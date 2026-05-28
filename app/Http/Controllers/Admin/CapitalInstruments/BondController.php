<?php

namespace App\Http\Controllers\Admin\CapitalInstruments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BondHolding;
use App\Models\BondHoldingHistory;
use App\Models\User;

class BondController extends Controller
{
    /**
     * Display all bond holdings.
     */
    public function index(Request $request)
    {
        $page_title = __('Bond Holdings');
        $template = config('site.template');
        $search = $request->search;
        $status = $request->status;

        $holdings_query = BondHolding::with('user')
            ->when($search, function ($q) use ($search) {
                return $q->where(function ($query) use ($search) {
                    $query->where('cusip', 'like', "%$search%")
                        ->orWhere('bond_name', 'like', "%$search%")
                        ->orWhereHas('user', function ($uq) use ($search) {
                            $uq->where('username', 'like', "%$search%")
                                ->orWhere('email', 'like', "%$search%");
                        });
                });
            })
            ->when($status, function ($q) use ($status) {
                return $q->where('status', $status);
            });

        // Global Analytics before pagination
        $stats = (clone $holdings_query)->selectRaw('
            COUNT(*) as total_holdings,
            COALESCE(SUM(amount), 0) as total_invested,
            COALESCE(SUM(interest_amount), 0) as total_expected_interest,
            SUM(CASE WHEN status = "matured" THEN 1 ELSE 0 END) as matured_count,
            SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active_count
        ')->first();

        $holdings = $holdings_query->latest();

        // Export Handling
        if ($request->has('export')) {
            $exportType = $request->export;
            $items = (clone $holdings)->get();

            $requestedCols = $request->get('columns');
            if (is_string($requestedCols)) {
                $requestedCols = array_map('trim', explode(',', $requestedCols));
            } else {
                $requestedCols = (array) ($requestedCols ?? ['username', 'cusip', 'bond_name', 'amount', 'coupon', 'maturity_date', 'status']);
            }

            $columnMap = [
                'username' => 'User',
                'cusip' => 'CUSIP',
                'bond_name' => 'Bond Name',
                'amount' => 'Principal',
                'coupon' => 'Coupon',
                'interest_amount' => 'Interest',
                'maturity_date' => 'Maturity',
                'status' => 'Status',
            ];

            $selectedCols = [];
            foreach ($requestedCols as $col) {
                if (array_key_exists($col, $columnMap)) {
                    $selectedCols[$col] = $columnMap[$col];
                }
            }

            if ($exportType == 'pdf') {
                $orientation = count($selectedCols) <= 8 ? 'portrait' : 'landscape';
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("templates.$template.blades.admin.pdf.bonds", [
                    'holdings' => $items,
                    'page_title' => $page_title,
                    'columns' => $selectedCols,
                    'orientation' => $orientation
                ]);
                return $pdf->download('bond-inventory-' . now()->format('Y-m-d-H-i-s') . '.pdf');
            }

            if ($exportType == 'sql') {
                $headers = [
                    'Content-Type' => 'application/sql',
                    'Content-Disposition' => 'attachment; filename="bonds-dump-' . now()->format('Y-m-d-H-i-s') . '.sql"',
                ];
                $callback = function () use ($items) {
                    $file = fopen('php://output', 'w');
                    fwrite($file, "-- Bond Holdings Table Dump\n\n");
                    foreach ($items as $item) {
                        /** @var BondHolding $item */
                        $attributes = (array) $item->getAttributes();
                        $cols = array_keys($attributes);
                        $vals = array_map(fn($v) => is_null($v) ? 'NULL' : "'" . addslashes((string) $v) . "'", array_values($attributes));
                        fwrite($file, "INSERT INTO bond_holdings (" . implode(', ', $cols) . ") VALUES (" . implode(', ', $vals) . ");\n");
                    }
                    fclose($file);
                };
                return response()->stream($callback, 200, $headers);
            }

            if ($exportType == 'csv') {
                $headers = [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="bond-inventory-' . now()->format('Y-m-d-H-i-s') . '.csv"',
                ];
                $callback = function () use ($items, $selectedCols) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, array_values($selectedCols));
                    foreach ($items as $item) {
                        $row = [];
                        foreach ($selectedCols as $key => $label) {
                            if ($key == 'username') {
                                $row[] = $item->user->username;
                            } else {
                                $row[] = $item->{$key};
                            }
                        }
                        fputcsv($file, $row);
                    }
                    fclose($file);
                };
                return response()->stream($callback, 200, $headers);
            }
        }

        $holdings = $holdings->paginate(getSetting('pagination'));

        if ($request->ajax()) {
            return view("templates.$template.blades.admin.bonds.index", compact('page_title', 'holdings', 'stats'));
        }

