<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{
    /**
     * Display a listing of investments.
     */
    public function index(Request $request)
    {
        $page_title = __('Investments');

        // Base Query
        $query = \App\Models\Investment::with(['user', 'plan']);

        // Stats
        $stats = [
            'total_invested' => \App\Models\Investment::sum('capital_invested'),
            'total_roi' => \App\Models\Investment::sum('roi_earned'),
            'active_count' => \App\Models\Investment::active()->count(),
            'completed_count' => \App\Models\Investment::completed()->count(),
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
                })->orWhereHas('plan', function ($pq) use ($term) {
                    $pq->where('name', 'like', "%$term%");
                });
            });
        }

        // Export Handling
        if ($request->has('export')) {
            $exportType = $request->export;
            $exportInvestments = (clone $query)->latest()->get();
            $template = config('site.template');

            // Dynamic Column Selection
            $requestedCols = $request->get('columns');
            if (is_string($requestedCols)) {
                $requestedCols = array_map('trim', explode(',', $requestedCols));
            } else {
                $requestedCols = (array) ($requestedCols ?? ['username', 'plan_name', 'capital_invested', 'roi_earned', 'status', 'created_at']);
            }

            // Header whitelist and mapping
            $columnMap = [
                'username' => 'User',
                'plan_name' => 'Plan',
                'capital_invested' => 'Invested',
                'compounding_capital' => 'Compounding',
                'roi_earned' => 'ROI Earned',
                'cycle_count' => 'Cycles',
                'total_cycles' => 'Total Cycles',
                'auto_reinvest' => 'Auto Reinvest',
                'status' => 'Status',
                'next_roi_at' => 'Next ROI',
                'expires_at' => 'Expires',
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
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("templates.$template.blades.admin.pdf.investments", [
                    'investments' => $exportInvestments,
                    'page_title' => $page_title,
                    'columns' => $selectedCols,
                    'orientation' => $orientation
                ]);
                return $pdf->download('investments-report-' . now()->format('Y-m-d-H-i-s') . '.pdf');
            }

            if ($exportType == 'sql') {
                $headers = [
                    'Content-Type' => 'application/sql',
                    'Content-Disposition' => 'attachment; filename="investments-dump-' . now()->format('Y-m-d-H-i-s') . '.sql"',
                ];

                $callback = function () use ($exportInvestments) {
                    $file = fopen('php://output', 'w');
                    fwrite($file, "-- Investments Table Dump\n");
                    fwrite($file, "-- Generated at: " . now() . "\n\n");

                    try {
                        $createTable = \Illuminate\Support\Facades\DB::select("SHOW CREATE TABLE investments")[0]->{'Create Table'};
                        fwrite($file, "DROP TABLE IF EXISTS investments;\n");
                        fwrite($file, $createTable . ";\n\n");
                    } catch (\Exception $e) {
                        fwrite($file, "-- Failed to generate CREATE TABLE statement: " . $e->getMessage() . "\n\n");
                    }

                    foreach ($exportInvestments as $investment) {
                        $attributes = is_object($investment) && method_exists($investment, 'getAttributes') ? $investment->getAttributes() : (array) $investment;
                        $columns = array_keys($attributes);
                        $values = array_map(function ($value) {
                            if (is_null($value))
                                return 'NULL';
                            return "'" . addslashes((string) $value) . "'";
                        }, array_values($attributes));

                        $sql = "INSERT INTO investments (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");\n";
                        fwrite($file, $sql);
                    }
                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
            }

            if ($exportType == 'csv') {
                $headers = [
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=investments-list-" . now()->format('Y-m-d-H-i-s') . ".csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                ];

                $callback = function () use ($exportInvestments, $selectedCols) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, array_values($selectedCols));

                    foreach ($exportInvestments as $investment) {
                        $row = [];
                        foreach (array_keys($selectedCols) as $key) {
                            switch ($key) {
                                case 'username':
                                    $row[] = $investment->user->username;
                                    break;
                                case 'plan_name':
                                    $row[] = $investment->plan->name;
                                    break;
                                case 'status':
                                    $row[] = ucfirst($investment->status);
                                    break;
                                case 'created_at':
                                    $row[] = $investment->created_at->format('Y-m-d H:i:s');
                                    break;
                                case 'next_roi_at':
                                    $row[] = $investment->next_roi_at ? date('Y-m-d H:i:s', $investment->next_roi_at) : 'N/A';
                                    break;
                                case 'expires_at':
                                    $row[] = $investment->expires_at ? date('Y-m-d H:i:s', $investment->expires_at) : 'N/A';
                                    break;
                                case 'auto_reinvest':
                                    $row[] = $investment->auto_reinvest ? 'Yes' : 'No';
                                    break;
                                default:
                                    $row[] = $investment->$key ?? '';
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

        $investments = $query->latest()->paginate(getSetting('pagination', 15));

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
        $investmentStatuses = ['active', 'completed', 'suspended'];

        $investmentRaw = \App\Models\Investment::selectRaw('DATE(created_at) as day, status, SUM(capital_invested) as total')
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

        $graph_data = [];
        foreach ($periods as $p) {
            $graph_data[$p] = [];
            foreach ($investmentStatuses as $status) {
                $graph_data[$p][$status] = $period($investmentMaps[$status], $p);
            }
        }

        // Plan Distribution Data
        $plan_chart_data = \App\Models\Investment::select('investment_plan_id', \DB::raw('SUM(capital_invested) as total_invested'), \DB::raw('SUM(roi_earned) as total_roi'))
            ->with('plan:id,name')
            ->groupBy('investment_plan_id')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->plan ? $item->plan->name : 'Unknown Plan',
                    'invested' => (float) $item->total_invested,
                    'roi' => (float) $item->total_roi
                ];
            });

        return view('templates.bento.blades.admin.investments.index', compact(
            'page_title',
            'investments',
            'stats',
            'graph_data',
            'plan_chart_data'
        ));
    }

    public function edit($id)
    {
        return back();
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,completed,suspended'
        ]);

        $investment = \App\Models\Investment::findOrFail($id);
        $investment->status = $request->status;
        $investment->save();

        return response()->json([
            'success' => true,
            'message' => __('Investment status updated successfully.')
        ]);
    }

    public function delete($id)
    {
        $investment = \App\Models\Investment::findOrFail($id);
        $investment->delete();

        return response()->json([
            'success' => true,
            'message' => __('Investment deleted successfully.')
        ]);
    }
    public function plans()
    {
        $page_title = "Investment Plans";

        $plans = \App\Models\InvestmentPlan::withCount('investments')
            ->orderByDesc('investments_count')
            ->orderByDesc('is_featured')
            ->latest()
            ->get();

        $stats = [
            'total_plans' => $plans->count(),
            'active_plans' => $plans->where('is_enabled', true)->count(),
            'total_investments' => $plans->sum('investments_count'),
            'total_invested' => \App\Models\Investment::sum('capital_invested'),
        ];

        $template = config('site.template');

        return view('templates.' . $template . '.blades.admin.investments.plans', compact('page_title', 'plans', 'stats'));
    }
    public function createPlan()
    {
        $page_title = "Create Investment Plan";
        $template = config('site.template');

        return view('templates.' . $template . '.blades.admin.investments.plans-create', compact('page_title'));
    }
    public function storePlan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:investment_plans,name',
            'description' => 'required|string',
            'min_investment' => 'required|numeric|min:0',
            'max_investment' => 'required|numeric|gt:min_investment',
            'return_percent' => 'required|numeric|min:0',
            'return_interval' => 'required|in:hourly,daily,weekly,monthly,yearly',
            'duration' => 'required|integer|min:1',
            'duration_type' => 'required|in:hours,days,months,years',
            'interests' => 'nullable|array',
            'interests.*' => 'string',
            'risk_profile' => 'required|in:conservative,balanced,growth',
            'investment_goal' => 'required|in:short_term,medium_term,long_term',
            'capital_returned' => 'required|boolean',
            'compounding' => 'required|boolean',
            'is_featured' => 'required|boolean',
            'is_enabled' => 'required|boolean',
        ]);

        $plan = new \App\Models\InvestmentPlan();
        $plan->name = $request->name;
        $plan->description = $request->description;
        $plan->min_investment = $request->min_investment;
        $plan->max_investment = $request->max_investment;
        $plan->return_percent = $request->return_percent;
        $plan->return_interval = $request->return_interval;
        $plan->duration = $request->duration;
        $plan->duration_type = $request->duration_type;
        $plan->interests = $request->interests ?? [];
        $plan->risk_profile = $request->risk_profile;
        $plan->investment_goal = $request->investment_goal;
        $plan->capital_returned = $request->capital_returned;
        $plan->compounding = $request->compounding;
        $plan->is_featured = $request->is_featured;
        $plan->is_enabled = $request->is_enabled;
        $plan->save();

        return response()->json([
            'success' => true,
            'message' => __('Investment plan created successfully.')
        ]);
    }
    public function editPlan($id)
    {
        $plan = \App\Models\InvestmentPlan::findOrFail($id);
        $page_title = __('Edit') . ' ' . $plan->name;

        $template = config('site.template');

        return view('templates.' . $template . '.blades.admin.investments.plan-edit', compact('page_title', 'plan'));
    }
    public function updatePlan(Request $request, $id)
    {
        $plan = \App\Models\InvestmentPlan::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:investment_plans,name,' . $id,
            'description' => 'required|string',
            'min_investment' => 'required|numeric|min:0',
            'max_investment' => 'required|numeric|gt:min_investment',
            'return_percent' => 'required|numeric|min:0',
            'return_interval' => 'required|in:hourly,daily,weekly,monthly,yearly',
            'duration' => 'required|integer|min:1',
            'duration_type' => 'required|in:hours,days,months,years',
            'interests' => 'nullable|array',
            'interests.*' => 'string',
            'risk_profile' => 'required|in:conservative,balanced,growth',
            'investment_goal' => 'required|in:short_term,medium_term,long_term',
            'capital_returned' => 'required|boolean',
            'compounding' => 'required|boolean',
            'is_featured' => 'required|boolean',
            'is_enabled' => 'required|boolean',
        ]);

        $plan->name = $request->name;
        $plan->description = $request->description;
        $plan->min_investment = $request->min_investment;
        $plan->max_investment = $request->max_investment;
        $plan->return_percent = $request->return_percent;
        $plan->return_interval = $request->return_interval;
        $plan->duration = $request->duration;
        $plan->duration_type = $request->duration_type;
        $plan->interests = $request->interests ?? [];
        $plan->risk_profile = $request->risk_profile;
        $plan->investment_goal = $request->investment_goal;
        $plan->capital_returned = $request->capital_returned;
        $plan->compounding = $request->compounding;
        $plan->is_featured = $request->is_featured;
        $plan->is_enabled = $request->is_enabled;
        $plan->save();

        return response()->json([
            'success' => true,
            'message' => __('Investment plan updated successfully.')
        ]);
    }
    public function deletePlan($id)
    {
        $plan = \App\Models\InvestmentPlan::findOrFail($id);

        // Cascade delete all investments tied to this plan
        $plan->investments()->delete();

        $plan->delete();

        return response()->json([
            'success' => true,
            'message' => __('Investment plan and all associated investments deleted successfully.')
        ]);
    }

    public function earnings(Request $request)
    {
        $page_title = "Investment Earnings";

        $filteredQuery = \App\Models\InvestmentEarning::query()->with(['user', 'investment.plan']);

        if ($request->has('search') && $request->search != '') {
            $filteredQuery->search($request->search);
        }

        if ($request->has('date') && $request->date != '') {
            $dates = explode(' to ', $request->date);
            if (count($dates) == 2) {
                $filteredQuery->dateRange(trim($dates[0]), trim($dates[1]));
            }
        }

        // Calculate Stats
        $stats = [
            'total_distributed' => \App\Models\InvestmentEarning::sum('amount'),
            'today' => \App\Models\InvestmentEarning::whereDate('created_at', now()->today())->sum('amount'),
            'this_week' => \App\Models\InvestmentEarning::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('amount'),
            'this_month' => \App\Models\InvestmentEarning::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
            'unique_investors' => \App\Models\InvestmentEarning::distinct('user_id')->count('user_id'),
        ];

        // Handle Exports
        if ($request->has('export')) {
            $exportType = $request->export;
            $exportEarnings = $filteredQuery->orderBy('id', 'desc')->get();
            $template = config('site.template');

            // Dynamic Column Selection
            $requestedCols = $request->get('columns');
            if (is_string($requestedCols)) {
                $requestedCols = array_map('trim', explode(',', $requestedCols));
            } else {
                $requestedCols = (array) ($requestedCols ?? ['created_at', 'username', 'plan_name', 'investment_goal', 'risk_profile', 'amount', 'note']);
            }

            $allAvailableColumns = [
                'created_at' => 'Date',
                'username' => 'User',
                'plan_name' => 'Plan',
                'investment_goal' => 'Goal',
                'risk_profile' => 'Risk',
                'amount' => 'Earning',
                'note' => 'Note'
            ];

            // Only keep requested columns
            $columns = array_intersect_key($allAvailableColumns, array_flip($requestedCols));

            if ($exportType == 'sql') {
                $headers = [
                    "Content-type" => "text/plain",
                    "Content-Disposition" => "attachment; filename=investment-earnings-" . now()->format('Y-m-d-H-i-s') . ".sql",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                ];

                $callback = function () use ($exportEarnings) {
                    $file = fopen('php://output', 'w');
                    foreach ($exportEarnings as $earning) {
                        $attributes = is_object($earning) && method_exists($earning, 'getAttributes') ? $earning->getAttributes() : (array) $earning;
                        $dbColumns = array_keys($attributes);
                        $values = array_map(function ($value) {
                            if (is_null($value))
                                return 'NULL';
                            return "'" . addslashes((string) $value) . "'";
                        }, array_values($attributes));

                        $sql = "INSERT INTO investment_earnings (" . implode(', ', $dbColumns) . ") VALUES (" . implode(', ', $values) . ");\n";
                        fwrite($file, $sql);
                    }
                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
            }

            if ($exportType == 'csv') {
                $headers = [
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=investment-earnings-" . now()->format('Y-m-d-H-i-s') . ".csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                ];

                $callback = function () use ($exportEarnings, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, array_values($columns));

                    foreach ($exportEarnings as $earning) {
                        $row = [
                            $earning->created_at->format('M d, Y H:i:s'),
                            $earning->user ? $earning->user->username : 'Deleted User',
                            $earning->investment && $earning->investment->plan ? $earning->investment->plan->name : 'N/A',
                            $earning->investment_goal ? ucfirst(str_replace('_', ' ', $earning->investment_goal)) : 'N/A',
                            $earning->risk_profile ? ucfirst($earning->risk_profile) : 'N/A',
                            $earning->amount,
                            $earning->note
                        ];
                        fputcsv($file, $row);
                    }
                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
            }

            if ($exportType == 'pdf') {
                $orientation = count($columns) <= 8 ? 'portrait' : 'landscape';
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("templates.$template.blades.admin.pdf.earnings", [
                    'earnings' => $exportEarnings,
                    'page_title' => "Investment Earnings Report",
                    'columns' => $columns,
                    'orientation' => $orientation
                ])->setPaper('a4', $orientation);
                return $pdf->download('earnings-report-' . now()->format('Y-m-d-H-i-s') . '.pdf');
            }
        }

        $earnings = $filteredQuery->orderBy('id', 'desc')->paginate(getSetting('pagination'));

        if ($request->ajax() && !$request->has('export')) {
            return response()->json([
                'html' => view('templates.bento.blades.admin.investments.partials.earnings-table', compact('earnings'))->render(),
                'pagination' => (string) $earnings->links('templates.bento.blades.partials.pagination'),
                'stats' => $stats
            ]);
        }

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

        $earningsRaw = \App\Models\InvestmentEarning::selectRaw('DATE(created_at) as day, SUM(amount) as total')
            ->where('created_at', '>=', $graphWindowStart)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $earningsMap = [];
        foreach ($earningsRaw as $row) {
            $earningsMap[$row->day] = (float) $row->total;
        }

        $graph_data = [];
        foreach ($periods as $p) {
            $graph_data[$p] = [
                'earnings' => $period($earningsMap, $p)
            ];
        }

        // Plan Distribution Data
        $plan_chart_data = \App\Models\InvestmentEarning::join('investments', 'investment_earnings.investment_id', '=', 'investments.id')
            ->join('investment_plans', 'investments.investment_plan_id', '=', 'investment_plans.id')
            ->select('investment_plans.name', \Illuminate\Support\Facades\DB::raw('SUM(investment_earnings.amount) as total_earnings'))
            ->groupBy('investment_plans.name')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->name,
                    'earnings' => (float) $item->total_earnings
                ];
            });

        $template = config('site.template');

        return view('templates.' . $template . '.blades.admin.investments.earnings', compact(
            'page_title',
            'earnings',
            'stats',
            'graph_data',
            'plan_chart_data'
        ));
    }

    public function deleteEarnings($id)
    {
        $earning = \App\Models\InvestmentEarning::findOrFail($id);
        $earning->delete();

        return response()->json([
            'success' => true,
            'message' => __('Earning record deleted successfully.')
        ]);
    }
}
