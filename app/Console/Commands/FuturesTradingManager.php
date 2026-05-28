<?php

namespace App\Console\Commands;

use App\Models\FuturesTradingOrders;
use App\Models\FuturesTradingPositions;
use App\Models\TradingAccount;
use App\Services\LozandServices;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FuturesTradingManager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lozand:manage-futures';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pulse of the futures trading system: Update prices, fill orders, and manage TP/SL.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //check if forex module is loaded
        if (!moduleEnabled('futures_module')) {
            $this->info("Futures module is not enabled. Please enable it first.");
            return 0;
        }

        $this->info("Starting Futures Trading Manager...");

        $lozandServices = new LozandServices();
        $ticker_data = $lozandServices->futureTickers();

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
        $this->info("Checking limit orders...");
        $this->fillLimitOrders($price_map);

        // 3. check Liquidation
        $this->checkLiquidation();

        $this->info("Futures Trading Manager cycle complete.");

        updateLastCronJob($this->signature);

        return 0;
    }

    protected function updatePositions(array $price_map)
    {
        $positions = FuturesTradingPositions::all();

        foreach ($positions as $position) {
            if (!isset($price_map[$position->ticker])) {
                continue;
            }

            $current_price = $price_map[$position->ticker];
            $entry_price = $position->entry_price;
            $size = $position->size;
            $side = $position->side;

            // Calculate PnL
            $unrealized_pnl = 0;
            if ($side === 'buy') {
                $unrealized_pnl = ($current_price - $entry_price) * $size;
            } else {
                $unrealized_pnl = ($entry_price - $current_price) * $size;
            }

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
        $tp = $position->take_profit;
        $sl = $position->stop_loss;
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
            $trading_account = $user->tradingAccounts()->where('account_type', 'futures')->first();

            // Calculate final PnL
            $pnl = 0;
            if ($position->side === 'buy') {
                $pnl = ($price - $position->entry_price) * $position->size;
            } else {
                $pnl = ($position->entry_price - $price) * $position->size;
            }

            // Refund Margin + PnL
            $final_amount = $position->margin + $pnl;
            $trading_account->increment('balance', $final_amount);

            // Create Order record for closing
            FuturesTradingOrders::create([
                'user_id' => $user->id,
                'type' => 'market', // TP/SL closes at current market price
                'ticker' => $position->ticker,
                'side' => $position->side === 'buy' ? 'sell' : 'buy',
                'size' => $position->size,
                'price' => $price,
                'status' => 'filled',
                'order_id' => 'ORD-' . strtoupper(Str::random(10)),
                'timestamp' => (string) now()->valueOf(), // ms
            ]);

            $position->delete();
        });
    }

    protected function fillLimitOrders(array $price_map)
    {
        $orders = FuturesTradingOrders::where('type', 'limit')
            ->where('status', 'pending')
            ->get();

        foreach ($orders as $order) {
            if (!isset($price_map[$order->ticker])) {
                continue;
            }

            $current_price = $price_map[$order->ticker];
            $limit_price = $order->price;
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
                $this->info("Filling Limit Order {$order->order_id} for {$order->ticker} at {$current_price}");
                $this->executeFill($order, $current_price);
            }
        }
    }

    protected function executeFill($order, $current_price)
    {
        DB::transaction(function () use ($order, $current_price) {
            $user = $order->user;
            $position = FuturesTradingPositions::where('user_id', $user->id)
                ->where('ticker', $order->ticker)
                ->first();

            $base_amount = $order->size;
            $entry_price = $order->price;
            $required_margin = $order->locked_margin;
            $trading_account = $user->tradingAccounts()->where('account_type', 'futures')->first();

            if ($position) {
                if ($position->side === $order->side) {
                    // Adding to position
                    // locked_margin was already deducted from balance.
                    $total_size = $position->size + $base_amount;
                    $new_entry_price = (($position->entry_price * $position->size) + ($entry_price * $base_amount)) / $total_size;

                    $position->update([
                        'size' => $total_size,
                        'entry_price' => $new_entry_price,
                        'current_price' => $current_price,
                        'margin' => $position->margin + $required_margin,
                        'take_profit' => $order->take_profit,
                        'stop_loss' => $order->stop_loss,
                        'timestamp' => (string) now()->valueOf(), // ms
                    ]);
                } else {
                    // Reducing, Closing, or Reversing
                    // This is more complex because of how margin was locked for reversals.
                    // But in trade() we already did the deduction logic.

                    if ($position->size > $base_amount) {
                        // Partial close
                        $margin_to_refund = ($position->margin / $position->size) * $base_amount;
                        $trading_account = $user->tradingAccounts()->where('account_type', 'futures')->first();
                        $trading_account->increment('balance', $margin_to_refund);

                        $position->update([
                            'size' => $position->size - $base_amount,
                            'current_price' => $current_price,
                            'margin' => $position->margin - $margin_to_refund,
                            'timestamp' => (string) now()->valueOf(), // ms
                        ]);
                    } elseif ($position->size == $base_amount) {
                        // Full close
                        $trading_account->increment('balance', $position->margin);
                        $position->delete();
                    } else {
                        // Reverse position
                        // 1. Refund current margin
                        $trading_account->increment('balance', $position->margin);

                        // 2. The locked_margin for the new part was already deducted in trade() logic.
                        $remaining_base_amount = $base_amount - $position->size;

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
                // locked_margin was already deducted from balance.
                FuturesTradingPositions::create([
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
        $positions = FuturesTradingPositions::all();
        $grouped = $positions->groupBy('user_id');

        foreach ($grouped as $userId => $userPositions) {
            $user = \App\Models\User::find($userId);
            if (!$user)
                continue;

            $trading_account = $user->tradingAccounts()
                ->where('account_type', 'futures')
                // ->where('account_status', 'active') // Assuming active if positions exist
                ->first();

            if (!$trading_account)
                continue;

            $balance = (float) $trading_account->balance;
            $usedMargin = (float) $userPositions->sum('margin');
            $unrealizedPnL = (float) $userPositions->sum('unrealized_pnl');

            $marginLevel = \App\Services\TradingUtility::calculateMarginLevel($balance, $usedMargin, $unrealizedPnL);

            if ($marginLevel < 20) {
                $this->info("Liquidating User {$userId} (Futures) - Margin Level: {$marginLevel}%");
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
            $trading_account = $user->tradingAccounts()->where('account_type', 'futures')->first();

            // Calculate final PnL
            $pnl = 0;
            if ($position->side === 'buy') {
                $pnl = ($price - $position->entry_price) * $position->size;
            } else {
                $pnl = ($position->entry_price - $price) * $position->size;
            }

            // Refund Margin + PnL
            // Logic similar to closePosition, but we are force closing entire position
            $final_amount = $position->margin + $pnl;
            $trading_account->increment('balance', $final_amount);

            // Create Order record for closing
            FuturesTradingOrders::create([
                'user_id' => $user->id,
                'type' => 'market',
                'ticker' => $position->ticker,
                'side' => $position->side === 'buy' ? 'sell' : 'buy',
                'size' => $position->size,
                'price' => $price,
                'status' => 'filled',
                'order_id' => 'LIQ-' . strtoupper(Str::random(10)), // Prefix order ID to indicate Liquidation
                'timestamp' => (string) now()->valueOf(), // ms
            ]);

            $position->delete();
        });
    }
}
