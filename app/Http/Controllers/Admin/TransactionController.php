<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions.
     */
    public function index(Request $request)
    {
        $page_title = __('Transactions');

        $query = Transaction::with(['user']);

        // Stats
        $stats = [
            'total_count' => Transaction::count(),
            'total_volume' => Transaction::sum('amount'),
            'total_credit_count' => Transaction::where('type', 'credit')->count(),
            'total_credit_volume' => Transaction::where('type', 'credit')->sum('amount'),
            'total_debit_count' => Transaction::where('type', 'debit')->count(),
            'total_debit_volume' => Transaction::where('type', 'debit')->sum('amount'),
            'today_count' => Transaction::whereDate('created_at', today())->count(),
            'today_volume' => Transaction::whereDate('created_at', today())->sum('amount'),
            'today_credit_count' => Transaction::whereDate('created_at', today())->where('type', 'credit')->count(),
            'today_credit_volume' => Transaction::whereDate('created_at', today())->where('type', 'credit')->sum('amount'),
            'today_debit_count' => Transaction::whereDate('created_at', today())->where('type', 'debit')->count(),
            'today_debit_volume' => Transaction::whereDate('created_at', today())->where('type', 'debit')->sum('amount'),
        ];

        // Filters
        if ($request->has('type') && $request->type != 'all') {
            $query->where('type', $request->type);
        }

        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('search') && $request->search) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->whereHas('user', function ($uq) use ($term) {
                    $uq->where('username', 'like', "%$term%")
                        ->orWhere('email', 'like', "%$term%")
                        ->orWhere('first_name', 'like', "%$term%")
                        ->orWhere('last_name', 'like', "%$term%");
                })->orWhere('reference', 'like', "%$term%");
            });
        }

        // Export Handling
        if ($request->has('export')) {
            $exportType = $request->export;
            $exportTransactions = (clone $query)->latest()->get();
            $template = config('site.template');

            // Dynamic Column Selection
            $requestedCols = $request->get('columns');
            if (is_string($requestedCols)) {
                $requestedCols = array_map('trim', explode(',', $requestedCols));
            } else {
                $requestedCols = (array) ($requestedCols ?? ['username', 'amount', 'new_balance', 'type', 'reference', 'status', 'created_at']);
            }

            // Header whitelist and mapping
            $columnMap = [
                'username' => 'User',
                'amount' => 'Amount',
                'new_balance' => 'New Balance',
                'type' => 'Type',
                'reference' => 'Trx Ref',
                'status' => 'Status',
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
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("templates.$template.blades.admin.pdf.transactions", [
                    'transactions' => $exportTransactions,
                    'page_title' => $page_title,
                    'columns' => $selectedCols,
                    'orientation' => $orientation
                ]);
                return $pdf->download('transactions-report-' . now()->format('Y-m-d-H-i-s') . '.pdf');
            }

            if ($exportType == 'sql') {
                $headers = [
                    'Content-Type' => 'application/sql',
                    'Content-Disposition' => 'attachment; filename="transactions-dump-' . now()->format('Y-m-d-H-i-s') . '.sql"',
                ];

                $callback = function () use ($exportTransactions) {
                    $file = fopen('php://output', 'w');
                    fwrite($file, "-- Transactions Table Dump\n");
                    fwrite($file, "-- Generated at: " . now() . "\n\n");

                    try {
                        $createTable = DB::select("SHOW CREATE TABLE transactions")[0]->{'Create Table'};
                        fwrite($file, "DROP TABLE IF EXISTS transactions;\n");
                        fwrite($file, $createTable . ";\n\n");
                    } catch (\Exception $e) {
                        fwrite($file, "-- Failed to generate CREATE TABLE statement: " . $e->getMessage() . "\n\n");
                    }

                    foreach ($exportTransactions as $transaction) {
                        $attributes = is_object($transaction) && method_exists($transaction, 'getAttributes') ? $transaction->getAttributes() : (array) $transaction;
                        $columns = array_keys($attributes);
                        $values = array_map(function ($value) {
                            if (is_null($value))
                                return 'NULL';
                            return "'" . addslashes((string) $value) . "'";
                        }, array_values($attributes));

                        $sql = "INSERT INTO transactions (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");\n";
                        fwrite($file, $sql);
                    }
                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
            }

            if ($exportType == 'csv') {
                $headers = [
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=transactions-list-" . now()->format('Y-m-d-H-i-s') . ".csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                ];

                $callback = function () use ($exportTransactions, $selectedCols) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, array_values($selectedCols));

                    foreach ($exportTransactions as $transaction) {
                        $row = [];
                        foreach (array_keys($selectedCols) as $key) {
                            switch ($key) {
                                case 'username':
                                    $row[] = $transaction->user->username;
                                    break;
                                case 'type':
                                    $row[] = ucfirst($transaction->type);
                                    break;
                                case 'status':
                                    $row[] = ucfirst($transaction->status);
                                    break;
                                case 'created_at':
                                    $row[] = $transaction->created_at->format('Y-m-d H:i:s');
                                    break;
                                case 'amount':
                                    $row[] = $transaction->amount;
                                    break;
                                case 'currency':
                                    $row[] = $transaction->currency;
                                    break;
                                case 'converted_amount':
                                    $row[] = $transaction->converted_amount;
                                    break;
                                case 'converted_currency':
                                    $row[] = $transaction->converted_currency;
                                    break;
                                case 'rate':
                                    $row[] = $transaction->rate;
                                    break;
                                case 'description':
                                    $row[] = $transaction->description;
                                    break;
                                case 'new_balance':
                                    $row[] = $transaction->new_balance;
                                    break;
                                case 'reference':
                                    $row[] = $transaction->reference;
                                    break;
                                default:
                                    $row[] = $transaction->$key ?? '';
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

        $transactions = $query->latest()->paginate(getSetting('pagination', 15));

        // Distinct types for filter dropdown
        $types = Transaction::select('type')->distinct()->pluck('type');

        // ── Visualizations Data ─────────────────────────────────────────────
        $yearStart = now()->startOfYear();
        $oneYearAgo = now()->subYear()->startOfDay();
        $graphWindowStart = $oneYearAgo->lt($yearStart) ? $oneYearAgo : $yearStart;

        // Helper: slice a keyed map to the last N days
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

        $periods = ['7d', '30d', '60d', '90d', '1y', 'ytd'];

        // Get daily credits
        $creditsRaw = Transaction::selectRaw('DATE(created_at) as day, SUM(amount) as total')
            ->where('type', 'credit')
            ->where('created_at', '>=', $graphWindowStart)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $creditsMap = [];
        foreach ($creditsRaw as $row) {
            $creditsMap[$row->day] = (float) $row->total;
        }

        // Get daily debits
        $debitsRaw = Transaction::selectRaw('DATE(created_at) as day, SUM(amount) as total')
            ->where('type', 'debit')
            ->where('created_at', '>=', $graphWindowStart)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $debitsMap = [];
        foreach ($debitsRaw as $row) {
            $debitsMap[$row->day] = (float) $row->total;
        }

        $graph_data = [];
        foreach ($periods as $p) {
            $graph_data[$p] = [
                'credits' => $period($creditsMap, $p),
                'debits' => $period($debitsMap, $p),
            ];
        }

        // Type Distribution Data (equivalent to Plan chart)
        $type_chart_data = Transaction::select('type', \Illuminate\Support\Facades\DB::raw('SUM(amount) as total_amount'))
            ->groupBy('type')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => ucfirst($item->type),
                    'total' => (float) $item->total_amount
                ];
            });

        $template = config('site.template');
        return view("templates.$template.blades.admin.transactions", compact('page_title', 'transactions', 'stats', 'types', 'graph_data', 'type_chart_data'));
    }

    /**
     * Delete a transaction.
     */
    public function delete($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return response()->json(['success' => true, 'message' => __('Transaction deleted successfully.')]);
    }

    /**
     * Bulk delete transactions.
     */
    public function bulkDelete(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:transactions,id',
        ]);

        Transaction::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => true, 'message' => __('Selected transactions deleted successfully.')]);
    }
}
