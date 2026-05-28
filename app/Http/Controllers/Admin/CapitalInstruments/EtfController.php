<?php

namespace App\Http\Controllers\Admin\CapitalInstruments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\EtfHolding;
use App\Models\EtfHoldingHistory;
use App\Models\User;

class EtfController extends Controller
{
    /**
     * Display all ETF holdings.
     */
    public function index(Request $request)
    {
        $page_title = __('ETF Holdings');
        $template = config('site.template');
        $search = $request->search;
        $conversion = rateConverter(1, 'USD', getSetting('currency'), 'etf');
        $exchange_rate = $conversion['exchange_rate'];

        $holdings_query = EtfHolding::with('user')
            ->when($search, function ($q) use ($search) {
                return $q->where('ticker', 'like', "%$search%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('username', 'like', "%$search%")
                            ->orWhere('email', 'like', "%$search%");
                    });
            });

        // Global Analytics before pagination
        $stats = (clone $holdings_query)->selectRaw('
            COUNT(*) as total_holdings,
            COALESCE(SUM(shares), 0) as total_shares,
            COALESCE(SUM(pnl), 0) as total_pnl,
            COALESCE(AVG(pnl_percent), 0) as avg_pnl,
            COALESCE(SUM(shares * average_price + pnl), 0) as total_value
        ')->first();

        $holdings = $holdings_query->latest();

        // Export Handling
        if ($request->has('export')) {
            $exportType = $request->export;
            $items = (clone $holdings)->get();
            $template = config('site.template');

            $requestedCols = $request->get('columns');
            if (is_string($requestedCols)) {
                $requestedCols = array_map('trim', explode(',', $requestedCols));
            } else {
                $requestedCols = (array) ($requestedCols ?? ['username', 'ticker', 'shares', 'average_price', 'pnl', 'pnl_percent']);
            }

            $columnMap = [
                'username' => 'User',
                'ticker' => 'Ticker',
                'shares' => 'Shares',
                'average_price' => 'Avg Price',
                'pnl' => 'PnL',
                'pnl_percent' => 'PnL %',
            ];

            $selectedCols = [];
            foreach ($requestedCols as $col) {
                if (array_key_exists($col, $columnMap)) {
                    $selectedCols[$col] = $columnMap[$col];
                }
            }

            if ($exportType == 'pdf') {
                $orientation = count($selectedCols) <= 8 ? 'portrait' : 'landscape';
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("templates.$template.blades.admin.pdf.etfs", [
                    'holdings' => $items,
                    'page_title' => $page_title,
                    'columns' => $selectedCols,
                    'exchange_rate' => $exchange_rate,
                    'orientation' => $orientation
                ]);
                return $pdf->download('etf-holdings-' . now()->format('Y-m-d-H-i-s') . '.pdf');
            }

            if ($exportType == 'sql') {
                $headers = [
                    'Content-Type' => 'application/sql',
                    'Content-Disposition' => 'attachment; filename="etfs-holdings-dump-' . now()->format('Y-m-d-H-i-s') . '.sql"',
                ];
                $callback = function () use ($items) {
                    $file = fopen('php://output', 'w');
                    fwrite($file, "-- ETF Holdings Table Dump\n\n");
                    foreach ($items as $item) {
                        $attributes = is_object($item) && method_exists($item, 'getAttributes') ? $item->getAttributes() : (array) $item;
                        $cols = array_keys($attributes);
                        $vals = array_map(fn($v) => is_null($v) ? 'NULL' : "'" . addslashes((string) $v) . "'", array_values($attributes));
                        fwrite($file, "INSERT INTO etf_holdings (" . implode(', ', $cols) . ") VALUES (" . implode(', ', $vals) . ");\n");
                    }
                    fclose($file);
                };
                return response()->stream($callback, 200, $headers);
            }

            if ($exportType == 'csv') {
                $headers = [
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=etf-holdings-" . now()->format('Y-m-d-H-i-s') . ".csv",
                ];
                $callback = function () use ($items, $selectedCols, $exchange_rate) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, array_values($selectedCols));
                    foreach ($items as $item) {
                        $row = [];
                        foreach (array_keys($selectedCols) as $key) {
                            $row[] = match ($key) {
                                'username' => $item->user?->username ?? 'N/A',
                                'average_price' => $item->average_price * $exchange_rate,
                                'pnl' => $item->pnl * $exchange_rate,
                                'pnl_percent' => $item->pnl_percent . '%',
                                default => $item->$key,
                            };
                        }
                        fputcsv($file, $row);
                    }
                    fclose($file);
                };
                return response()->stream($callback, 200, $headers);
            }
        }

        $holdings = $holdings->paginate(getSetting('pagination'));

        if ($request->ajax() && !$request->has('export')) {
            return view("templates.$template.blades.admin.etfs.index", compact('page_title', 'holdings', 'exchange_rate', 'stats'));
        }

        return view("templates.$template.blades.admin.etfs.index", compact('page_title', 'holdings', 'exchange_rate', 'stats'));
    }

    /**
     * Display ETF order history.
     */
    public function history(Request $request)
    {
        $page_title = __('Order History');
        $template = config('site.template');
        $search = $request->search;
        $type = $request->type;

        $conversion = rateConverter(1, 'USD', getSetting('currency'), 'etf');
        $exchange_rate = $conversion['exchange_rate'];

        $history_query = EtfHoldingHistory::with('user')
            ->when($search, function ($q) use ($search) {
                return $q->where('ticker', 'like', "%$search%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('username', 'like', "%$search%")
                            ->orWhere('email', 'like', "%$search%");
                    });
            })
            ->when($type, function ($q) use ($type) {
                return $q->where('transaction_type', $type);
            });

        // Calculate Stats (Filtered)
        $stats = [
            'total_buy' => (clone $history_query)->where('transaction_type', 'buy')->sum('amount'),
            'total_sell' => (clone $history_query)->where('transaction_type', 'sell')->sum('amount'),
            'total_trades' => (clone $history_query)->count(),
            'trades_today' => (clone $history_query)->whereDate('created_at', now()->today())->count(),
            'unique_tickers' => (clone $history_query)->distinct('ticker')->count('ticker'),
        ];

        // Export Handling
        if ($request->has('export')) {
            $exportType = $request->export;
            $items = (clone $history_query)->latest()->get();
            $template = config('site.template');

            $requestedCols = $request->get('columns');
            if (is_string($requestedCols)) {
                $requestedCols = array_map('trim', explode(',', $requestedCols));
            } else {
                $requestedCols = (array) ($requestedCols ?? ['username', 'ticker', 'type', 'shares', 'price', 'amount', 'fee', 'created_at']);
            }

            $columnMap = [
                'username' => 'User',
                'ticker' => 'Ticker',
                'type' => 'Type',
                'shares' => 'Shares',
                'price' => 'Price',
                'amount' => 'Total',
                'fee' => 'Fee',
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
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("templates.$template.blades.admin.pdf.etf-history", [
                    'history' => $items,
                    'page_title' => $page_title,
                    'columns' => $selectedCols,
                    'exchange_rate' => $exchange_rate,
                    'orientation' => $orientation
                ]);
                return $pdf->download('etf-order-history-' . now()->format('Y-m-d-H-i-s') . '.pdf');
            }

            if ($exportType == 'sql') {
                $headers = [
                    'Content-Type' => 'application/sql',
                    'Content-Disposition' => 'attachment; filename="etf-history-dump-' . now()->format('Y-m-d-H-i-s') . '.sql"',
                ];
                $callback = function () use ($items) {
                    $file = fopen('php://output', 'w');
                    fwrite($file, "-- ETF History Table Dump\n\n");
                    foreach ($items as $item) {
                        $attributes = is_object($item) && method_exists($item, 'getAttributes') ? $item->getAttributes() : (array) $item;
                        $cols = array_keys($attributes);
                        $vals = array_map(fn($v) => is_null($v) ? 'NULL' : "'" . addslashes((string) $v) . "'", array_values($attributes));
                        fwrite($file, "INSERT INTO etf_holding_histories (" . implode(', ', $cols) . ") VALUES (" . implode(', ', $vals) . ");\n");
                    }
                    fclose($file);
                };
                return response()->stream($callback, 200, $headers);
            }

            if ($exportType == 'csv') {
                $headers = [
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=etf-order-history-" . now()->format('Y-m-d-H-i-s') . ".csv",
                ];
                $callback = function () use ($items, $selectedCols, $exchange_rate) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, array_values($selectedCols));
                    foreach ($items as $item) {
                        $row = [];
                        foreach (array_keys($selectedCols) as $key) {
                            $row[] = match ($key) {
                                'username' => $item->user?->username ?? 'N/A',
                                'type' => ucfirst($item->transaction_type),
                                'price' => $item->price_at_action * $exchange_rate,
                                'amount' => $item->amount * $exchange_rate,
                                'fee' => $item->fee_amount * $exchange_rate,
                                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                                default => $item->$key,
                            };
                        }
                        fputcsv($file, $row);
                    }
                    fclose($file);
                };
                return response()->stream($callback, 200, $headers);
            }
        }

        $history = (clone $history_query)->latest()->paginate(getSetting('pagination'));

        // ── Visualizations Data ─────────────────────────────────────────────
        $yearStart = now()->startOfYear();
        $oneYearAgo = now()->subYear()->startOfDay();
        $graphWindowStart = $oneYearAgo->lt($yearStart) ? $oneYearAgo : $yearStart;

        // Daily volume aggregation
        $rawVolume = EtfHoldingHistory::selectRaw('DATE(created_at) as day, transaction_type as type, SUM(amount) as total')
            ->where('created_at', '>=', $graphWindowStart)
            ->groupBy('day', 'type')
            ->get();

        $volumeMap = ['buy' => [], 'sell' => []];
        foreach ($rawVolume as $row) {
            $volumeMap[$row->type][$row->day] = (float) $row->total;
        }

        $sliceDays = function (array $map, int $days): array {
            $res = [];
            for ($i = $days - 1; $i >= 0; $i--) {
                $k = now()->subDays($i)->format('Y-m-d');
                $res[$k] = $map[$k] ?? 0;
            }
            return $res;
        };

        $sliceYtd = function (array $map): array {
            $res = [];
            $start = now()->startOfYear();
            $days = (int) $start->diffInDays(now()) + 1;
            for ($i = 0; $i < $days; $i++) {
                $k = $start->copy()->addDays($i)->format('Y-m-d');
                $res[$k] = $map[$k] ?? 0;
            }
            return $res;
        };

        $buildPeriod = function (array $map, string $p) use ($sliceDays, $sliceYtd) {
            $slice = match ($p) {
                '7d' => $sliceDays($map, 7),
                '30d' => $sliceDays($map, 30),
                '1y' => $sliceDays($map, 365),
                'ytd' => $sliceYtd($map),
                default => $sliceDays($map, 7),
            };
            return ['labels' => array_keys($slice), 'data' => array_values($slice)];
        };

        $graph_data = [];
        foreach (['7d', '30d', '1y', 'ytd'] as $p) {
            $graph_data[$p] = [
                'buy' => $buildPeriod($volumeMap['buy'], $p),
                'sell' => $buildPeriod($volumeMap['sell'], $p),
            ];
        }

        // Ticker distribution
        $ticker_data = EtfHoldingHistory::select('ticker', \DB::raw('COUNT(*) as count'), \DB::raw('SUM(amount) as total'))
            ->groupBy('ticker')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->map(fn($item) => ['ticker' => $item->ticker, 'count' => (int) $item->count, 'total' => (float) $item->total]);


        if ($request->ajax() && !$request->has('export')) {
            return view("templates.$template.blades.admin.etfs.history", compact('page_title', 'history', 'exchange_rate', 'stats', 'graph_data', 'ticker_data'));
        }

        return view("templates.$template.blades.admin.etfs.history", compact('page_title', 'history', 'exchange_rate', 'stats', 'graph_data', 'ticker_data'));
    }

    /**
     * Delete a holding (emergency/management).
     */
    public function delete(Request $request)
    {
        $request->validate(['id' => 'required|exists:etf_holdings,id']);
        EtfHolding::findOrFail($request->id)->delete();
        return response()->json(['success' => true, 'message' => __('Holding deleted successfully')]);
    }

    /**
     * Delete a history record.
     */
    public function deleteHistory(Request $request)
    {
        $request->validate(['id' => 'required|exists:etf_holding_histories,id']);
        EtfHoldingHistory::findOrFail($request->id)->delete();
        return response()->json(['success' => true, 'message' => __('Order history record deleted successfully')]);
    }
}
