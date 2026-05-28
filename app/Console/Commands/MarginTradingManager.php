<?php

namespace App\Console\Commands;

use App\Models\MarginTradingOrder;
use App\Models\MarginTradingPosition;
use App\Models\TradingAccount;
use App\Services\LozandServices;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MarginTradingManager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lozand:manage-margin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pulse of the margin trading system: Update prices, fill orders, and manage TP/SL.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //check if margin module is loaded
        if (!moduleEnabled('margin_module')) {
            $this->info("Margin module is not enabled. Please enable it first.");
            return 0;
        }

        $this->info("Starting Margin Trading Manager...");

        $lozandServices = new LozandServices();
        $ticker_data = $lozandServices->margins();

        if (!is_array($ticker_data)) {
            \Log::error("Failed to fetch ticker data: " . json_encode($ticker_data));
            return 1;
        }
        if ($ticker_data['status'] !== 'success') {
            $this->error("Failed to fetch ticker data: " . $ticker_data['message']);
            return 1;
        }

        $all_tickers = $ticker_data['data'];
        $price_map = [];
        foreach ($all_tickers as $ticker) {
            $price_map[$ticker['ticker']] = (float) $ticker['current_price'];
        }

        // 1. Update Position Prices and Check TP/SL
        $this->info("Updating positions...");
        $this->updatePositions($price_map);

        // 2. Fill Limit Orders
        // 2. Fill Limit Orders
        $this->info("Checking limit orders...");
        $this->fillLimitOrders($price_map);

        // 3. check Liquidation
        $this->checkLiquidation();

        $this->info("Margin Trading Manager cycle complete.");

        updateLastCronJob($this->signature);

        return 0;
    }

    protected function updatePositions(array $price_map)
    {
        $positions = MarginTradingPosition::where('status', 'open')->get();

        foreach ($positions as $position) {
            if (!isset($price_map[$position->ticker])) {
                continue;
            }

            $current_price = $price_map[$position->ticker];
            $entry_price = (float) $position->entry_price;
            $size = (float) $position->size;
            $side = $position->side;

            // Calculate PnL
            $unrealized_pnl = 0;
            if ($side === 'buy') {
                $unrealized_pnl = ($current_price - $entry_price) * $size;
            } else {
                $unrealized_pnl = ($entry_price - $current_price) * $size;
            }

            /** @var MarginTradingPosition $position */
            $position->update([
                'current_price' => $current_price,
                'unrealized_pnl' => $unrealized_pnl,
                'timestamp' => (string) now()->valueOf(), // ms
            ]);

            // Check TP/SL
            $this->checkTriggers($position, $current_price);
        }
    }

    protected function checkTriggers($position, $current_price)
    {
        $tp = (float) $position->take_profit;
        $sl = (float) $position->stop_loss;
        $triggered = false;
        $trigger_type = null;

        if ($position->side === 'buy') {
            if ($tp > 0 && $current_price >= $tp) {
                $triggered = true;
                $trigger_type = 'Take Profit';
            } elseif ($sl > 0 && $current_price <= $sl) {
                $triggered = true;
                $trigger_type = 'Stop Loss';
            }
        } else {
            if ($tp > 0 && $current_price <= $tp) {
                $triggered = true;
                $trigger_type = 'Take Profit';
            } elseif ($sl > 0 && $current_price >= $sl) {
                $triggered = true;
                $trigger_type = 'Stop Loss';
            }
        }

        if ($triggered) {
            $this->info("Triggered {$trigger_type} for {$position->ticker} [{$position->side}] at {$current_price}");
            $this->closePosition($position, $current_price, $trigger_type);
        }
    }

    protected function closePosition($position, $price, $reason)
    {
        DB::transaction(function () use ($position, $price, $reason) {
            $user = $position->user;
            $trading_account = $user->tradingAccounts()->where('account_type', 'margin')->first();

            // Calculate final PnL
            $pnl = 0;
            if ($position->side === 'buy') {
                $pnl = ($price - (float) $position->entry_price) * (float) $position->size;
            } else {
                $pnl = ((float) $position->entry_price - $price) * (float) $position->size;
            }

            // Refund Margin + PnL
            $final_amount = (float) $position->margin + $pnl;
            if ($trading_account) {
                if ($trading_account->borrowed > 0) {
                    $to_repay = min((float) $trading_account->borrowed, $final_amount);
                    $trading_account->decrement('borrowed', (float) $to_repay);
                    $final_amount -= $to_repay;
                }
                if ($final_amount > 0) {
                    $trading_account->increment('balance', $final_amount);
                }
            }

            // Create Order record for closing
            MarginTradingOrder::create([
                'user_id' => $user->id,
                'type' => 'market',
                'ticker' => $position->ticker,
                'side' => $position->side === 'buy' ? 'sell' : 'buy',
                'size' => $position->size,
                'price' => $price,
                'status' => 'filled',
                'leverage' => $position->leverage,
                'timestamp' => (string) now()->valueOf(), // ms
            ]);

            $position->delete();
        });
    }

    protected function fillLimitOrders(array $price_map)
    {
        $orders = MarginTradingOrder::where('type', 'limit')
            ->where('status', 'pending')
            ->get();

        foreach ($orders as $order) {
            if (!isset($price_map[$order->ticker])) {
                continue;
            }

            $current_price = $price_map[$order->ticker];
            $limit_price = (float) $order->price;
            $should_fill = false;

            if ($order->side === 'buy') {
                if ($current_price <= $limit_price) {
                    $should_fill = true;
                }
            } else {
                if ($current_price >= $limit_price) {
                    $should_fill = true;
                }
            }

            if ($should_fill) {
                $this->info("Filling Limit Order for {$order->ticker} at {$current_price}");
                $this->executeFill($order, $current_price);
            }
        }
    }

    protected function executeFill($order, $current_price)
    {
        DB::transaction(function () use ($order, $current_price) {
            $user = $order->user;
            $position = MarginTradingPosition::where('user_id', $user->id)
                ->where('ticker', $order->ticker)
                ->first();

            $base_amount = (float) $order->size;
            $entry_price = (float) $order->price;
            $required_margin = (float) $order->locked_margin;
            $trading_account = $user->tradingAccounts()->where('account_type', 'margin')->first();

            if ($position) {
                if ($position->side === $order->side) {
                    // Adding to position
                    $total_size = (float) $position->size + $base_amount;
                    $new_entry_price = (((float) $position->entry_price * (float) $position->size) + ($entry_price * $base_amount)) / $total_size;

                    $position->update([
                        'size' => $total_size,
                        'entry_price' => $new_entry_price,
                        'current_price' => $current_price,
                        'margin' => (float) $position->margin + $required_margin,
                        'take_profit' => $order->take_profit,
                        'stop_loss' => $order->stop_loss,
                        'timestamp' => (string) now()->valueOf(), // ms
                    ]);
                } else {
                    // Reducing, Closing, or Reversing
                    if ((float) $position->size > $base_amount) {
                        // Partial close
                        $margin_to_refund = ((float) $position->margin / (float) $position->size) * $base_amount;
                        if ($trading_account) {
                            $refund_amount = (float) $margin_to_refund;
                            if ($trading_account->borrowed > 0) {
                                $to_repay = min((float) $trading_account->borrowed, $refund_amount);
                                $trading_account->decrement('borrowed', (float) $to_repay);
                                $refund_amount -= $to_repay;
                            }
                            if ($refund_amount > 0) {
                                $trading_account->increment('balance', $refund_amount);
                            }
                        }

                        $position->update([
                            'size' => (float) $position->size - $base_amount,
                            'current_price' => $current_price,
                            'margin' => (float) $position->margin - $margin_to_refund,
                            'timestamp' => (string) now()->valueOf(), // ms
                        ]);
                    } elseif ((float) $position->size == $base_amount) {
                        if ($trading_account) {
                            $refund_amount = (float) $position->margin;
                            if ($trading_account->borrowed > 0) {
                                $to_repay = min((float) $trading_account->borrowed, $refund_amount);
                                $trading_account->decrement('borrowed', (float) $to_repay);
                                $refund_amount -= $to_repay;
                            }
                            if ($refund_amount > 0) {
                                $trading_account->increment('balance', (float) $refund_amount);
                            }
                        }
                        $position->delete();
                    } else {
                        // Reverse position
                        if ($trading_account) {
                            $refund_amount = (float) $position->margin;
                            if ($trading_account->borrowed > 0) {
                                $to_repay = min((float) $trading_account->borrowed, $refund_amount);
                                $trading_account->decrement('borrowed', (float) $to_repay);
                                $refund_amount -= $to_repay;
                            }
                            if ($refund_amount > 0) {
                                $trading_account->increment('balance', (float) $refund_amount);
                            }
                        }
                        $remaining_base_amount = $base_amount - (float) $position->size;

                        $position->update([
                            'side' => $order->side,
                            'size' => $remaining_base_amount,
                            'entry_price' => $entry_price,
                            'current_price' => $current_price,
                            'margin' => $required_margin,
                            'take_profit' => $order->take_profit,
                            'stop_loss' => $order->stop_loss,
                            'timestamp' => (string) now()->valueOf(), // ms
                        ]);
                    }
                }
            } else {
                // New position
                MarginTradingPosition::create([
                    'user_id' => $user->id,
                    'ticker' => $order->ticker,
                    'side' => $order->side,
                    'size' => $base_amount,
                    'entry_price' => $entry_price,
                    'current_price' => $current_price,
                    'margin' => $required_margin,
                    'leverage' => $order->leverage,
                    'take_profit' => $order->take_profit,
                    'stop_loss' => $order->stop_loss,
                    'unrealized_pnl' => 0,
                    'realized_pnl' => 0,
                    'timestamp' => (string) now()->valueOf(), // ms
                ]);
            }

            $order->update(['status' => 'filled']);
        });
    }

    protected function checkLiquidation()
    {
        // Group positions by user
        $positions = MarginTradingPosition::where('status', 'open')->get();
        $grouped = $positions->groupBy('user_id');

        foreach ($grouped as $userId => $userPositions) {
            $user = \App\Models\User::find($userId);
            if (!$user)
                continue;

            $trading_account = $user->tradingAccounts()
                ->where('account_type', 'margin')
                // ->where('account_status', 'active')
                ->first();

            if (!$trading_account)
                continue;

            $balance = (float) $trading_account->balance;
            $usedMargin = (float) $userPositions->sum('margin');
            $unrealizedPnL = (float) $userPositions->sum('unrealized_pnl');

            $marginLevel = \App\Services\TradingUtility::calculateMarginLevel($balance, $usedMargin, $unrealizedPnL);

            if ($marginLevel < 20) {
                $this->info("Liquidating User {$userId} (Margin) - Margin Level: {$marginLevel}%");
                foreach ($userPositions as $position) {
                    $this->liquidatePosition($position, $position->current_price);
                }
            }
        }
    }

    protected function liquidatePosition($position, $price)
    {
        DB::transaction(function () use ($position, $price) {
            $user = $position->user;
            $trading_account = $user->tradingAccounts()->where('account_type', 'margin')->first();

            // Calculate final PnL
            $pnl = 0;
            if ($position->side === 'buy') {
                $pnl = ($price - (float) $position->entry_price) * (float) $position->size;
            } else {
                $pnl = ((float) $position->entry_price - $price) * (float) $position->size;
            }

            // Refund Margin + PnL
            $final_amount = (float) $position->margin + $pnl;
            if ($trading_account) {
                if ($trading_account->borrowed > 0) {
                    $to_repay = min((float) $trading_account->borrowed, $final_amount);
                    $trading_account->decrement('borrowed', (float) $to_repay);
                    $final_amount -= $to_repay;
                }
                if ($final_amount > 0) {
                    $trading_account->increment('balance', $final_amount);
                }
            }

            // Create Order record for closing
            MarginTradingOrder::create([
                'user_id' => $user->id,
                'type' => 'market',
                'ticker' => $position->ticker,
                'side' => $position->side === 'buy' ? 'sell' : 'buy',
                'size' => $position->size,
                'price' => $price,
                'status' => 'filled',
                'leverage' => $position->leverage,
                'timestamp' => (string) now()->valueOf(), // ms
            ]);

            $position->delete();
        });
    }
}
