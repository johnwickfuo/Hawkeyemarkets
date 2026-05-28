<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BondHolding;
use App\Models\BondHoldingHistory;
use App\Models\EtfHolding;
use App\Models\EtfHoldingHistory;
use App\Models\ForexTradingOrder;
use App\Models\ForexTradingPosition;
use App\Models\FuturesTradingOrders;
use App\Models\FuturesTradingPositions;
use App\Models\Investment;
use App\Models\InvestmentEarning;
use App\Models\InvestmentPlan;
use App\Models\MarginTradingOrder;
use App\Models\MarginTradingPosition;
use App\Models\StockHolding;
use App\Models\Deposit;
use App\Models\StockHoldingHistory;
use App\Models\TradingAccount;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $page_title = 'Dashboard';
        $top_cards = [];
        $users_metrics = [
            'total' => User::count(),
            'banned' => User::where('status', 'banned')->count(),
            'active' => User::where('status', 'active')->count(),
            'email_verified' => User::whereNotNull('email_verified_at')->count(),
            'pending_email_verification' => User::whereNull('email_verified_at')->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'kyc_verified' => User::whereHas('kyc', fn($q) => $q->where('status', 'approved'))->count(),
            'pending_kyc' => User::whereDoesntHave('kyc')->orWhereHas('kyc', fn($q) => $q->where('status', 'pending'))->count(),
        ];

        $top_cards['users'] = $users_metrics;
        $system_equity_metrics = [
            'total' => 0,
            'users_balance' => User::sum('balance'),
            'futures_balance' => moduleEnabled('futures_module') ? TradingAccount::where('account_type', 'futures')->sum('balance') : 0,
            'margin_balance' => moduleEnabled('margin_module') ? TradingAccount::where('account_type', 'margin')->sum('balance') : 0,
            'forex_balance' => moduleEnabled('forex_module') ? TradingAccount::where('account_type', 'forex')->where('mode', 'live')->sum('balance') : 0,
            'stocks_balance' => moduleEnabled('stock_module') ? StockHolding::sum(DB::raw('average_price * shares')) : 0,
            'etfs_balance' => moduleEnabled('etf_module') ? EtfHolding::sum(DB::raw('average_price * shares')) : 0,
            'bonds_balance' => moduleEnabled('bonds_module') ? BondHolding::active()->sum('amount') : 0,
        ];

        // convert all to system currency,
        $rate = rateConverter(1, 'USD', getSetting('currency'), 'gen')['converted_amount'];
        $futures_conversion = $system_equity_metrics['futures_balance'] * $rate;
        $margin_conversion = $system_equity_metrics['margin_balance'] * $rate;
        $forex_conversion = $system_equity_metrics['forex_balance'] * $rate;
        $stocks_conversion = $system_equity_metrics['stocks_balance'] * $rate;
        $etfs_conversion = $system_equity_metrics['etfs_balance'] * $rate;
        $bonds_conversion = $system_equity_metrics['bonds_balance'];

        $system_equity_metrics['futures_balance'] = $futures_conversion;
        $system_equity_metrics['margin_balance'] = $margin_conversion;
        $system_equity_metrics['forex_balance'] = $forex_conversion;
        $system_equity_metrics['stocks_balance'] = $stocks_conversion;
        $system_equity_metrics['etfs_balance'] = $etfs_conversion;
        $system_equity_metrics['bonds_balance'] = $bonds_conversion;

        $system_equity_metrics['total'] = $system_equity_metrics['users_balance'] + $system_equity_metrics['futures_balance'] + $system_equity_metrics['margin_balance'] + $system_equity_metrics['forex_balance'] + $system_equity_metrics['stocks_balance'] + $system_equity_metrics['etfs_balance'] + $system_equity_metrics['bonds_balance'];

        $top_cards['system_equity'] = $system_equity_metrics;

        $investment_metrics = [
            'plans' => [
                'total' => InvestmentPlan::count(),
                'active' => InvestmentPlan::active()->count(),
                'inactive' => InvestmentPlan::where('is_enabled', false)->count(),
            ],
            'total' => [
                'amount' => Investment::sum('capital_invested'),
                'count' => Investment::count(),
            ],
            'active' => [
                'amount' => Investment::where('status', 'active')->sum('capital_invested'),
                'count' => Investment::where('status', 'active')->count(),
            ],
            'completed' => [
                'amount' => Investment::where('status', 'completed')->sum('capital_invested'),
                'count' => Investment::where('status', 'completed')->count(),
            ],
            'roi' => [
                'total' => Investment::sum('roi_earned'),
                'paid' => Investment::where('status', 'completed')->sum('roi_earned'),
                'pending' => Investment::where('status', 'active')->sum('roi_earned'),
            ],
        ];

        $top_cards['investment'] = $investment_metrics;

        $trading_card = [];

        $futures = [
            'orders' => [
                'filled' => FuturesTradingOrders::where('status', 'filled')->count(),
                'pending' => FuturesTradingOrders::where('status', 'pending')->count(),
                'cancelled' => FuturesTradingOrders::where('status', 'cancelled')->count(),
            ],
            'positions' => FuturesTradingPositions::count(),
            'accounts' => [
                'count' => TradingAccount::where('account_type', 'futures')->count(),
                'equity' => $futures_conversion,
            ]
        ];

        $margin = [
            'orders' => [
                'filled' => MarginTradingOrder::where('status', 'filled')->count(),
                'pending' => MarginTradingOrder::where('status', 'pending')->count(),
                'cancelled' => MarginTradingOrder::where('status', 'cancelled')->count(),
            ],
            'positions' => MarginTradingPosition::count(),
            'accounts' => [
                'count' => TradingAccount::where('account_type', 'margin')->count(),
                'equity' => $margin_conversion,
            ]
        ];

        $forex_demo_equity = TradingAccount::where('account_type', 'forex')->where('mode', 'demo')->sum('balance');
        $forex_demo_equity_conversion = $forex_demo_equity * $rate;


        $forex = [
            'demo' => [
                'orders' => [
                    'filled' => ForexTradingOrder::where('mode', 'demo')->where('status', 'filled')->count(),
                    'pending' => ForexTradingOrder::where('mode', 'demo')->where('status', 'pending')->count(),
                    'cancelled' => ForexTradingOrder::where('mode', 'demo')->where('status', 'cancelled')->count(),
                ],
                'positions' => ForexTradingPosition::where('mode', 'demo')->count(),
                'accounts' => [
                    'count' => TradingAccount::where('account_type', 'forex')->where('mode', 'demo')->count(),
                    'equity' => $forex_demo_equity_conversion,
                ]
            ],
            'live' => [
                'orders' => [
                    'filled' => ForexTradingOrder::where('mode', 'live')->where('status', 'filled')->count(),
                    'pending' => ForexTradingOrder::where('mode', 'live')->where('status', 'pending')->count(),
                    'cancelled' => ForexTradingOrder::where('mode', 'live')->where('status', 'cancelled')->count(),
                ],
                'positions' => ForexTradingPosition::where('mode', 'live')->count(),
                'accounts' => [
                    'count' => TradingAccount::where('account_type', 'forex')->where('mode', 'live')->count(),
                    'equity' => $forex_conversion,
                ]
            ]
        ];

        $trading_card['futures'] = $futures;
        $trading_card['margin'] = $margin;
        $trading_card['forex'] = $forex;

        $chart_data = [
            'account_balance' => $system_equity_metrics['users_balance'],
            'futures' => $futures_conversion,
            'margin' => $margin_conversion,
            'forex' => $forex_conversion,
            'stocks' => $stocks_conversion,
            'etfs' => $etfs_conversion,
            'bonds' => $bonds_conversion,
        ];

        // ── Graph data ──────────────────────────────────────────────────────────
        // Pre-compute all four periods (7d, 30d, 1y, ytd) from a single 1-year
        // window per dataset so the JS can switch filters without any AJAX.
        // Each period entry: [ 'labels' => [...], 'datasets' => [ type => [...] ] ]

        $yearStart = now()->startOfYear();
        $oneYearAgo = now()->subYear()->startOfDay();
        $graphWindowStart = $oneYearAgo->lt($yearStart) ? $oneYearAgo : $yearStart;

        // Helper: slice a keyed ['YYYY-MM-DD' => value] map to the last N days
        $sliceDays = function (array $map, int $days): array {
            $result = [];
            for ($i = $days - 1; $i >= 0; $i--) {
                $key = now()->subDays($i)->format('Y-m-d');
                $result[$key] = $map[$key] ?? 0;
            }
            return $result;
        };

        // Helper: slice a keyed map from Jan 1 of this year to today
        $sliceYtd = function (array $map): array {
            $result = [];
            $start = now()->startOfYear();
            $days = (int) $start->diffInDays(now()) + 1;
            for ($i = 0; $i < $days; $i++) {
                $key = $start->copy()->addDays($i)->format('Y-m-d');
                $result[$key] = $map[$key] ?? 0;
            }
            return $result;
        };

        // Helper: build formatted period array for a given slice
        $period = function (array $map, string $p) use ($sliceDays, $sliceYtd): array {
            $slice = match ($p) {
                '7d' => $sliceDays($map, 7),
                '30d' => $sliceDays($map, 30),
                '1y' => $sliceDays($map, 365),
                'ytd' => $sliceYtd($map),
                default => $sliceDays($map, 7),
            };
            return [
                'labels' => array_keys($slice),
                'data' => array_values($slice),
            ];
        };

        $periods = ['7d', '30d', '1y', 'ytd'];

        // ── 1. Transaction history (credit / debit) ──────────────────────────
        $txnRaw = Transaction::selectRaw('DATE(created_at) as day, type, SUM(amount) as total')
            ->where('created_at', '>=', $graphWindowStart)
            ->groupBy('day', 'type')
            ->orderBy('day')
            ->get();

        $txnMaps = ['credit' => [], 'debit' => []];
        foreach ($txnRaw as $row) {
            $txnMaps[$row->type][$row->day] = (float) $row->total;
        }

        $graph_data['transactions'] = [];
        foreach ($periods as $p) {
            $graph_data['transactions'][$p] = [
                'credit' => $period($txnMaps['credit'], $p),
                'debit' => $period($txnMaps['debit'], $p),
            ];
        }

        // ── 2. Deposits ──────────────────────────────────────────────────────
        $depositStatuses = ['pending', 'completed', 'failed', 'partial_payment'];
        $depositRaw = Deposit::selectRaw('DATE(created_at) as day, status, SUM(total_amount) as total, COUNT(*) as count')
            ->where('created_at', '>=', $graphWindowStart)
            ->groupBy('day', 'status')
            ->orderBy('day')
            ->get();

        $depositMaps = array_fill_keys($depositStatuses, []);
        foreach ($depositRaw as $row) {
            if (array_key_exists($row->status, $depositMaps)) {
                $depositMaps[$row->status][$row->day] = (float) $row->total;
            }
        }

        $graph_data['deposits'] = [];
        foreach ($periods as $p) {
            $graph_data['deposits'][$p] = [];
            foreach ($depositStatuses as $status) {
                $graph_data['deposits'][$p][$status] = $period($depositMaps[$status], $p);
            }
        }

        // ── 3. Withdrawals ───────────────────────────────────────────────────
        $withdrawalStatuses = ['pending', 'completed', 'failed', 'partial_payment'];
        $withdrawalRaw = Withdrawal::selectRaw('DATE(created_at) as day, status, SUM(amount_payable) as total, COUNT(*) as count')
            ->where('created_at', '>=', $graphWindowStart)
            ->groupBy('day', 'status')
            ->orderBy('day')
            ->get();

        $withdrawalMaps = array_fill_keys($withdrawalStatuses, []);
        foreach ($withdrawalRaw as $row) {
            if (array_key_exists($row->status, $withdrawalMaps)) {
                $withdrawalMaps[$row->status][$row->day] = (float) $row->total;
            }
        }

        $graph_data['withdrawals'] = [];
        foreach ($periods as $p) {
            $graph_data['withdrawals'][$p] = [];
            foreach ($withdrawalStatuses as $status) {
                $graph_data['withdrawals'][$p][$status] = $period($withdrawalMaps[$status], $p);
            }
        }

        // ── 4. Investments ───────────────────────────────────────────────────
        $investmentStatuses = ['active', 'completed', 'cancelled'];
        $investmentRaw = Investment::selectRaw('DATE(created_at) as day, status, SUM(capital_invested) as total, COUNT(*) as count')
            ->where('created_at', '>=', $graphWindowStart)
            ->groupBy('day', 'status')
            ->orderBy('day')
            ->get();

        $investmentMaps = array_fill_keys($investmentStatuses, []);
        foreach ($investmentRaw as $row) {
            if (array_key_exists($row->status, $investmentMaps)) {
                $investmentMaps[$row->status][$row->day] = (float) $row->total;
            }
        }

        $graph_data['investments'] = [];
        foreach ($periods as $p) {
            $graph_data['investments'][$p] = [];
            foreach ($investmentStatuses as $status) {
                $graph_data['investments'][$p][$status] = $period($investmentMaps[$status], $p);
            }
        }
        // ────────────────────────────────────────────────────────────────────

        $capital_instruments_metrics = [];
        $stock_metrics = [
            'bought' => [
                'amonut' => StockHoldingHistory::where('transaction_type', 'buy')->sum('amount'),
                'fees' => StockHoldingHistory::where('transaction_type', 'buy')->sum('fee_amount'),
            ],
            'sold' => [
                'amonut' => StockHoldingHistory::where('transaction_type', 'sell')->sum('amount'),
                'fees' => StockHoldingHistory::where('transaction_type', 'sell')->sum('fee_amount'),
            ],
            'pnl' => [
                'amount' => StockHolding::sum('pnl') * $rate,
                'percent' => StockHolding::sum('pnl_percent'),
            ],
            'holdings' => StockHolding::count(),
            'balance' => $system_equity_metrics['stocks_balance']
        ];
        $etf_metrics = [
            'bought' => [
                'amonut' => EtfHoldingHistory::where('transaction_type', 'buy')->sum('amount'),
                'fees' => EtfHoldingHistory::where('transaction_type', 'buy')->sum('fee_amount'),
            ],
            'sold' => [
                'amonut' => EtfHoldingHistory::where('transaction_type', 'sell')->sum('amount'),
                'fees' => EtfHoldingHistory::where('transaction_type', 'sell')->sum('fee_amount'),
            ],
            'pnl' => [
                'amount' => ETFHolding::sum('pnl') * $rate,
                'percent' => ETFHolding::sum('pnl_percent'),
            ],
            'holdings' => ETFHolding::count(),
            'balance' => $system_equity_metrics['etfs_balance']
        ];
        $bond_metrics = [
            'bought' => [
                'amount' => BondHoldingHistory::where('transaction_type', 'buy')->sum('amount'),
                'fees' => BondHoldingHistory::where('transaction_type', 'buy')->sum('fee_amount'),
            ],
            'sold' => [
                'amount' => BondHoldingHistory::where('transaction_type', 'payout')->sum('amount'),
                'fees' => BondHoldingHistory::where('transaction_type', 'payout')->sum('fee_amount'),
            ],
            'pnl' => [
                'amount' => BondHoldingHistory::where('transaction_type', 'payout')->sum('interest_amount'),
                'percent' => 0, // Bonds fixed ROI, percentage varies per bond.
            ],
            'holdings' => BondHolding::count(),
            'balance' => $system_equity_metrics['bonds_balance']
        ];

        $capital_instruments_metrics = [
            'stocks' => $stock_metrics,
            'etf' => $etf_metrics,
            'bonds' => $bond_metrics,
        ];

        // ── Recent activity data (10 rows each) ──────────────────────────────
        $recent_data = [
            'deposits' => Deposit::with('user')
                ->latest()->limit(10)->get(),
            'withdrawals' => Withdrawal::with('user')
                ->latest()->limit(10)->get(),
            'investments' => Investment::with(['user', 'plan'])
                ->latest()->limit(10)->get(),
            'investment_earnings' => InvestmentEarning::with(['user', 'investment'])
                ->latest()->limit(10)->get(),
            'transactions' => Transaction::with('user')
                ->latest()->limit(10)->get(),
            'futures_orders' => FuturesTradingOrders::with('user')
                ->latest()->limit(10)->get(),
            'forex_orders_live' => ForexTradingOrder::with('user')
                ->where('mode', 'live')->latest()->limit(10)->get(),
            'forex_orders_demo' => ForexTradingOrder::with('user')
                ->where('mode', 'demo')->latest()->limit(10)->get(),
            'margin_orders' => MarginTradingOrder::with('user')
                ->latest()->limit(10)->get(),
            'stock_history' => StockHoldingHistory::with('user')
                ->latest()->limit(10)->get(),
            'etf_history' => EtfHoldingHistory::with('user')
                ->latest()->limit(10)->get(),
            'bond_history' => BondHoldingHistory::with('user')
                ->latest()->limit(10)->get(),
        ];
        // ─────────────────────────────────────────────────────────────────────

        $template = config('site.template');

        return view('templates.' . $template . '.blades.admin.dashboard', compact(
            'page_title',
            'top_cards',
            'trading_card',
            'chart_data',
            'graph_data',
            'capital_instruments_metrics',
            'recent_data',
        ));
    }
}
