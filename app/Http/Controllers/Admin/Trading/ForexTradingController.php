<?php

namespace App\Http\Controllers\Admin\Trading;

use App\Http\Controllers\Controller;
use App\Models\ForexTradingOrder;
use App\Models\ForexTradingPosition;
use App\Models\TradingAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ForexTradingController extends Controller
{
    /**
     * List all forex trading accounts.
     */
    public function tradingAccounts(Request $request)
    {
        $page_title = __('Forex Trading Accounts');
        $mode = $request->get('mode', 'live');

        // unique currency for this module
        $currency = TradingAccount::where('account_type', 'forex')->value('currency') ?? 'USD';

        // Base query for stats
        $baseStatsQuery = TradingAccount::where('account_type', 'forex')->where('mode', $mode);
        if ($request->filled('user_id')) {
            $baseStatsQuery->where('user_id', $request->user_id);
        }

        // Stats
        $stats = [
            'total_balance' => (clone $baseStatsQuery)->sum('balance'),
            'total_borrowed' => (clone $baseStatsQuery)->sum('borrowed'), // Forex accounts use borrowed for negative equity/leverage gap if any
            'active_count' => (clone $baseStatsQuery)->where('account_status', 'active')->count(),
            'suspended_count' => (clone $baseStatsQuery)->where('account_status', 'suspended')->count(),
            'inactive_count' => (clone $baseStatsQuery)->where('account_status', 'inactive')->count(),
            'closed_count' => (clone $baseStatsQuery)->where('account_status', 'closed')->count(),
        ];

        // Visualizations Data
        $yearStart = now()->startOfYear();
        $periods = ['7d', '30d', '60d', '90d', '1y', 'ytd'];
        $statuses = ['active', 'inactive', 'suspended', 'closed'];

        $accountsRaw = (clone $baseStatsQuery)
            ->selectRaw('DATE(created_at) as day, account_status as status, COUNT(*) as count')
            ->where('created_at', '>=', $yearStart)
            ->groupBy('day', 'status')
            ->get();

        $accountMaps = array_fill_keys($statuses, []);
        foreach ($accountsRaw as $row) {
            if (array_key_exists($row->status, $accountMaps)) {
                $accountMaps[$row->status][$row->day] = (int) $row->count;
            }
        }

        $sliceDays = function (array $map, int $days): array {
            $result = [];
            for ($i = $days - 1; $i >= 0; $i--) {
                $key = now()->subDays($i)->format('Y-m-d');
                $result[$key] = $map[$key] ?? 0;
            }
            return $result;
        };

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

        $period = function (array $map, string $p) use ($sliceDays, $sliceYtd): array {
            $slice = match ($p) {
                '7d' => $sliceDays($map, 7),
                '30d' => $sliceDays($map, 30),
                '60d' => $sliceDays($map, 60),
                '90d' => $sliceDays($map, 90),
                '1y' => $sliceDays($map, 365),
                'ytd' => $sliceYtd($map),
                default => $sliceDays($map, 7),
            };
            return [
                'labels' => array_keys($slice),
                'data' => array_values($slice),
            ];
        };

        $graph_data = [];
        foreach ($periods as $p) {
            $graph_data[$p] = [];
            foreach ($statuses as $status) {
                $graph_data[$p][$status] = $period($accountMaps[$status], $p);
            }
        }

        $status_chart_data = (clone $baseStatsQuery)
            ->select('account_status as status', DB::raw('COUNT(*) as count'), DB::raw('SUM(balance) as total_balance'))
            ->groupBy('status')
            ->get();

        $status_chart_data = collect($statuses)->map(function ($status) use ($status_chart_data) {
            $found = $status_chart_data->where('status', $status)->first();
            return [
                'status' => ucfirst($status),
                'count' => $found ? $found->count : 0,
                'amount' => $found ? (float) $found->total_balance : 0
            ];
        });

        // Base Query for Table
        $query = TradingAccount::where('account_type', 'forex')->where('mode', $mode)->with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('status') && $request->status != 'all') {
            $query->where('account_status', $request->status);
        }

        if ($request->has('search') && $request->search) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->whereHas('user', function ($uq) use ($term) {
                    $uq->where('username', 'like', "%$term%")
                        ->orWhere('email', 'like', "%$term%")
                        ->orWhere('first_name', 'like', "%$term%")
                        ->orWhere('last_name', 'like', "%$term%");
                });
            });
        }

        // Export logic (same as Futures/Margin)
        if ($request->has('export')) {
            $exportType = $request->export;
            $exportAccounts = (clone $query)->latest()->get();
            $template = config('site.template');

            $requestedCols = $request->get('columns');
            if (is_string($requestedCols)) {
                $requestedCols = array_map('trim', explode(',', $requestedCols));
            } else {
                $requestedCols = ['username', 'email', 'balance', 'borrowed', 'currency', 'account_status', 'created_at'];
            }

            $columnMap = [
                'username' => 'User',
                'email' => 'Email',
                'balance' => 'Balance',
                'borrowed' => 'Borrowed',
                'currency' => 'Currency',
                'account_status' => 'Status',
                'created_at' => 'Date',
            ];

            $selectedCols = [];
            foreach ($requestedCols as $col) {
                if (array_key_exists($col, $columnMap)) {
                    $selectedCols[$col] = $columnMap[$col];
                }
            }

            if ($exportType == 'pdf') {
                $orientation = count($selectedCols) <= 8 ? 'portrait' : 'landscape';
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("templates.$template.blades.admin.pdf.forex_accounts", [
                    'accounts' => $exportAccounts,
                    'page_title' => $page_title,
                    'columns' => $selectedCols,
                    'orientation' => $orientation
                ]);
                return $pdf->download('forex-accounts-report-' . now()->format('Y-m-d-H-i-s') . '.pdf');
            }

            if ($exportType == 'csv') {
                $headers = [
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=forex-accounts-list-" . now()->format('Y-m-d-H-i-s') . ".csv",
                ];
                $callback = function () use ($exportAccounts, $selectedCols) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, array_values($selectedCols));
                    foreach ($exportAccounts as $account) {
                        $row = [];
                        foreach (array_keys($selectedCols) as $key) {
                            switch ($key) {
                                case 'username':
                                    $row[] = $account->user->username;
                                    break;
                                case 'email':
                                    $row[] = $account->user->email;
                                    break;
                                case 'account_status':
                                    $row[] = ucfirst($account->account_status);
                                    break;
                                case 'created_at':
                                    $row[] = $account->created_at->format('Y-m-d H:i:s');
                                    break;
                                default:
                                    $row[] = $account->$key ?? '';
                                    break;
                            }
                        }
                        fputcsv($file, $row);
                    }
                    fclose($file);
                };
                return response()->stream($callback, 200, $headers);
            }
        }

        $pagination = getSetting('pagination') ?? 10;
        $accounts = $query->latest()->paginate($pagination);
        $template = config('site.template');

        if ($request->ajax()) {
            return view('templates.' . $template . '.blades.admin.forex.accounts', compact(
                'page_title',
                'accounts',
                'stats',
                'graph_data',
                'status_chart_data',
                'currency',
                'mode'
            ));
        }

        return view('templates.' . $template . '.blades.admin.forex.accounts', compact(
            'page_title',
            'accounts',
            'stats',
            'graph_data',
            'status_chart_data',
            'currency',
            'mode'
        ));
    }

    public function creditDebit(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:trading_accounts,id',
            'amount' => 'required|numeric',
            'type' => 'required|in:credit,debit',
        ]);

        try {
            DB::beginTransaction();
            $account = TradingAccount::findOrFail($request->id);

            if ($request->type == 'credit') {
                $account->balance += $request->amount;
            } else {
                if ($account->balance < $request->amount) {
                    return response()->json(['status' => 'error', 'message' => __('Insufficient balance.')]);
                }
                $account->balance -= $request->amount;
            }

            $account->save();
            DB::commit();

            // Notify user
            $title = $request->type == 'credit' ? 'Forex account credit' : 'Forex account debit';
            $body = __("Your forex trading account has been :action with :amount :currency", [
                'action' => $request->type == 'credit' ? 'credited' : 'debited',
                'amount' => $request->amount,
                'currency' => $account->currency
            ]);
            recordNotificationMessage($account->user, $title, $body);

            return response()->json(['status' => 'success', 'message' => __('Balance updated successfully.')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:trading_accounts,id',
            'status' => 'required|in:active,inactive,suspended,closed',
        ]);

        $account = TradingAccount::findOrFail($request->id);
        $account->account_status = $request->status;
        $account->save();

        return response()->json(['status' => 'success', 'message' => __('Account status updated.')]);
    }

    public function deleteTradingAccount(Request $request)
    {
        $request->validate(['id' => 'required|exists:trading_accounts,id']);
        TradingAccount::destroy($request->id);
        return response()->json(['status' => 'success', 'message' => __('Account deleted.')]);
    }

    public function positions(Request $request)
    {
        $page_title = __('Forex Open Positions');
        $mode = $request->get('mode', 'live');
        $currency = TradingAccount::where('account_type', 'forex')->value('currency') ?? 'USD';

        $baseStatsQuery = ForexTradingPosition::where('mode', $mode);
        if ($request->filled('user_id')) {
            $baseStatsQuery->where('user_id', $request->user_id);
        }

        $stats = [
            'total_positions' => (clone $baseStatsQuery)->where('status', 'open')->count(),
            'buy_count' => (clone $baseStatsQuery)->where('status', 'open')->where('side', 'Buy')->count(),
            'sell_count' => (clone $baseStatsQuery)->where('status', 'open')->where('side', 'Sell')->count(),
            'total_margin' => (clone $baseStatsQuery)->where('status', 'open')->sum('margin'),
            'total_pnl' => (clone $baseStatsQuery)->where('status', 'open')->sum('unrealized_pnl'),
        ];

        // Charts configuration...
        $yearStart = now()->startOfYear();
        $periods = ['7d', '30d', '60d', '90d', '1y', 'ytd'];
        $sides = ['Buy', 'Sell'];

        $positionsRaw = (clone $baseStatsQuery)->selectRaw('DATE(created_at) as day, side, COUNT(*) as count')
            ->where('created_at', '>=', $yearStart)
            ->groupBy('day', 'side')
            ->get();

        $positionMaps = array_fill_keys($sides, []);
        foreach ($positionsRaw as $row) {
            if (array_key_exists($row->side, $positionMaps)) {
                $positionMaps[$row->side][$row->day] = (int) $row->count;
            }
        }

        $sliceDays = function (array $map, int $days): array {
            $result = [];
            for ($i = $days - 1; $i >= 0; $i--) {
                $key = now()->subDays($i)->format('Y-m-d');
                $result[$key] = $map[$key] ?? 0;
            }
            return $result;
        };

        $period = function (array $map, string $p) use ($sliceDays): array {
            $days = match ($p) { '7d' => 7, '30d' => 30, '60d' => 60, '90d' => 90, '1y' => 365, default => 7};
            $slice = $sliceDays($map, $days);
            return ['labels' => array_keys($slice), 'data' => array_values($slice)];
        };

        $graph_data = [];
        foreach ($periods as $p) {
            $graph_data[$p] = [];
            foreach ($sides as $side) {
                $graph_data[$p][$side] = $period($positionMaps[$side] ?? [], $p);
            }
        }

        $side_chart_data = collect($sides)->map(function ($side) use ($baseStatsQuery) {
            return [
                'status' => $side,
                'count' => (clone $baseStatsQuery)->where('status', 'open')->where('side', $side)->count(),
                'amount' => (clone $baseStatsQuery)->where('status', 'open')->where('side', $side)->sum('margin')
            ];
        });

        $query = ForexTradingPosition::with('user')->where('mode', $mode);
        if ($request->filled('user_id'))
            $query->where('user_id', $request->user_id);

        if ($request->has('search') && $request->search) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('symbol', 'like', "%$term%")
                    ->orWhereHas('user', function ($uq) use ($term) {
                        $uq->where('username', 'like', "%$term%");
                    });
            });
        }

        $pagination = getSetting('pagination') ?? 10;
        $positions = $query->latest()->paginate($pagination);
        $template = config('site.template');

        if ($request->ajax()) {
            return view('templates.' . $template . '.blades.admin.forex.positions', compact('page_title', 'positions', 'currency', 'stats', 'graph_data', 'side_chart_data', 'mode'));
        }

        return view('templates.' . $template . '.blades.admin.forex.positions', compact('page_title', 'positions', 'currency', 'stats', 'graph_data', 'side_chart_data', 'mode'));
    }

    public function closePosition(Request $request)
    {
        $request->validate(['id' => 'required|exists:forex_trading_positions,id']);

        try {
            DB::beginTransaction();
            $position = ForexTradingPosition::findOrFail($request->id);
            $user = $position->user;
            $trading_account = $user->tradingAccounts()->where('account_type', 'forex')->where('mode', $position->mode)->first();

            if (!$trading_account) {
                return response()->json(['status' => 'error', 'message' => __('Trading account not found.')]);
            }

            // Calculate final PnL (Using current record price or market price if available)
            $price = $position->current_price;
            $units = $position->volume * 100000;
            $pnl = ($position->side === 'Buy')
                ? ($price - $position->entry_price) * $units
                : ($position->entry_price - $price) * $units;

            $final_amount = $position->margin + $pnl;
            $trading_account->increment('balance', $final_amount);

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
            DB::commit();

            return response()->json(['status' => 'success', 'message' => __('Position closed by admin.')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function deletePosition(Request $request)
    {
        $request->validate(['id' => 'required|exists:forex_trading_positions,id']);
        ForexTradingPosition::destroy($request->id);
        return response()->json(['status' => 'success', 'message' => __('Position record deleted.')]);
    }

    public function orders(Request $request)
    {
        $page_title = __('Forex Order History');
        $mode = $request->get('mode', 'live');
        $currency = TradingAccount::where('account_type', 'forex')->value('currency') ?? 'USD';

        $baseStatsQuery = ForexTradingOrder::where('mode', $mode);
        if ($request->filled('user_id'))
            $baseStatsQuery->where('user_id', $request->user_id);

        $stats = [
            'total_orders' => (clone $baseStatsQuery)->count(),
            'pending_count' => (clone $baseStatsQuery)->where('status', 'pending')->count(),
            'filled_count' => (clone $baseStatsQuery)->where('status', 'filled')->count(),
            'cancelled_count' => (clone $baseStatsQuery)->where('status', 'canceled')->count(),
        ];

        // Status Chart
        $statuses = ['pending', 'filled', 'canceled'];
        $status_chart_data = collect($statuses)->map(function ($status) use ($baseStatsQuery) {
            return [
                'status' => ucfirst($status),
                'count' => (clone $baseStatsQuery)->where('status', $status)->count()
            ];
        });

        // Graph data for orders...
        $yearStart = now()->startOfYear();
        $periods = ['7d', '30d', '60d', '90d', '1y', 'ytd'];
        $ordersRaw = (clone $baseStatsQuery)->selectRaw('DATE(created_at) as day, status, COUNT(*) as count')
            ->where('created_at', '>=', $yearStart)
            ->groupBy('day', 'status')
            ->get();

        $orderMaps = array_fill_keys($statuses, []);
        foreach ($ordersRaw as $row) {
            if (array_key_exists($row->status, $orderMaps)) {
                $orderMaps[$row->status][$row->day] = (int) $row->count;
            }
        }

        $sliceDays = function (array $map, int $days): array { /* identical utility */
            $result = [];
            for ($i = $days - 1; $i >= 0; $i--) {
                $key = now()->subDays($i)->format('Y-m-d');
                $result[$key] = $map[$key] ?? 0;
            }
            return $result;
        };

        $graph_data = [];
        foreach ($periods as $p) {
            $graph_data[$p] = [];
            foreach ($statuses as $status) {
                $days = match ($p) { '7d' => 7, '30d' => 30, '60d' => 60, '90d' => 90, '1y' => 365, default => 7};
                $slice = $sliceDays($orderMaps[$status], $days);
                $graph_data[$p][$status] = ['labels' => array_keys($slice), 'data' => array_values($slice)];
            }
        }

        $query = ForexTradingOrder::with('user')->where('mode', $mode);
        if ($request->filled('user_id'))
            $query->where('user_id', $request->user_id);
        if ($request->has('status') && $request->status != 'all')
            $query->where('status', $request->status);

        $pagination = getSetting('pagination') ?? 10;
        $orders = $query->latest()->paginate($pagination);
        $template = config('site.template');

        if ($request->ajax()) {
            return view('templates.' . $template . '.blades.admin.forex.orders', compact('page_title', 'orders', 'currency', 'stats', 'graph_data', 'status_chart_data', 'mode'));
        }

        return view('templates.' . $template . '.blades.admin.forex.orders', compact('page_title', 'orders', 'currency', 'stats', 'graph_data', 'status_chart_data', 'mode'));
    }

    public function cancelOrder(Request $request)
    {
        $request->validate(['id' => 'required|exists:forex_trading_orders,id']);
        $order = ForexTradingOrder::findOrFail($request->id);
        if ($order->status !== 'pending')
            return response()->json(['status' => 'error', 'message' => __('Only pending orders can be cancelled.')]);

        $order->update(['status' => 'canceled']);
        return response()->json(['status' => 'success', 'message' => __('Order cancelled.')]);
    }

    public function deleteOrder(Request $request)
    {
        $request->validate(['id' => 'required|exists:forex_trading_orders,id']);
        ForexTradingOrder::destroy($request->id);
        return response()->json(['status' => 'success', 'message' => __('Order record deleted.')]);
    }
}
