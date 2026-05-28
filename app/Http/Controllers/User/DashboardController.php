<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BondHolding;
use App\Models\BondHoldingHistory;
use App\Models\NotificationMessage;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $template = config('site.template');
        $page_title = __('Dashboard');
        $user = auth()->user();

        // 1. Hero Stats
        $balance = $user->balance;
        $rate = rateConverter(1, 'USD', getSetting('currency'), 'gen')['converted_amount'];
        $trading_equity = $user->tradingAccounts()->sum('equity') * $rate;
        $bond_holdings_value = $user->bondHoldings()->active()->sum('amount');
        // Total Equity = Balance + Trading Equity + Bonds
        $total_equity = $balance + $trading_equity + $bond_holdings_value;

        // 2. Borrowed & Margin
        $borrowed = $user->tradingAccounts()->sum('borrowed');

        // 3. Open Positions (Exposure)
        $futuresPositions = $user->futuresTradingPositions()->get();
        $marginPositions = $user->marginTradingPositions()->where('status', 'open')->get();
        $forexPositions = $user->forexTradingPositions()->where('status', 'open')->get();

        $open_positions_count = $futuresPositions->count() + $marginPositions->count() + $forexPositions->count();

        // Sum Unrealized PnL
        $open_pnl = ($futuresPositions->sum('unrealized_pnl') +
            $marginPositions->sum('unrealized_pnl') +
            $forexPositions->sum('unrealized_pnl')) * $rate;

        // Total Margin Used
        $margin_used = ($futuresPositions->sum('margin') +
            $marginPositions->sum('margin') +
            $forexPositions->sum('margin')) * $rate;

        // 4. Activity (Today / Last 7 Days) - Simplified to Monthly/Total for "At a glance" based on mockup
        // Deposits
        $deposits_month = $user->deposits()->whereMonth('created_at', now()->month)->where('status', 'approved')->sum('amount');
        $deposits_pending = $user->deposits()->where('status', 'pending')->count();

        // Withdrawals
        $withdrawals_month_amount = $user->withdrawals()->whereMonth('created_at', now()->month)->where('status', 'approved')->sum('amount');
        $withdrawals_pending = $user->withdrawals()->where('status', 'pending')->count();

        // Trades count
        $trades_count = $user->futuresTradingOrders()->count() +
            $user->marginTradingOrders()->count() +
            $user->forexTradingOrders()->count();

        // 5. Portfolio (Stocks & ETFs)
        $stock_holdings = $user->stockHoldings()->with('stockHoldingHistories')->get();
        $etf_holdings = $user->etfHoldings()->with('etfHoldingHistories')->get();

        // Combine holdings for analytics
        $bond_holdings = $user->bondHoldings()->active()->get();
        $all_holdings = $stock_holdings->concat($etf_holdings)->concat($bond_holdings);

        // Combine histories for analytics
        $bond_histories = $user->bondHoldingHistories;
        $all_histories = $user->stockHoldingHistories->concat($user->etfHoldingHistories)->concat($bond_histories);

        // Realized PnL Calculation (Simplified balance impact from histories)
        $realized_pnl = (($user->stockHoldingHistories()->sold()->sum('amount') - $user->stockHoldingHistories()->bought()->sum('amount') +
            $user->etfHoldingHistories()->sold()->sum('amount') - $user->etfHoldingHistories()->bought()->sum('amount')) * $rate) +
            $user->bondHoldingHistories()->where('transaction_type', 'payout')->sum('interest_amount');

        // Total Fees paid and Average Fee %
        $total_fees = (($user->stockHoldingHistories()->sum('fee_amount') +
            $user->etfHoldingHistories()->sum('fee_amount')) * $rate) +
            $user->bondHoldingHistories()->sum('fee_amount');
        $avg_fee_percent = $all_histories->avg('fee_amount_percent') ?? 0;

        // Analytics: Top Winners/Losers
        $top_winners = $all_holdings->sortByDesc('pnl_percent')->take(3);
        $top_losers = $all_holdings->sortBy('pnl_percent')->take(3);

        // Portfolio Allocation (By Current Value)
        $total_portfolio_value = $all_holdings->sum(function ($h) {
            if ($h instanceof BondHolding)
                return $h->amount;
            return $h->shares * $h->average_price;
        });
        $allocation_by_ticker = $all_holdings->mapWithKeys(function ($h) use ($total_portfolio_value) {
            $value = ($h instanceof BondHolding) ? $h->amount : ($h->shares * $h->average_price);
            $ticker = ($h instanceof BondHolding) ? ($h->cusip ?? 'BOND') : $h->ticker;
            $percent = $total_portfolio_value > 0 ? ($value / $total_portfolio_value) * 100 : 0;
            return [$ticker => round($percent, 1)];
        })->sortByDesc(fn($p) => $p)->take(4);

        // Most Traded tickers (from combined histories)
        $most_traded_tickers = $all_histories->groupBy('ticker')
            ->map(function ($group) {
                return $group->count();
            })
            ->sortByDesc(fn($count) => $count)
            ->take(5);

        // 6. Section 2: Analytics & Insights
        // A. Transaction Graph (Dynamic Filtering)
        $filter = request()->get('days', '7');
        $startDate = now();
        $isAllTime = false;
        $labelFormat = 'M d';

        if ($filter == '7') {
            $startDate = now()->subDays(6)->startOfDay();
            $isMonthly = false;
        } elseif ($filter == '30') {
            $startDate = now()->subDays(29)->startOfDay();
            $isMonthly = false;
        } elseif ($filter == '90') {
            $startDate = now()->subDays(89)->startOfDay();
            $isMonthly = false;
        } elseif ($filter == '365') {
            $startDate = now()->subDays(364)->startOfDay();
            $isMonthly = true;
            $labelFormat = 'M Y';
        } else {
            // All Time
            $isAllTime = true;
            $firstTx = $user->transactions()->orderBy('created_at')->first();
            $startDate = $firstTx ? $firstTx->created_at->startOfDay() : now()->subYear()->startOfDay();
            $isMonthly = true;
            $labelFormat = 'M Y';
        }

        $query = $user->transactions()->where('status', 'completed');
        if (!$isAllTime) {
            $query->where('created_at', '>=', $startDate);
        }

        $txHistory = $query->get();
        $labels = [];
        $credits = [];
        $debits = [];

        if ($isMonthly) {
            $current = (clone $startDate)->startOfMonth();
            $end = now()->endOfMonth();
            while ($current <= $end) {
                $monthStr = $current->format('Y-m');
                $labels[] = $current->format($labelFormat);
                $monthGroup = $txHistory->filter(fn($t) => $t->created_at->format('Y-m') == $monthStr);
                $credits[] = (float) $monthGroup->where('type', 'credit')->sum('amount');
                $debits[] = (float) $monthGroup->where('type', 'debit')->sum('amount');
                $current->addMonth();
            }
        } else {
            $current = (clone $startDate)->startOfDay();
            $end = now()->endOfDay();
            while ($current <= $end) {
                $dayStr = $current->format('Y-m-d');
                $labels[] = $current->format($labelFormat);
                $dayGroup = $txHistory->filter(fn($t) => $t->created_at->format('Y-m-d') == $dayStr);
                $credits[] = (float) $dayGroup->where('type', 'credit')->sum('amount');
                $debits[] = (float) $dayGroup->where('type', 'debit')->sum('amount');
                $current->addDay();
            }
        }

        $chart_data = [
            'labels' => $labels,
            'credits' => $credits,
            'debits' => $debits,
        ];

        // If AJAX request for chart data only
        if (request()->ajax() && request()->has('days')) {
            return response()->json($chart_data);
        }

        // B. Money Distribution (Pie Chart)
        $investments_value = $user->investments()->active()->sum('capital_invested');
        $stocks_value = $stock_holdings->sum(fn($h) => $h->shares * $h->average_price);
        $etfs_value = $etf_holdings->sum(fn($h) => $h->shares * $h->average_price);

        $money_distribution = [
            'Wallet' => (float) $balance,
            'Investments' => (float) $investments_value,
            'Stocks' => (float) $stocks_value * $rate,
            'ETFs' => (float) $etfs_value * $rate,
            'Bonds' => (float) $bond_holdings_value,
            'Trading' => (float) $trading_equity,
        ];

        // C. Smart Insights
        $smart_insights = [];

        // 1. PnL Hub
        $total_unrealized = $open_pnl + (($stock_holdings->sum('pnl') + $etf_holdings->sum('pnl')) * $rate);
        $total_realized_pnl = $realized_pnl + ($user->futuresTradingPositions()->sum('realized_pnl') * $rate); // Realized from combined sources
        $smart_insights[] = [
            'type' => 'trend_up',
            'title' => __('PnL Hub'),
            'text' => __('Your total ecosystem PnL is ') . showAmount($total_unrealized + $total_realized_pnl) . __(' with ') . showAmount($total_realized_pnl) . __(' already secured.'),
            'index' => 1
        ];

        // 2. Risk Guard (Concentration)
        $top_allocation = $allocation_by_ticker->first() ?? 0;
        if ($top_allocation > 40) {
            $smart_insights[] = [
                'type' => 'trend_down',
                'title' => __('Risk Alert'),
                'text' => __('High concentration detected: ') . $allocation_by_ticker->keys()->first() . __(' represents ') . $top_allocation . __('% of your portfolio. Consider diversifying.'),
                'index' => 2
            ];
        }

        // 3. Behavior (Best Hour)
        $best_hour = $user->futuresTradingOrders()->selectRaw('HOUR(created_at) as hour, count(*) as count')->groupBy('hour')->orderByDesc('count')->first();
        if ($best_hour) {
            $smart_insights[] = [
                'type' => 'best_day',
                'title' => __('Trading Pulse'),
                'text' => __('You are most active at ') . $best_hour->hour . __(':00. Data shows this is your peak decision window.'),
                'index' => 3
            ];
        }

        // 4. Fees
        $smart_insights[] = [
            'type' => 'source',
            'title' => __('Fee Analytics'),
            'text' => __('You have paid ') . showAmount($total_fees) . __(' in total fees. Optimized trading could save you up to 15% yearly.'),
            'index' => 4
        ];

        // 5. Forecast
        $active_investments = $user->investments()->active()->get();
        $projected_roi = $active_investments->sum(fn($i) => ($i->capital_invested * ($i->plan->return_percent / 100)) * ($i->total_cycles - $i->cycle_count));
        if ($projected_roi > 0) {
            $smart_insights[] = [
                'type' => 'frequency',
                'title' => __('ROI Forecast'),
                'text' => __('Active investments are projected to yield an additional ') . showAmount($projected_roi) . __(' by their respective expiry dates.'),
                'index' => 5
            ];
        }

        // 6. Portfolio Diversity
        $unique_assets = $all_holdings->count();
        $smart_insights[] = [
            'type' => 'trend_up',
            'title' => __('Diversity Score'),
            'text' => __('You are currently holding ') . $unique_assets . __(' unique assets. Broad diversity protects against market volatility.'),
            'index' => 6
        ];

        // 7. Top Performer
        if ($top_winners->count() > 0) {
            $best = $top_winners->first();
            $smart_insights[] = [
                'type' => 'trend_up',
                'title' => __('Top Performer'),
                'text' => $best->ticker . __(' is leading your portfolio with a ') . $best->pnl_percent . __('% gain. Monitoring winners is key to growth.'),
                'index' => 7
            ];
        }

        // 8. High Activity
        if ($most_traded_tickers->count() > 0) {
            $hot = $most_traded_tickers->keys()->first();
            $smart_insights[] = [
                'type' => 'frequency',
                'title' => __('Hot Asset'),
                'text' => __('You have high trading velocity in ') . $hot . __('. Ensure your rebalancing strategy accounts for transaction costs.'),
                'index' => 8
            ];
        }

        // 9. Pending Movements
        if ($deposits_pending > 0 || $withdrawals_pending > 0) {
            $smart_insights[] = [
                'type' => 'trend_down',
                'title' => __('Pending Flow'),
                'text' => __('There are currently ') . ($deposits_pending + $withdrawals_pending) . __(' operations in your pipeline. Your liquidity will update upon approval.'),
                'index' => 9
            ];
        }

        // 11. Section 4: Investments (Plans, ROI, earnings)
        $active_investments = $user->investments()->active()->with('plan')->get();
        $investment_stats = [
            'active_count' => $active_investments->count(),
            'total_capital' => (float) $active_investments->sum('capital_invested'),
            'total_compounding' => (float) $active_investments->sum('compounding_capital'),
            'total_roi' => (float) $active_investments->sum('roi_earned'),
            'total_interest' => (float) $user->investmentEarnings()->sum('interest'),
        ];

        // Earnings Ledger (Recent)
        $earnings_ledger = $user->investmentEarnings()->with('investment.plan')->latest()->take(6)->get();

        // Earnings Chart Data (Dynamic Filtering)
        $yield_filter = request()->get('yield_days', '7');
        $yield_days_count = ($yield_filter == '30') ? 30 : 7;

        $earnings_days = collect();
        for ($i = ($yield_days_count - 1); $i >= 0; $i--) {
            $earnings_days->push(now()->subDays($i)->format('Y-m-d'));
        }

        $earnings_history = $user->investmentEarnings()
            ->where('created_at', '>=', now()->subDays($yield_days_count))
            ->selectRaw('DATE(created_at) as day, SUM(amount) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $earnings_chart_data = [
            'labels' => $earnings_days->map(fn($d) => date('M d', strtotime($d))),
            'amounts' => $earnings_days->map(fn($day) => (float) $earnings_history->get($day, 0)),
        ];

        // AJAX Request for Yield Chart
        if (request()->ajax() && request()->has('yield_days')) {
            return response()->json($earnings_chart_data);
        }

        // 12. Section 5: Trading Cockpit
        $trading_accounts = $user->tradingAccounts()->get();

        // Categorized Positions
        $futures_positions = $user->futuresTradingPositions()->get();
        $margin_positions = $user->marginTradingPositions()->where('status', 'open')->get();
        $forex_positions = $user->forexTradingPositions()->where('status', 'open')->get();

        $categorized_positions = [
            'Futures' => $futures_positions->map(fn($p) => [
                'symbol' => $p->ticker,
                'side' => $p->side,
                'size' => $p->size,
                'entry' => (float) $p->entry_price,
                'current' => (float) $p->current_price,
                'tp' => (float) $p->take_profit,
                'sl' => (float) $p->stop_loss,
                'margin' => (float) $p->margin,
                'leverage' => $p->leverage,
                'pnl' => (float) $p->unrealized_pnl,
                'opened_at' => $p->created_at,
            ]),
            'Margin' => $margin_positions->map(fn($p) => [
                'symbol' => $p->ticker,
                'side' => $p->side,
                'size' => $p->size,
                'entry' => (float) $p->entry_price,
                'current' => (float) $p->current_price,
                'tp' => null,
                'sl' => null,
                'margin' => (float) $p->margin,
                'leverage' => $p->leverage,
                'pnl' => (float) $p->unrealized_pnl,
                'opened_at' => $p->created_at,
            ]),
            'Forex' => $forex_positions->map(fn($p) => [
                'symbol' => $p->symbol,
                'side' => $p->side,
                'size' => $p->volume,
                'entry' => (float) $p->entry_price,
                'current' => (float) $p->current_price,
                'tp' => (float) $p->take_profit,
                'sl' => (float) $p->stop_loss,
                'margin' => (float) $p->margin,
                'leverage' => 'N/A',
                'pnl' => (float) $p->unrealized_pnl,
                'opened_at' => $p->created_at,
            ]),
        ];

        // Categorized Recent Orders
        $categorized_orders = [
            'Futures' => $user->futuresTradingOrders()->latest()->take(10)->get()->map(fn($o) => [
                'asset' => $o->ticker,
                'type' => $o->type,
                'side' => $o->side,
                'price' => $o->price,
                'status' => $o->status,
                'at' => $o->created_at
            ]),
            'Margin' => $user->marginTradingOrders()->latest()->take(10)->get()->map(fn($o) => [
                'asset' => $o->ticker,
                'type' => $o->type,
                'side' => $o->side,
                'price' => $o->price,
                'status' => $o->status,
                'at' => $o->created_at
            ]),
            'Forex' => $user->forexTradingOrders()->latest()->take(10)->get()->map(fn($o) => [
                'asset' => $o->symbol,
                'type' => $o->type,
                'side' => $o->side,
                'price' => $o->price,
                'status' => $o->status,
                'at' => $o->created_at
            ]),
        ];

        // Aggregates for high-level summaries
        $all_positions_flat = collect($categorized_positions)->flatten(1);
        $total_market_exposure = (float) $all_positions_flat->sum('margin');
        $net_exposure = $all_positions_flat->groupBy('symbol')->map(fn($g) => $g->sum(fn($p) => $p['side'] == 'buy' ? $p['size'] : -$p['size']));

        // 13. Section 6: Money Movement Hub
        $deposits_all = $user->deposits()->with('paymentMethod')->latest()->get();
        $withdrawals_all = $user->withdrawals()->with('withdrawalMethod')->latest()->get();

        $movement_stats = [
            'deposits' => [
                'pending' => (float) $deposits_all->where('status', 'pending')->sum('amount'),
                'approved' => (float) $deposits_all->where('status', 'approved')->sum('amount'),
                'rejected' => (float) $deposits_all->where('status', 'rejected')->sum('amount'),
                'avg_size' => (float) ($deposits_all->where('status', 'approved')->avg('amount') ?? 0),
                'most_used_method' => $deposits_all->count() > 0 ? ($deposits_all->groupBy('payment_method_id')->sortByDesc(fn($g) => $g->count())->first()?->first()?->paymentMethod?->name ?? __('N/A')) : __('N/A'),
            ],
            'withdrawals' => [
                'pending' => (float) $withdrawals_all->where('status', 'pending')->sum('amount'),
                'paid' => (float) $withdrawals_all->where('status', 'approved')->sum('amount'),
                'rejected' => (float) $withdrawals_all->where('status', 'rejected')->sum('amount'),
                'total_fees' => (float) $withdrawals_all->where('status', 'approved')->sum('fee_amount'),
            ],
            'net_cashflow' => (float) ($deposits_month - $withdrawals_month_amount),
            'total_fees_month' => (float) ($user->deposits()->whereMonth('created_at', now()->month)->where('status', 'approved')->sum('fee_amount') +
                $user->withdrawals()->whereMonth('created_at', now()->month)->where('status', 'approved')->sum('fee_amount')),
            'recent_deposits' => $deposits_all->take(5),
            'recent_withdrawals' => $withdrawals_all->take(5),
        ];

        $recent_transactions_hub = $user->transactions()->latest()->take(10)->get();

        return view("templates.$template.blades.user.dashboard", compact(
            'page_title',
            'balance',
            'total_equity',
            'borrowed',
            'margin_used',
            'open_pnl',
            'open_positions_count',
            'deposits_month',
            'deposits_pending',
            'withdrawals_month_amount',
            'withdrawals_pending',
            'trades_count',
            'stock_holdings',
            'etf_holdings',
            'all_holdings',
            'realized_pnl',
            'total_fees',
            'avg_fee_percent',
            'top_winners',
            'top_losers',
            'allocation_by_ticker',
            'most_traded_tickers',
            'chart_data',
            'money_distribution',
            'smart_insights',
            'active_investments',
            'investment_stats',
            'earnings_ledger',
            'earnings_chart_data',
            'trading_accounts',
            'categorized_positions',
            'categorized_orders',
            'total_market_exposure',
            'net_exposure',
            'movement_stats',
            'recent_transactions_hub'
        ));
    }

    public function notificationMarkAsRead(Request $request)
    {
        $notification = NotificationMessage::findOrFail($request->notification_id);
        $notification->update(['status' => 'read']);
        return response()->json(['success' => true]);
    }


    // onboarding
    public function onboarding(Request $request)
    {
        // validate for first
        $request->validate([
            'risk_profile' => 'required|in:conservative,balanced,growth',
            'investment_goal' => 'required|in:short_term,medium_term,long_term',
        ]);
        $user = auth()->user();
        $user->onboarding()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'risk_profile' => $request->risk_profile,
                'investment_goal' => $request->investment_goal,
            ]
        );
        $risk_profile = str_replace('_', ' ', $request->risk_profile);
        $investment_goal = $request->investment_goal;
        $title = "Onboarding Completed"; //this will be translated in the blade when its queried from database
        $body = "Your onboarding has been completed successfully. Your risk level is $risk_profile and your investment goal is $investment_goal";
        recordNotificationMessage($user, $title, $body);

        return response()->json([
            'status' => 'success',
            'message' => __('Onboarding completed successfully'),
        ]);
    }
}