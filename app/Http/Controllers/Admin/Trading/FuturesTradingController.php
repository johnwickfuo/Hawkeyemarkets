<?php

namespace App\Http\Controllers\Admin\Trading;

use App\Http\Controllers\Controller;
use App\Models\FuturesTradingOrders;
use App\Models\FuturesTradingPositions;
use App\Models\TradingAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FuturesTradingController extends Controller
{

    /**
     * List all futures trading accounts.
     */
    public function tradingAccounts(Request $request)
    {
        $page_title = __('Futures Trading Accounts');

        // Get unique currency for this module
        $currency = TradingAccount::where('account_type', 'futures')->value('currency') ?? 'USD';

        // Base query for stats and visualizations
        $baseStatsQuery = TradingAccount::where('account_type', 'futures');
        if ($request->filled('user_id')) {
            $baseStatsQuery->where('user_id', $request->user_id);
        }

        // Stats
        $stats = [
            'total_balance' => (clone $baseStatsQuery)->sum('balance'),
            'total_equity' => (clone $baseStatsQuery)->sum('equity'),
            'active_count' => (clone $baseStatsQuery)->where('account_status', 'active')->count(),
            'suspended_count' => (clone $baseStatsQuery)->where('account_status', 'suspended')->count(),
            'inactive_count' => (clone $baseStatsQuery)->where('account_status', 'inactive')->count(),
            'closed_count' => (clone $baseStatsQuery)->where('account_status', 'closed')->count(),
        ];

        // ── Visualizations Data ─────────────────────────────────────────────
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

        // Base Query
        $query = TradingAccount::where('account_type', 'futures')->with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filters
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

        // Export Handling
        if ($request->has('export')) {
            $exportType = $request->export;
            $exportAccounts = (clone $query)->latest()->get();
            $template = config('site.template');

            // Dynamic Column Selection
            $requestedCols = $request->get('columns');
            if (is_string($requestedCols)) {
                $requestedCols = array_map('trim', explode(',', $requestedCols));
            } else {
                $requestedCols = (array) ($requestedCols ?? ['username', 'email', 'balance', 'equity', 'currency', 'account_status', 'created_at']);
            }

            // Header whitelist and mapping
            $columnMap = [
                'username' => 'User',
                'email' => 'Email',
                'balance' => 'Balance',
                'equity' => 'Equity',
                'currency' => 'Currency',
                'account_status' => 'Status',
                'created_at' => 'Date',
            ];

            // Filter columns based on whitelist
            $selectedCols = [];
            foreach ($requestedCols as $col) {
                $col = trim($col);
                if (array_key_exists($col, $columnMap)) {
                    $selectedCols[$col] = $columnMap[$col];
                }
            }

            if ($exportType == 'pdf') {
                $orientation = count($selectedCols) <= 8 ? 'portrait' : 'landscape';
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("templates.$template.blades.admin.pdf.futures", [
                    'accounts' => $exportAccounts,
                    'page_title' => $page_title,
                    'columns' => $selectedCols,
                    'orientation' => $orientation
                ]);
                return $pdf->download('futures-accounts-report-' . now()->format('Y-m-d-H-i-s') . '.pdf');
            }

            if ($exportType == 'sql') {
                $headers = [
                    'Content-Type' => 'application/sql',
                    'Content-Disposition' => 'attachment; filename="futures-accounts-dump-' . now()->format('Y-m-d-H-i-s') . '.sql"',
                ];

                $callback = function () use ($exportAccounts) {
                    $file = fopen('php://output', 'w');
                    fwrite($file, "-- Futures Trading Accounts Table Dump\n");
                    fwrite($file, "-- Generated at: " . now() . "\n\n");

                    try {
                        $createTable = DB::select("SHOW CREATE TABLE trading_accounts")[0]->{'Create Table'};
                        fwrite($file, "DROP TABLE IF EXISTS trading_accounts;\n");
                        fwrite($file, $createTable . ";\n\n");
                    } catch (\Exception $e) {
                        fwrite($file, "-- Failed to generate CREATE TABLE statement: " . $e->getMessage() . "\n\n");
                    }

                    foreach ($exportAccounts as $account) {
                        $attributes = is_object($account) && method_exists($account, 'getAttributes') ? $account->getAttributes() : (array) $account;
                        $columns = array_keys($attributes);
                        $values = array_map(function ($value) {
                            if (is_null($value))
                                return 'NULL';
                            return "'" . addslashes((string) $value) . "'";
                        }, array_values($attributes));

                        $sql = "INSERT INTO trading_accounts (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");\n";
                        fwrite($file, $sql);
                    }
                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
            }

            if ($exportType == 'csv') {
                $headers = [
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=futures-accounts-list-" . now()->format('Y-m-d-H-i-s') . ".csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
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

        return view('templates.' . $template . '.blades.admin.futures.accounts', compact(
            'page_title',
            'accounts',
            'stats',
            'graph_data',
            'status_chart_data',
            'currency'
        ));
    }

    /**
     * Adjust account balance (Credit/Debit).
     */
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

            // record transaction
            // covert amount to website currency
            $account->refresh();
            $website_currency = getSetting('currency');
            $conversion = rateConverter($request->amount, $account->currency, $website_currency, 'futures');
            $reference = \Str::orderedUuid();
            $description = $request->type == 'credit' ? 'Trading account credit by admin' : 'Trading account debit by admin';
            $new_balance = $account->user->balance;
            recordTransaction($account->user, $conversion['converted_amount'], $website_currency, $request->amount, $account->currency, $conversion['exchange_rate'], $request->type, 'completed', $reference, $description, $new_balance);

            // record new notification message
            $title = $request->type == 'credit' ? 'Trading account credit' : 'Trading account debit';
            $body = $request->type == 'credit' ? __('Your futures trading account has been credited with :amount :currency', ['amount' => $request->amount, 'currency' => $account->currency]) : __('Your futures trading account has been debited with :amount :currency', ['amount' => $request->amount, 'currency' => $account->currency]);
            recordNotificationMessage($account->user, $title, $body);

            return response()->json(['status' => 'success', 'message' => __('Balance updated successfully.')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Update account status.
     */
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

    /**
     * Delete a trading account.
     */
    public function deleteTradingAccount(Request $request)
    {
        $request->validate(['id' => 'required|exists:trading_accounts,id']);

        $account = TradingAccount::findOrFail($request->id);
        $account->delete();

        return response()->json(['status' => 'success', 'message' => __('Account deleted successfully.')]);
    }

    /**
     * List all open positions.
     */
    public function positions(Request $request)
    {
        $page_title = __('Open Positions');
        $currency = TradingAccount::where('account_type', 'futures')->value('currency') ?? 'USD';

        // Base query for stats
        $baseStatsQuery = FuturesTradingPositions::query();
        if ($request->filled('user_id')) {
            $baseStatsQuery->where('user_id', $request->user_id);
        }

        // Stats
        $stats = [
            'total_positions' => (clone $baseStatsQuery)->count(),
            'long_count' => (clone $baseStatsQuery)->where('side', 'buy')->count(),
            'short_count' => (clone $baseStatsQuery)->where('side', 'sell')->count(),
            'total_margin' => (clone $baseStatsQuery)->sum('margin'),
            'total_pnl' => (clone $baseStatsQuery)->sum('unrealized_pnl'),
        ];

        // Charts Data
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
            $count = (clone $baseStatsQuery)->where('side', $side)->count();
            return [
                'status' => $side == 'buy' ? __('Long') : __('Short'),
                'count' => $count,
                'amount' => (clone $baseStatsQuery)->where('side', $side)->sum('margin')
            ];
        });

        // Query
        $query = FuturesTradingPositions::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('search') && $request->search) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('ticker', 'like', "%$term%")
                    ->orWhereHas('user', function ($uq) use ($term) {
                        $uq->where('username', 'like', "%$term%")
                            ->orWhere('email', 'like', "%$term%");
                    });
            });
        }

        // Export
        if ($request->has('export')) {
            $exportType = $request->export;
            $exportPositions = (clone $query)->latest()->get();
            $template = config('site.template');

            $requestedCols = $request->get('columns');
            if (is_string($requestedCols)) {
                $requestedCols = array_map('trim', explode(',', $requestedCols));
            } else {
                $requestedCols = ['username', 'ticker', 'side', 'size', 'entry_price', 'current_price', 'margin', 'leverage', 'unrealized_pnl', 'created_at'];
            }

            $columnMap = [
                'username' => 'User',
                'ticker' => 'Ticker',
                'side' => 'Side',
                'size' => 'Size',
                'entry_price' => 'Entry Price',
                'current_price' => 'Current Price',
                'margin' => 'Margin',
                'leverage' => 'Leverage',
                'unrealized_pnl' => 'PnL',
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
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("templates.$template.blades.admin.pdf.futures_positions", [
                    'positions' => $exportPositions,
                    'page_title' => $page_title,
                    'columns' => $selectedCols,
                    'orientation' => $orientation
                ]);
                return $pdf->download('futures-positions-report-' . now()->format('Y-m-d-H-i-s') . '.pdf');
            }

            if ($exportType == 'csv') {
                $headers = [
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=futures-positions-list-" . now()->format('Y-m-d-H-i-s') . ".csv",
                ];
                $callback = function () use ($exportPositions, $selectedCols) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, array_values($selectedCols));
                    foreach ($exportPositions as $pos) {
                        $row = [];
                        foreach (array_keys($selectedCols) as $key) {
                            switch ($key) {
                                case 'username':
                                    $row[] = $pos->user->username;
                                    break;
                                case 'created_at':
                                    $row[] = $pos->created_at->format('Y-m-d H:i:s');
                                    break;
                                case 'side':
                                    $row[] = ucfirst($pos->side);
                                    break;
                                default:
                                    $row[] = $pos->$key ?? '';
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
        $positions = $query->latest()->paginate($pagination);
        $template = config('site.template');

        if ($request->ajax()) {
            return view('templates.' . $template . '.blades.admin.futures.positions', compact('page_title', 'positions', 'currency', 'stats', 'graph_data', 'side_chart_data'));
        }

        return view('templates.' . $template . '.blades.admin.futures.positions', compact('page_title', 'positions', 'currency', 'stats', 'graph_data', 'side_chart_data'));
    }

    /**
     * Force close a position.
     */
    public function closePosition(Request $request)
    {
        $request->validate(['id' => 'required|exists:futures_trading_positions,id']);

        try {
            DB::beginTransaction();
            $position = FuturesTradingPositions::findOrFail($request->id);
            $user = $position->user;
            $trading_account = $user->tradingAccounts()->where('account_type', 'futures')->first();

            if (!$trading_account) {
                return response()->json(['status' => 'error', 'message' => __('User does not have a futures trading account.')]);
            }

            // Calculate final PnL (Using current price from position record)
            $price = $position->current_price;
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
                'type' => 'market',
                'ticker' => $position->ticker,
                'side' => $position->side === 'buy' ? 'sell' : 'buy',
                'size' => $position->size,
                'price' => $price,
                'status' => 'filled',
                'order_id' => 'ORD-' . strtoupper(\Str::random(10)),
                'timestamp' => (string) now()->valueOf(),
            ]);

            $position->delete();
            DB::commit();

            return response()->json(['status' => 'success', 'message' => __('Position closed and balance updated.')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Delete position record.
     */
    public function deletePosition(Request $request)
    {
        $request->validate(['id' => 'required|exists:futures_trading_positions,id']);
        FuturesTradingPositions::destroy($request->id);
        return response()->json(['status' => 'success', 'message' => __('Record deleted.')]);
    }

    /**
     * List all pending orders.
     */
    public function orders(Request $request)
    {
        $page_title = __('Order History');
        $currency = TradingAccount::where('account_type', 'futures')->value('currency') ?? 'USD';

        // Base Query for Stats
        $baseStatsQuery = FuturesTradingOrders::query();
        if ($request->filled('user_id')) {
            $baseStatsQuery->where('user_id', $request->user_id);
        }

        // Stats
        $stats = [
            'total_orders' => (clone $baseStatsQuery)->count(),
            'pending_count' => (clone $baseStatsQuery)->where('status', 'pending')->count(),
            'filled_count' => (clone $baseStatsQuery)->where('status', 'filled')->count(),
            'cancelled_count' => (clone $baseStatsQuery)->where('status', 'cancelled')->count(),
            'total_notional' => (clone $baseStatsQuery)->selectRaw('SUM(price * size) as total')->value('total') ?? 0,
        ];
        // Charts
        $periods = ['7d', '30d', '60d', '90d', '1y', 'ytd'];
        $statuses = ['pending', 'filled', 'cancelled'];

        $ordersRaw = (clone $baseStatsQuery)
            ->selectRaw('status, DATE(created_at) as day, COUNT(*) as count')
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('status', 'day')
            ->orderBy('day')
            ->get();

        $orderMaps = array_fill_keys($statuses, []);
        foreach ($ordersRaw as $row) {
            if (array_key_exists($row->status, $orderMaps)) {
                $orderMaps[$row->status][$row->day] = (int) $row->count;
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
            return ['labels' => array_keys($slice), 'data' => array_values($slice)];
        };

        $graph_data = [];
        foreach ($periods as $p) {
            $graph_data[$p] = [];
            foreach ($statuses as $status) {
                $graph_data[$p][$status] = $period($orderMaps[$status], $p);
            }
        }

        $status_chart_data = collect($statuses)->map(function ($status) use ($baseStatsQuery) {
            $count = (clone $baseStatsQuery)->where('status', $status)->count();
            return [
                'status' => ucfirst($status),
                'count' => $count,
                'amount' => (clone $baseStatsQuery)->where('status', $status)->selectRaw('SUM(price * size) as total')->value('total') ?? 0
            ];
        });

        // Main Query
        $query = FuturesTradingOrders::with('user');
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Search and Status Filters
        if ($request->has('search') && $request->search) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('ticker', 'like', "%$term%")
                    ->orWhereHas('user', function ($uq) use ($term) {
                        $uq->where('username', 'like', "%$term%")
                            ->orWhere('email', 'like', "%$term%");
                    });
            });
        }

        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Export
        if ($request->has('export')) {
            $exportType = $request->export;
            $exportOrders = (clone $query)->latest()->get();
            $template = config('site.template');

            $requestedCols = $request->get('columns');
            if (is_string($requestedCols)) {
                $requestedCols = array_map('trim', explode(',', $requestedCols));
            } else {
                $requestedCols = ['username', 'ticker', 'type', 'side', 'size', 'price', 'status', 'created_at'];
            }

            $columnMap = [
                'username' => 'User',
                'ticker' => 'Ticker',
                'type' => 'Type',
                'side' => 'Side',
                'size' => 'Size',
                'price' => 'Price',
                'status' => 'Status',
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
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("templates.$template.blades.admin.pdf.futures_orders", [
                    'orders' => $exportOrders,
                    'page_title' => $page_title,
                    'columns' => $selectedCols,
                    'orientation' => $orientation
                ]);
                return $pdf->download('futures-orders-report-' . now()->format('Y-m-d-H-i-s') . '.pdf');
            }

            if ($exportType == 'csv') {
                $headers = [
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=futures-orders-list-" . now()->format('Y-m-d-H-i-s') . ".csv",
                ];
                $callback = function () use ($exportOrders, $selectedCols) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, array_values($selectedCols));
                    foreach ($exportOrders as $ord) {
                        $row = [];
                        foreach (array_keys($selectedCols) as $key) {
                            switch ($key) {
                                case 'username':
                                    $row[] = $ord->user->username;
                                    break;
                                case 'created_at':
                                    $row[] = $ord->created_at->format('Y-m-d H:i:s');
                                    break;
                                default:
                                    $row[] = $ord->$key ?? '';
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
        $orders = $query->latest()->paginate($pagination);
        $template = config('site.template');

        if ($request->ajax()) {
            return view('templates.' . $template . '.blades.admin.futures.orders', compact('page_title', 'orders', 'currency', 'stats', 'graph_data', 'status_chart_data'));
        }

        return view('templates.' . $template . '.blades.admin.futures.orders', compact('page_title', 'orders', 'currency', 'stats', 'graph_data', 'status_chart_data'));
    }


    /**
     * Cancel an order.
     */
    public function cancelOrder(Request $request)
    {
        $request->validate(['id' => 'required|exists:futures_trading_orders,id']);

        try {
            DB::beginTransaction();
            $order = FuturesTradingOrders::findOrFail($request->id);

            if ($order->status !== 'pending') {
                return response()->json(['status' => 'error', 'message' => __('Only pending orders can be canceled')]);
            }

            // Refund locked margin
            if ($order->locked_margin > 0) {
                $trading_account = $order->user->tradingAccounts()->where('account_type', 'futures')->first();
                if ($trading_account) {
                    $trading_account->increment('balance', $order->locked_margin);
                }
            }

            $order->update(['status' => 'cancelled']);
            DB::commit();

            return response()->json(['status' => 'success', 'message' => __('Order cancelled successfully and margin refunded.')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Delete order record.
     */
    public function deleteOrder(Request $request)
    {
        $request->validate(['id' => 'required|exists:futures_trading_orders,id']);
        FuturesTradingOrders::destroy($request->id);
        return response()->json(['status' => 'success', 'message' => __('Record deleted.')]);
    }
}
