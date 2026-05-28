<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    /**
     * Display a listing of withdrawals.
     */
    public function index(Request $request)
    {
        $page_title = __('Withdrawals');

        // Base Query
        $query = Withdrawal::with(['user', 'withdrawalMethod']);

        // Stats
        $stats = [
            'total_withdrawn' => Withdrawal::sum('amount'),
            'total_completed' => Withdrawal::approved()->sum('amount'),
            'pending_count' => Withdrawal::pending()->count(),
            'pending_amount' => Withdrawal::pending()->sum('amount'),
            'failed_count' => Withdrawal::rejected()->count(),
            'failed_amount' => Withdrawal::rejected()->sum('amount'),
        ];

        // Filters
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
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
                })->orWhere('transaction_reference', 'like', "%$term%");
            });
        }

        // Export Handling
        if ($request->has('export')) {
            $exportType = $request->export;
            $exportWithdrawals = (clone $query)->latest()->get();
            $template = config('site.template');

            // Dynamic Column Selection
            $requestedCols = $request->get('columns');
            if (is_string($requestedCols)) {
                $requestedCols = array_map('trim', explode(',', $requestedCols));
            } else {
                $requestedCols = (array) ($requestedCols ?? ['username', 'withdrawal_method_name', 'amount', 'amount_payable', 'transaction_reference', 'status', 'created_at']);
            }

            // Header whitelist and mapping
            $columnMap = [
                'username' => 'User',
                'withdrawal_method_name' => 'Method',
                'amount' => 'Amount',
                'fee_amount' => 'Fee',
                'amount_payable' => 'Payable',
                'converted_amount' => 'Converted',
                'exchange_rate' => 'Rate',
                'transaction_reference' => 'Trx Ref',
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
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("templates.$template.blades.admin.pdf.withdrawals", [
                    'withdrawals' => $exportWithdrawals,
                    'page_title' => $page_title,
                    'columns' => $selectedCols,
                    'orientation' => $orientation
                ]);
                return $pdf->download('withdrawals-report-' . now()->format('Y-m-d-H-i-s') . '.pdf');
            }

            if ($exportType == 'sql') {
                $headers = [
                    'Content-Type' => 'application/sql',
                    'Content-Disposition' => 'attachment; filename="withdrawals-dump-' . now()->format('Y-m-d-H-i-s') . '.sql"',
                ];

                $callback = function () use ($exportWithdrawals) {
                    $file = fopen('php://output', 'w');
                    fwrite($file, "-- Withdrawals Table Dump\n");
                    fwrite($file, "-- Generated at: " . now() . "\n\n");

                    try {
                        $createTable = DB::select("SHOW CREATE TABLE withdrawals")[0]->{'Create Table'};
                        fwrite($file, "DROP TABLE IF EXISTS withdrawals;\n");
                        fwrite($file, $createTable . ";\n\n");
                    } catch (\Exception $e) {
                        fwrite($file, "-- Failed to generate CREATE TABLE statement: " . $e->getMessage() . "\n\n");
                    }

                    foreach ($exportWithdrawals as $withdrawal) {
                        $attributes = is_object($withdrawal) && method_exists($withdrawal, 'getAttributes') ? $withdrawal->getAttributes() : (array) $withdrawal;
                        $columns = array_keys($attributes);
                        $values = array_map(function ($value) {
                            if (is_null($value))
                                return 'NULL';
                            return "'" . addslashes((string) $value) . "'";
                        }, array_values($attributes));

                        $sql = "INSERT INTO withdrawals (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");\n";
                        fwrite($file, $sql);
                    }
                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
            }

            if ($exportType == 'csv') {
                $headers = [
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=withdrawals-list-" . now()->format('Y-m-d-H-i-s') . ".csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                ];

                $callback = function () use ($exportWithdrawals, $selectedCols) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, array_values($selectedCols));

                    foreach ($exportWithdrawals as $withdrawal) {
                        $row = [];
                        foreach (array_keys($selectedCols) as $col) {
                            if ($col === 'username') {
                                $row[] = $withdrawal->user->username ?? 'N/A';
                            } elseif ($col === 'withdrawal_method_name') {
                                $row[] = $withdrawal->withdrawalMethod->name ?? 'N/A';
                            } elseif ($col === 'created_at') {
                                $row[] = date('Y-m-d H:i:s', strtotime($withdrawal->created_at));
                            } else {
                                $row[] = $withdrawal->$col ?? '0.00';
                            }
                        }
                        fputcsv($file, $row);
                    }
                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
            }
        }

        // Pagination
        $perPage = getSetting('pagination');
        $withdrawals = $query->latest('id')->paginate($perPage)->withQueryString();

        // ── Visualizations Data ─────────────────────────────────────────────
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
        $withdrawalStatuses = ['pending', 'completed', 'failed'];

        $withdrawalRaw = Withdrawal::selectRaw('DATE(created_at) as day, status, SUM(amount) as total')
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

        $graph_data = [];
        foreach ($periods as $p) {
            $graph_data[$p] = [];
            foreach ($withdrawalStatuses as $status) {
                $graph_data[$p][$status] = $period($withdrawalMaps[$status], $p);
            }
        }

        // Status Distribution Data for Pie/Doughnut Chart
        $status_chart_data = Withdrawal::select('status', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total_amount'))
            ->groupBy('status')
            ->get()
            ->map(function ($item) {
                return [
                    'status' => ucfirst($item->status),
                    'count' => $item->count,
                    'amount' => (float) $item->total_amount
                ];
            });

        $template = config('site.template');

        if ($request->ajax() && !$request->has('export')) {
            return response()->json([
                'html' => view("templates.$template.blades.admin.withdrawals.partials.table", compact('withdrawals'))->render(),
                'pagination' => (string) $withdrawals->links('templates.bento.blades.partials.pagination'),
                'stats' => $stats
            ]);
        }

        return view("templates.$template.blades.admin.withdrawals.index", compact('page_title', 'withdrawals', 'stats', 'graph_data', 'status_chart_data'));
    }

    /**
     * Display the specified withdrawal details.
     */
    public function viewWithdrawal($id)
    {
        $withdrawal = Withdrawal::with(['user', 'withdrawalMethod'])->findOrFail($id);
        $page_title = __('Withdrawal Details');
        $template = config('site.template');

        return view("templates.$template.blades.admin.withdrawals.view", compact('page_title', 'withdrawal'));
    }

    /**
     * Update the withdrawal status.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed',
        ]);

        $withdrawal = Withdrawal::findOrFail($id);

        $status = $request->status;

        // guard rails
        $allowed = ['pending'];
        if (!in_array($withdrawal->status, $allowed)) {
            return response()->json(['status' => 'error', 'message' => __('Withdrawal Already processed')], 400);
        }

        // current status cannot be same as old status
        if ($withdrawal->status === $request->status) {
            return response()->json(['status' => 'error', 'message' => __('Withdrawal status cannot be same as old status')], 400);
        }

        if ($status === 'completed') {
            $user = $withdrawal->user;

            // Record withdrawal update
            $withdrawal->status = 'completed';
            $withdrawal->save();

            // send withdrawal email
            $custom_subject = "Withdrawal Completed";
            $custom_message = "Withdrawal has been completed find the details below.";
            sendWithdrawalEmail($custom_subject, $custom_message, $withdrawal);

            // Record notification
            $title = "Withdrawal Completed";
            $body = "Withdrawal via " . $withdrawal->withdrawalMethod->name . " has been completed";
            recordNotificationMessage($user, $title, $body);

            return response()->json(['status' => 'success', 'message' => __('Payment processed successfully')], 200);
        }



        if (in_array($status, ['failed', 'rejected'])) {
            $withdrawal->status = 'failed';
            $withdrawal->save();

            // send deposit email
            $custom_subject = "Withdrawal Failed and Refunded";
            $custom_message = "Your withdrawal request failed and has been refunded to your available balance. If the error persist, contact an admin";
            sendWithdrawalEmail($custom_subject, $custom_message, $withdrawal);
            recordNotificationMessage($withdrawal->user, $custom_subject, $custom_message);

            // refund the user
            $user = $withdrawal->user;
            $user->refresh();
            $user->increment('balance', $withdrawal->amount);

            // record transaction
            recordTransaction($user, $withdrawal->amount, getSetting('currency'), $withdrawal->converted_amount, $withdrawal->currency, $withdrawal->exchange_rate, 'credit', 'completed', $withdrawal->transaction_reference, "Failed Withdrawal refund", $user->balance);

            return response()->json(['status' => 'success', 'message' => __('Payment failed or expired')], 200);
        }

        $withdrawal->status = $request->status;
        $withdrawal->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('Withdrawal status updated successfully.')
            ]);
        }

        return redirect()->back()->with('success', __('Withdrawal status updated successfully.'));
    }

    /**
     * Delete the withdrawal.
     */
    public function delete(Request $request, $id)
    {
        $withdrawal = Withdrawal::findOrFail($id);

        $withdrawal->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('Withdrawal deleted successfully.')
            ]);
        }

        return redirect()->route('admin.withdrawals.index')->with('success', __('Withdrawal deleted successfully.'));
    }
}