        return view("templates.$template.blades.admin.bonds.index", compact('page_title', 'holdings', 'stats'));
    }

    /**
     * Display bond order history.
     */
    public function history(Request $request)
    {
        $page_title = __('Bond History');
        $template = config('site.template');
        $search = $request->search;
        $type = $request->type;

        $history_query = BondHoldingHistory::with(['user', 'bondHolding'])
            ->when($search, function ($q) use ($search) {
                return $q->where(function ($query) use ($search) {
                    $query->where('cusip', 'like', "%$search%")
                        ->orWhereHas('user', function ($uq) use ($search) {
                            $uq->where('username', 'like', "%$search%")
                                ->orWhere('email', 'like', "%$search%");
                        });
                });
            })
            ->when($type, function ($q) use ($type) {
                return $q->where('transaction_type', $type);
            });

        // Export Handling
        if ($request->has('export')) {
            $exportType = $request->export;
            $items = (clone $history_query)->get();

            $requestedCols = $request->get('columns');
            if (is_string($requestedCols)) {
                $requestedCols = array_map('trim', explode(',', $requestedCols));
            } else {
                $requestedCols = (array) ($requestedCols ?? ['username', 'cusip', 'amount', 'transaction_type', 'created_at']);
            }

            $columnMap = [
                'username' => 'User',
                'cusip' => 'CUSIP',
                'amount' => 'Amount',
                'transaction_type' => 'Type',
                'created_at' => 'Date',
            ];

            $selectedCols = [];
            foreach ($requestedCols as $col) {
                if (array_key_exists($col, $columnMap)) {
                    $selectedCols[$col] = $columnMap[$col];
                }
            }

            if ($exportType == 'pdf') {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("templates.$template.blades.admin.pdf.bond-history", [
                    'history' => $items,
                    'page_title' => $page_title,
                    'columns' => $selectedCols,
                ]);
                return $pdf->download('bond-history-' . now()->format('Y-m-d-H-i-s') . '.pdf');
            }

            if ($exportType == 'sql') {
                $headers = [
                    'Content-Type' => 'application/sql',
                    'Content-Disposition' => 'attachment; filename="bond-history-dump-' . now()->format('Y-m-d-H-i-s') . '.sql"',
                ];
                $callback = function () use ($items) {
                    $file = fopen('php://output', 'w');
                    fwrite($file, "-- Bond History Table Dump\n\n");
                    foreach ($items as $item) {
                        /** @var BondHoldingHistory $item */
                        $attributes = (array) $item->getAttributes();
                        $cols = array_keys($attributes);
                        $vals = array_map(fn($v) => is_null($v) ? 'NULL' : "'" . addslashes((string) $v) . "'", array_values($attributes));
                        fwrite($file, "INSERT INTO bond_holding_histories (" . implode(', ', $cols) . ") VALUES (" . implode(', ', $vals) . ");\n");
                    }
                    fclose($file);
                };
                return response()->stream($callback, 200, $headers);
            }

            if ($exportType == 'csv') {
                $headers = [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="bond-history-' . now()->format('Y-m-d-H-i-s') . '.csv"',
                ];
                $callback = function () use ($items, $selectedCols) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, array_values($selectedCols));
                    foreach ($items as $item) {
                        $row = [];
                        foreach ($selectedCols as $key => $label) {
                            if ($key == 'username') {
                                $row[] = $item->user->username;
                            } else {
                                $row[] = $item->{$key};
                            }
                        }
                        fputcsv($file, $row);
                    }
                    fclose($file);
                };
                return response()->stream($callback, 200, $headers);
            }
        }

        $stats = [
            'total_buy' => (clone $history_query)->where('transaction_type', 'buy')->sum('amount'),
            'total_payout' => (clone $history_query)->where('transaction_type', 'payout')->sum('amount'),
            'total_transactions' => (clone $history_query)->count(),
        ];

        $history = $history_query->latest()->paginate(getSetting('pagination'));

        if ($request->ajax()) {
            return view("templates.$template.blades.admin.bonds.history", compact('page_title', 'history', 'stats'));
        }

        return view("templates.$template.blades.admin.bonds.history", compact('page_title', 'history', 'stats'));
    }

    /**
     * Delete a holding.
     */
    public function delete(Request $request)
    {
        $request->validate(['id' => 'required|exists:bond_holdings,id']);
        BondHolding::findOrFail($request->id)->delete();
        return response()->json(['success' => true, 'message' => __('Bond holding deleted successfully')]);
    }

    /**
     * Delete a history record.
     */
    public function deleteHistory(Request $request)
    {
        $request->validate(['id' => 'required|exists:bond_holding_histories,id']);
        BondHoldingHistory::findOrFail($request->id)->delete();
        return response()->json(['success' => true, 'message' => __('History record deleted successfully')]);
    }
}
