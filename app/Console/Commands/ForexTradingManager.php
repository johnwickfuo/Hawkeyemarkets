<?php

namespace App\Console\Commands;

use App\Models\ForexTradingOrder;
use App\Models\ForexTradingPosition;
use App\Models\TradingAccount;
use App\Services\LozandServices;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ForexTradingManager extends Command
{
    protected $signature = 'lozand:manage-forex';
    protected $description = 'Pulse of the forex trading system: Update prices, fill orders, and manage TP/SL.';

    public function handle()
    {
        //check if forex module is loaded
        if (!moduleEnabled('forex_module')) {
            $this->info("Forex module is not enabled. Please enable it first.");
            return 0;
        }
        $this->info("Starting Forex Trading Manager...");

        $lozandServices = new LozandServices();
        $ticker_data = $lozandServices->forexTickers();

        if ($ticker_data['status'] !== 'success') {
            $this->error("Failed to fetch ticker data: " . $ticker_data['message']);
            return 1;
        }

        $all_tickers = $ticker_data['data'];
        $price_map = [];
        foreach ($all_tickers as $ticker) {
            $symbol = str_replace('/', '_', $ticker['s']);
            $price_map[$symbol] = [
                'bid' => (float) $ticker['b'],
                'ask' => (float) $ticker['a'],
            ];
        }

        // 1. Update Position Prices and Check TP/SL
        $this->updatePositions($price_map);

        // 2. Fill Limit/Stop Orders
        $this->fillOrders($price_map);

        // 3. check Liquidation
        $this->checkLiquidation();

        $this->info("Forex Trading Manager cycle complete.");

        updateLastCronJob($this->signature);

        return 0;
    }

    protected function updatePositions(array $price_map)
    {
        $positions = ForexTradingPosition::where('status', 'open')->get();

        foreach ($positions as $position) {
            if (!isset($price_map[$position->symbol])) {
                continue;
            }

            $ticker = $price_map[$position->symbol];
            /** @var ForexTradingPosition $position */
            $current_price = ($position->side === 'Buy') ? $ticker['bid'] : $ticker['ask'];

            // Calculate PnL (Simplified: (Current Price - Entry Price) * Units)
            // 1 Lot = 100,000 units
            $units = $position->volume * 100000;
            $unrealized_pnl = 0;
            if ($position->side === 'Buy') {
                $unrealized_pnl = ($current_price - $position->entry_price) * $units;
            } else {
                $unrealized_pnl = ($position->entry_price - $current_price) * $units;
            }

            $position->update([
                'current_price' => $current_price,
                'unrealized_pnl' => $unrealized_pnl,
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

        if ($position->side === 'Buy') {
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
            $this->info("Triggered {$trigger_type} for {$position->symbol} [{$position->side}] at {$current_price}");
            $this->closePosition($position, $current_price, $trigger_type);
        }
    }

    protected function closePosition($position, $price, $reason)
    {
        DB::transaction(function () use ($position, $price, $reason) {
            $user = $position->user;
            $trading_account = $user->tradingAccounts()
                ->where('account_type', 'forex')
                ->where('account_status', 'active')
                ->where('mode', $position->mode)
                ->first();

            // Calculate final PnL
            $units = $position->volume * 100000;
            $pnl = ($position->side === 'Buy')
                ? ($price - $position->entry_price) * $units
                : ($position->entry_price - $price) * $units;

            // Refund Margin + PnL
            $final_amount = $position->margin + $pnl;
            $trading_account->increment('balance', $final_amount);

            // Create Order record for closing
            ForexTradingOrder::create([
                'user_id' => $user->id,
                'symbol' => $position->symbol,
                'mode' => $position->mode,
                'type' => $position->side === 'Buy' ? 'Sell' : 'Buy',
                'order_type' => 'Market',
                'volume' => $position->volume,
                'price' => $price,
                'status' => 'filled',
            ]);

            $position->update(['status' => 'closed']);
        });
    }

    protected function fillOrders(array $price_map)
    {
        $orders = ForexTradingOrder::whereIn('order_type', ['Limit', 'Stop'])
            ->where('status', 'pending')
            ->get();

        foreach ($orders as $order) {
            if (!isset($price_map[$order->symbol])) {
                continue;
            }

            $ticker = $price_map[$order->symbol];
            $current_price = ($order->type === 'Buy') ? $ticker['ask'] : $ticker['bid'];
            $target_price = $order->price;
            $should_fill = false;

            if ($order->order_type === 'Limit') {
                if ($order->type === 'Buy' && $current_price <= $target_price)
                    $should_fill = true;
                if ($order->type === 'Sell' && $current_price >= $target_price)
                    $should_fill = true;
            } else { // Stop
                if ($order->type === 'Buy' && $current_price >= $target_price)
                    $should_fill = true;
                if ($order->type === 'Sell' && $current_price <= $target_price)
                    $should_fill = true;
            }

            if ($should_fill) {
                $this->info("Filling Order for {$order->symbol} at {$current_price}");
                $this->executeFill($order, $current_price);
            }
        }
    }

    protected function executeFill($order, $current_price)
    {
        DB::transaction(function () use ($order, $current_price) {
            $user = $order->user;
            $trading_account = $user->tradingAccounts()
                ->where('account_type', 'forex')
                ->where('account_status', 'active')
                ->where('mode', $order->mode)
                ->first();

            $leverage = 100;
            $units = $order->volume * 100000;
            $margin_required = ($units * $current_price) / $leverage;

            if ($trading_account->balance >= $margin_required) {
                $trading_account->decrement('balance', $margin_required);

                ForexTradingPosition::create([
                    'user_id' => $user->id,
                    'symbol' => $order->symbol,
                    'mode' => $order->mode,
                    'side' => $order->type,
                    'volume' => $order->volume,
                    'entry_price' => $current_price,
                    'current_price' => $current_price,
                    'stop_loss' => $order->stop_loss,
                    'take_profit' => $order->take_profit,
                    'margin' => $margin_required,
                    'status' => 'open',
                ]);

                $order->update(['status' => 'filled']);
            } else {
                $this->warn("Insufficient balance to fill order for user {$user->id}");
            }
        });
    }

    protected function checkLiquidation()
    {
        // Group positions by user and mode to calculate margin level per account context
        $positions = ForexTradingPosition::where('status', 'open')->get();
        $grouped = $positions->groupBy(function ($item) {
            return $item->user_id . '-' . $item->mode;
        });

        foreach ($grouped as $key => $userPositions) {
            list($userId, $mode) = explode('-', $key);
            $user = \App\Models\User::find($userId);

            if (!$user)
                continue;

            $trading_account = $user->tradingAccounts()
                ->where('account_type', 'forex')
                ->where('account_status', 'active')
                ->where('mode', $mode)
                ->first();

            if (!$trading_account)
                continue;

            $balance = (float) $trading_account->balance;
            $usedMargin = (float) $userPositions->sum('margin');
            $unrealizedPnL = (float) $userPositions->sum('unrealized_pnl');

            $marginLevel = \App\Services\TradingUtility::calculateMarginLevel($balance, $usedMargin, $unrealizedPnL);

            if ($marginLevel < 20) {
                $this->info("Liquidating User {$userId} ({$mode}) - Margin Level: {$marginLevel}%");
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
            $trading_account = $user->tradingAccounts()
                ->where('account_type', 'forex')
                ->where('account_status', 'active')
                ->where('mode', $position->mode)
                ->first();

            // Calculate final PnL
            $units = $position->volume * 100000;
            $pnl = ($position->side === 'Buy')
                ? ($price - $position->entry_price) * $units
                : ($position->entry_price - $price) * $units;

            // Refund Margin + PnL
            $final_amount = $position->margin + $pnl;

            // Ensure we don't refund negative if PnL eats into margin excessively (though typically liquidation happens before equity < 0)
            // But if gap down happens, equity could be negative.
            // In Forex, typically balance reflects realized PnL.
            // Here we increment balance by margin + pnl.
            // If pnl is -$100 and margin is $50, result is -$50.
            // Balance becomes Balance - 50. Correct.

            $trading_account->increment('balance', $final_amount);

            // Create Order record for closing
            ForexTradingOrder::create([
                'user_id' => $user->id,
                'symbol' => $position->symbol,
                'mode' => $position->mode,
                'type' => $position->side === 'Buy' ? 'Sell' : 'Buy',
                'order_type' => 'Market',
                'volume' => $position->volume,
                'price' => $price,
                'status' => 'filled',
                // 'notes' => 'Liquidated' // If we had a notes column
            ]);

            $position->update(['status' => 'liquidated']);
        });
    }
}
