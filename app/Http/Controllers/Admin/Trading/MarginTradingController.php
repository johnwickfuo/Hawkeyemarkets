<?php

namespace App\Http\Controllers\Admin\Trading;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\MarginTradingOrder;
use App\Models\MarginTradingPosition;
use App\Models\TradingAccount;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MarginTradingController extends Controller
{
    /**
     * List all margin trading accounts.
     */
    public function tradingAccounts(Request $request)
    {
        $page_title = __('Margin Trading Accounts');
        $currency = TradingAccount::where('account_type', 'margin')->value('currency') ?? 'USD';

        $baseStatsQuery = TradingAccount::where('account_type', 'margin');
        if ($request->filled('user_id')) {
            $baseStatsQuery->where('user_id', $request->user_id);
        }

        $stats = [
            'total_balance' => (clone $baseStatsQuery)->sum('balance'),
            'total_equity' => (clone $baseStatsQuery)->sum('equity'),
            'total_borrowed' => (clone $baseStatsQuery)->sum('borrowed'),
            'active_count' => (clone $baseStatsQuery)->where('account_status', 'active')->count(),
            'suspended_count' => (clone $baseStatsQuery)->where('account_status', 'suspended')->count(),
            'inactive_count' => (clone $baseStatsQuery)->where('account_status', 'inactive')->count(),
            'closed_count' => (clone $baseStatsQuery)->where('account_status', 'closed')->count(),
        ];

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

        $sliceDays = function (array $map, int $days) {
            $result = [];
            for ($i = $days - 1; $i >= 0; $i--) {
                $key = now()->subDays($i)->format('Y-m-d');
                $result[$key] = $map[$key] ?? 0;
            }
            return $result;
        };

        $sliceYtd = function (array $map) {
            $result = [];
            $start = now()->startOfYear();
            $days = (int) $start->diffInDays(now()) + 1;
            for ($i = 0; $i < $days; $i++) {
                $key = $start->copy()->addDays($i)->format('Y-m-d');
                $result[$key] = $map[$key] ?? 0;
            }
            return $result;
        };

        $period = function (array $map, string $p) use ($sliceDays, $sliceYtd) {
            $slice = match ($p) {
                '7d' => $sliceDays($map, 7),
                '30d' => $sliceDays($map, 30),
                '60d' => $sliceDays($map, 60),
                '90d' => $sliceDays($map, 90),
                '1y' => $sliceDays($map, 365),
                'ytd' => $sliceYtd($map),
                default => $sliceDays($map, 7),
            };
            return ['labels' => array_keys($slice), 'data' => array_values($slice)];
        };

        $graph_data = [];
        foreach ($periods as $p) {
            $graph_data[$p] = [];
            foreach ($statuses as $status) {
                $graph_data[$p][$status] = $period($accountMaps[$status], $p);
            }
        }

        $status_chart_data = collect($statuses)->map(function ($status) use ($baseStatsQuery) {
            $q = (clone $baseStatsQuery)->where('account_status', $status);
            return [
                'status' => ucfirst($status),
                'count' => $q->count(),
                'amount' => $q->sum('balance')
            ];
        });

        $query = TradingAccount::where('account_type', 'margin')->with('user');
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
                        ->orWhere('email', 'like', "%$term%");
                });
            });
        }

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
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("templates.$template.blades.admin.pdf.margin", [
                    'accounts' => $exportAccounts,
                    'page_title' => $page_title,
                    'columns' => $selectedCols,
                    'orientation' => $orientation
                ]);
                return $pdf->download('margin-accounts-report-' . now()->format('Y-m-d') . '.pdf');
            }

            if ($exportType == 'csv') {
                $headers = ["Content-type" => "text/csv", "Content-Disposition" => "attachment; filename=margin-accounts.csv"];
                $callback = function () use ($exportAccounts, $selectedCols) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, array_values($selectedCols));
                    foreach ($exportAccounts as $acc) {
                        $row = [];
                        foreach (array_keys($selectedCols) as $key) {
                            if ($key == 'username') $row[] = $acc->user->username;
                            elseif ($key == 'email') $row[] = $acc->user->email;
                            elseif ($key == 'created_at') $row[] = $acc->created_at->format('Y-m-d H:i:s');
                            else $row[] = $acc->$key ?? '';
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
            return view('templates.' . $template . '.blades.admin.margin.accounts', compact('page_title', 'accounts', 'stats', 'graph_data', 'status_chart_data', 'currency'));
        }

        return view('templates.' . $template . '.blades.admin.margin.accounts', compact('page_title', 'accounts', 'stats', 'graph_data', 'status_chart_data', 'currency'));
    }

    public function creditDebit(Request $request)
    {
        $request->validate(['id' => 'required|exists:trading_accounts,id', 'amount' => 'required|numeric', 'type' => 'required|in:credit,debit']);
        try {
            DB::beginTransaction();
            $account = TradingAccount::findOrFail($request->id);
            if ($request->type == 'credit') {
                $account->balance += $request->amount;
            } else {
                if ($account->balance < $request->amount) return response()->json(['status' => 'error', 'message' => __('Insufficient balance.')]);
                $account->balance -= $request->amount;
            }
            $account->save();
            DB::commit();
            return response()->json(['status' => 'success', 'message' => __('Balance updated.')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function updateStatus(Request $request)
    {
        $request->validate(['id' => 'required|exists:trading_accounts,id', 'status' => 'required|in:active,inactive,suspended,closed']);
        $account = TradingAccount::findOrFail($request->id);
        $account->account_status = $request->status;
        $account->save();
        return response()->json(['status' => 'success', 'message' => __('Status updated.')]);
    }

    public function deleteTradingAccount(Request $request)
    {
        $request->validate(['id' => 'required|exists:trading_accounts,id']);
        TradingAccount::destroy($request->id);
        return response()->json(['status' => 'success', 'message' => __('Account deleted.')]);
    }

    public function positions(Request $request)
    {
        $page_title = __('Margin Positions');
        $currency = TradingAccount::where('account_type', 'margin')->value('currency') ?? 'USD';

        $baseStatsQuery = MarginTradingPosition::query();
        if ($request->filled('user_id')) {
            $baseStatsQuery->where('user_id', $request->user_id);
        }

        $stats = [
            'total_positions' => (clone $baseStatsQuery)->count(),
            'long_count' => (clone $baseStatsQuery)->where('side', 'buy')->count(),
            'short_count' => (clone $baseStatsQuery)->where('side', 'sell')->count(),
            'total_margin' => (clone $baseStatsQuery)->sum('margin'),
            'total_pnl' => (clone $baseStatsQuery)->sum('unrealized_pnl'),
        ];

        $yearStart = now()->startOfYear();
        $periods = ['7d', '30d', '60d', '90d', '1y', 'ytd'];
        $sides = ['buy', 'sell'];

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

        $sliceDays = function (array $map, int $days) {
            $result = [];
            for ($i = $days - 1; $i >= 0; $i--) {
                $key = now()->subDays($i)->format('Y-m-d');
                $result[$key] = $map[$key] ?? 0;
            }
            return $result;
        };

        $period = function (array $map, string $p) use ($sliceDays) {
            $days = match ($p) { '7d' => 7, '30d' => 30, '60d' => 60, '90d' => 90, '1y' => 365, 'ytd' => (int) now()->startOfYear()->diffInDays(now()) + 1, default => 7 };
            $slice = $sliceDays($map, $days);
            return ['labels' => array_keys($slice), 'data' => array_values($slice)];
        };

        $graph_data = [];
        foreach ($periods as $p) {
            $graph_data[$p] = [];
            foreach ($sides as $side) {
                $graph_data[$p][$side] = $period($positionMaps[$side], $p);
            }
        }

        $side_chart_data = collect($sides)->map(function ($side) use ($baseStatsQuery) {
            $q = (clone $baseStatsQuery)->where('side', $side);
            return ['status' => ucfirst($side), 'count' => $q->count(), 'amount' => $q->sum('margin')];
        });

        $query = MarginTradingPosition::with('user');
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('search') && $request->search) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('ticker', 'like', "%$term%")->orWhereHas('user', function ($uq) use ($term) {
                    $uq->where('username', 'like', "%$term%")->orWhere('email', 'like', "%$term%");
                });
            });
        }

        if ($request->has('export')) {
            $exportType = $request->export;
            $exportPositions = (clone $query)->latest()->get();
            $template = config('site.template');
            $requestedCols = $request->get('columns') ?? ['username', 'ticker', 'side', 'size', 'entry_price', 'current_price', 'margin', 'leverage', 'unrealized_pnl', 'created_at'];
            if (is_string($requestedCols)) $requestedCols = array_map('trim', explode(',', $requestedCols));

            $columnMap = ['username' => 'User', 'ticker' => 'Ticker', 'side' => 'Side', 'size' => 'Size', 'entry_price' => 'Entry', 'current_price' => 'Current', 'margin' => 'Margin', 'leverage' => 'Leverage', 'unrealized_pnl' => 'PnL', 'created_at' => 'Date'];
            $selectedCols = [];
            foreach ($requestedCols as $col) if (array_key_exists($col, $columnMap)) $selectedCols[$col] = $columnMap[$col];

            if ($exportType == 'pdf') {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("templates.$template.blades.admin.pdf.margin_positions", ['positions' => $exportPositions, 'page_title' => $page_title, 'columns' => $selectedCols, 'orientation' => count($selectedCols) > 8 ? 'landscape' : 'portrait']);
                return $pdf->download('margin-positions.pdf');
            }
        }

        $pagination = getSetting('pagination') ?? 10;
        $positions = $query->latest()->paginate($pagination);
        $template = config('site.template');

        if ($request->ajax()) return view('templates.' . $template . '.blades.admin.margin.positions', compact('page_title', 'positions', 'stats', 'graph_data', 'side_chart_data', 'currency'));
        return view('templates.' . $template . '.blades.admin.margin.positions', compact('page_title', 'positions', 'stats', 'graph_data', 'side_chart_data', 'currency'));
    }

    public function closePosition(Request $request)
    {
        $request->validate(['id' => 'required|exists:margin_trading_positions,id']);
        try {
            return DB::transaction(function () use ($request) {
                $position = MarginTradingPosition::findOrFail($request->id);
                $user = $position->user;
                $trading_account = $user->tradingAccounts()->where('account_type', 'margin')->first();
                $price = $position->current_price;

                $pnl = ($position->side === 'buy') ? ($price - (float) $position->entry_price) * (float) $position->size : ((float) $position->entry_price - $price) * (float) $position->size;
                $final_amount = (float) $position->margin + $pnl;

                if ($trading_account) {
                    if ($trading_account->borrowed > 0) {
                        $to_repay = min((float) $trading_account->borrowed, $final_amount);
                        $trading_account->decrement('borrowed', (float) $to_repay);
                        $final_amount -= $to_repay;
                    }
                    if ($final_amount > 0) $trading_account->increment('balance', $final_amount);
                }

                MarginTradingOrder::create([
                    'user_id' => $user->id,
                    'type' => 'market',
                    'ticker' => $position->ticker,
                    'side' => $position->side === 'buy' ? 'sell' : 'buy',
                    'size' => $position->size,
                    'price' => $price,
                    'status' => 'filled',
                    'leverage' => $position->leverage,
                    'timestamp' => (string) now()->valueOf(),
                ]);

                $position->delete();
                return response()->json(['status' => 'success', 'message' => __('Position closed.')]);
            });
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function deletePosition(Request $request)
    {
        $request->validate(['id' => 'required|exists:margin_trading_positions,id']);
        MarginTradingPosition::destroy($request->id);
        return response()->json(['status' => 'success', 'message' => __('Record deleted.')]);
    }

    public function orders(Request $request)
    {
        $page_title = __('Margin Order History');
        $currency = TradingAccount::where('account_type', 'margin')->value('currency') ?? 'USD';

        $baseStatsQuery = MarginTradingOrder::query();
        if ($request->filled('user_id')) {
            $baseStatsQuery->where('user_id', $request->user_id);
        }

        $stats = [
            'total_orders' => (clone $baseStatsQuery)->count(),
            'pending_count' => (clone $baseStatsQuery)->where('status', 'pending')->count(),
            'filled_count' => (clone $baseStatsQuery)->where('status', 'filled')->count(),
            'cancelled_count' => (clone $baseStatsQuery)->where('status', 'cancelled')->count(),
            'total_notional' => (clone $baseStatsQuery)->selectRaw('SUM(price * size) as total')->value('total') ?? 0,
        ];

        $yearStart = now()->startOfYear();
        $statuses = ['pending', 'filled', 'cancelled'];
        $ordersRaw = (clone $baseStatsQuery)->selectRaw('DATE(created_at) as day, status, COUNT(*) as count')->where('created_at', '>=', $yearStart)->groupBy('day', 'status')->get();
        $orderMaps = array_fill_keys($statuses, []);
        foreach ($ordersRaw as $row) if (array_key_exists($row->status, $orderMaps)) $orderMaps[$row->status][$row->day] = (int) $row->count;

        $periods = ['7d', '30d', '60d', '90d', '1y', 'ytd'];
        $graph_data = [];
        foreach ($periods as $p) {
            $days = match ($p) { '7d' => 7, '30d' => 30, 'ytd' => (int) now()->startOfYear()->diffInDays(now()) + 1, default => 7 };
            $graph_data[$p] = [];
            foreach ($statuses as $s) {
                $result = [];
                for ($i = $days - 1; $i >= 0; $i--) {
                    $key = now()->subDays($i)->format('Y-m-d');
                    $result[$key] = $orderMaps[$s][$key] ?? 0;
                }
                $graph_data[$p][$s] = ['labels' => array_keys($result), 'data' => array_values($result)];
            }
        }

        $status_chart_data = collect($statuses)->map(function ($s) use ($baseStatsQuery) {
            $q = (clone $baseStatsQuery)->where('status', $s);
            return ['status' => ucfirst($s), 'count' => $q->count(), 'amount' => $q->selectRaw('SUM(price * size) as total')->value('total') ?? 0];
        });

        $query = MarginTradingOrder::with('user');
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('status') && $request->status != 'all') $query->where('status', $request->status);
        if ($request->has('search') && $request->search) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('ticker', 'like', "%$term%")->orWhereHas('user', function ($uq) use ($term) {
                    $uq->where('username', 'like', "%$term%")->orWhere('email', 'like', "%$term%");
                });
            });
        }

        if ($request->has('export')) {
            $template = config('site.template');
            $requestedCols = $request->get('columns') ?? ['username', 'ticker', 'type', 'side', 'size', 'price', 'status', 'created_at'];
            if (is_string($requestedCols)) $requestedCols = array_map('trim', explode(',', $requestedCols));
            $columnMap = ['username' => 'User', 'ticker' => 'Ticker', 'type' => 'Type', 'side' => 'Side', 'size' => 'Size', 'price' => 'Price', 'status' => 'Status', 'created_at' => 'Date'];
            $selectedCols = [];
            foreach ($requestedCols as $col) if (array_key_exists($col, $columnMap)) $selectedCols[$col] = $columnMap[$col];

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("templates.$template.blades.admin.pdf.margin_orders", ['orders' => (clone $query)->latest()->get(), 'page_title' => $page_title, 'columns' => $selectedCols, 'orientation' => count($selectedCols) > 8 ? 'landscape' : 'portrait']);
            return $pdf->download('margin-orders.pdf');
        }

        $pagination = getSetting('pagination') ?? 10;
        $orders = $query->latest()->paginate($pagination);
        $template = config('site.template');

        if ($request->ajax()) return view('templates.' . $template . '.blades.admin.margin.orders', compact('page_title', 'orders', 'stats', 'graph_data', 'status_chart_data', 'currency'));
        return view('templates.' . $template . '.blades.admin.margin.orders', compact('page_title', 'orders', 'stats', 'graph_data', 'status_chart_data', 'currency'));
    }

    public function cancelOrder(Request $request)
    {
        $request->validate(['id' => 'required|exists:margin_trading_orders,id']);
        try {
            return DB::transaction(function () use ($request) {
                $order = MarginTradingOrder::findOrFail($request->id);
                if ($order->status !== 'pending') return response()->json(['status' => 'error', 'message' => __('Only pending orders can be canceled')]);

                if ($order->locked_margin > 0) {
                    $trading_account = $order->user->tradingAccounts()->where('account_type', 'margin')->first();
                    if ($trading_account) {
                        $refund_amount = (float) $order->locked_margin;
                        if ($trading_account->borrowed > 0) {
                            $to_repay = min((float) $trading_account->borrowed, $refund_amount);
                            $trading_account->decrement('borrowed', (float) $to_repay);
                            $refund_amount -= $to_repay;
                        }
                        if ($refund_amount > 0) $trading_account->increment('balance', (float) $refund_amount);
                    }
                }

                $order->update(['status' => 'cancelled']);
                return response()->json(['status' => 'success', 'message' => __('Order cancelled.')]);
            });
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function deleteOrder(Request $request)
    {
        $request->validate(['id' => 'required|exists:margin_trading_orders,id']);
        MarginTradingOrder::destroy($request->id);
        return response()->json(['status' => 'success', 'message' => __('Record deleted.')]);
    }
}
