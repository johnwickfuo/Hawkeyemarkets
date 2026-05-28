<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $template = config('site.template');
        $page_title = __('My Transactions');

        // Base Query
        $statsQuery = Transaction::where('user_id', $user->id);

        // 1) Money Flow Summary
        $moneyFlow = (clone $statsQuery)->selectRaw("
            SUM(CASE WHEN type = 'credit' AND status = 'completed' THEN amount ELSE 0 END) as total_credits,
            SUM(CASE WHEN type = 'debit' AND status = 'completed' THEN amount ELSE 0 END) as total_debits
        ")->first();

        // Net Cash Flow
        $netFlow = $moneyFlow->total_credits - $moneyFlow->total_debits;

        // Current Balance (User's actual balance is the source of truth)
        $currentBalance = $user->balance;

        // 2) Transaction Activity (Time-based counts)
        $today = now()->startOfDay();
        $startOfWeek = now()->startOfWeek();
        $startOfMonth = now()->startOfMonth();

        $activity = (clone $statsQuery)->selectRaw("
            COUNT(CASE WHEN created_at >= ? THEN 1 END) as today_count,
            COUNT(CASE WHEN created_at >= ? THEN 1 END) as week_count,
            COUNT(CASE WHEN created_at >= ? THEN 1 END) as month_count,
            COUNT(*) as total_count,
            MIN(created_at) as first_transaction_date
        ", [$today, $startOfWeek, $startOfMonth])->first();

        // Avg transactions per day (over 30 days or total active days)
        $daysActive = $activity->first_transaction_date ? now()->diffInDays($activity->first_transaction_date) : 1;
        $daysActive = max($daysActive, 1);
        $avgTransactionsPerDay = $activity->total_count / $daysActive;

        // Insight for activity
        // Simple logic: Compare this week vs last week (simplified here to just stating the stat)
        $activityInsight = __('You have made :count transactions this week.', ['count' => $activity->week_count]);


        // 3) Credit vs Debit Ratio
        $totalTx = $activity->total_count > 0 ? $activity->total_count : 1;
        $creditCount = (clone $statsQuery)->where('type', 'credit')->count();
        $debitCount = (clone $statsQuery)->where('type', 'debit')->count();

        $creditRatio = ($creditCount / $totalTx) * 100;
        $debitRatio = ($debitCount / $totalTx) * 100;

        $ratioInsight = $debitCount > $creditCount
            ? __('You withdraw more than you deposit recently.')
            : __('You deposit more than you withdraw recently.');


        // 4) Pending & Failed Monitoring
        $statusCounts = (clone $statsQuery)->selectRaw("
            COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_count,
            COUNT(CASE WHEN status = 'failed' OR status = 'rejected' THEN 1 END) as failed_count,
             COUNT(CASE WHEN status = 'completed' THEN 1 END) as success_count
        ")->first();

        $operationalInsight = $statusCounts->failed_count > 0
            ? __('You have :count failed transactions.', ['count' => $statusCounts->failed_count])
            : __('Your operational health is good.');


        // 5) Currency Usage Analytics
        $topCurrency = (clone $statsQuery)->select('currency')
            ->selectRaw('count(*) as count')
            ->groupBy('currency')
            ->orderByDesc('count')
            ->first();

        $topConverted = (clone $statsQuery)->select('converted_currency')
            ->selectRaw('count(*) as count')
            ->groupBy('converted_currency')
            ->orderByDesc('count')
            ->first();

        $mostUsedCurrency = $topCurrency ? $topCurrency->currency : 'N/A';
        $mostConvertedCurrency = $topConverted ? $topConverted->converted_currency : 'N/A';

        $currencyInsight = $topConverted
            ? __('Most transactions are converted to :currency.', ['currency' => $mostConvertedCurrency])
            : __('No conversion data available.');


        // 6) Average Transaction Size
        $sizeStats = (clone $statsQuery)->where('status', 'completed')->selectRaw("
            AVG(CASE WHEN type = 'credit' THEN amount END) as avg_credit,
            AVG(CASE WHEN type = 'debit' THEN amount END) as avg_debit,
            MAX(amount) as max_tx,
            MIN(amount) as min_tx
        ")->first();

        $sizeInsight = $sizeStats->avg_credit > 0
            ? __('Your average deposit is :amount.', ['amount' => number_format($sizeStats->avg_credit, 2)])
            : __('No deposit data.');


        // 7) Balance Trend Analytics (Last 30 transactions for chart)
        // We need to fetch meaningful data points. 
        $trendData = (clone $statsQuery)->whereNotNull('new_balance')
            ->latest()
            ->take(30)
            ->get(['created_at', 'new_balance'])
            ->reverse()
            ->values();

        $highestBalance = $trendData->max('new_balance') ?? 0;
        $lowestBalance = $trendData->min('new_balance') ?? 0;


        // 8) Transaction Success Rate
        $successRate = ($statusCounts->success_count / $totalTx) * 100;
        $failureRate = ($statusCounts->failed_count / $totalTx) * 100;


        // 9) Transaction Table (Filteable)
        $query = Transaction::where('user_id', $user->id);

        if ($request->has('type') && $request->type != 'all') {
            $query->where('type', $request->type);
        }

        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('reference', 'like', "%$term%")
                    ->orWhere('description', 'like', "%$term%");
            });
        }

        // Sorting
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        // Export Handling
        if ($request->has('export')) {
            $exportType = $request->export;
            $exportTransactions = $query->get(); // Get all for export

            if ($exportType == 'pdf') {
                $pdf = Pdf::loadView("templates.$template.blades.pdf.transactions", [
                    'user' => $user,
                    'transactions' => $exportTransactions
                ]);
                return $pdf->download('transaction-history-' . now()->format('Y-m-d-h-i-s') . '.pdf');
            }

            if ($exportType == 'csv') {
                $headers = [
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=transaction-history-" . now()->format('Y-m-d-h-i-s') . ".csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                ];

                $callback = function () use ($exportTransactions) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, ['Date', 'Reference', 'Description', 'Type', 'Amount', 'Currency', 'Status', 'Balance']);

                    foreach ($exportTransactions as $tx) {
                        fputcsv($file, [
                            $tx->created_at->format('Y-m-d H:i:s'),
                            $tx->reference,
                            $tx->description,
                            $tx->type,
                            $tx->amount,
                            $tx->currency,
                            $tx->status,
                            $tx->new_balance
                        ]);
                    }
                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
            }
        }

        $transactions = $query->paginate(getSetting('pagination', 15));


        // Check if AJAX request for table only
        if ($request->ajax()) {
            return view("templates.$template.blades.user.transactions", compact(
                'page_title',
                'user',
                'transactions',
                'moneyFlow',
                'netFlow',
                'currentBalance',
                'activity',
                'avgTransactionsPerDay',
                'activityInsight',
                'creditRatio',
                'debitRatio',
                'ratioInsight',
                'statusCounts',
                'operationalInsight',
                'mostUsedCurrency',
                'mostConvertedCurrency',
                'currencyInsight',
                'sizeStats',
                'sizeInsight',
                'trendData',
                'highestBalance',
                'lowestBalance',
                'successRate',
                'failureRate'
            ));
        }

        return view("templates.$template.blades.user.transactions", compact(
            'page_title',
            'user',
            'transactions',
            'moneyFlow',
            'netFlow',
            'currentBalance',
            'activity',
            'avgTransactionsPerDay',
            'activityInsight',
            'creditRatio',
            'debitRatio',
            'ratioInsight',
            'statusCounts',
            'operationalInsight',
            'mostUsedCurrency',
            'mostConvertedCurrency',
            'currencyInsight',
            'sizeStats',
            'sizeInsight',
            'trendData',
            'highestBalance',
            'lowestBalance',
            'successRate',
            'failureRate'
        ));
    }
}
