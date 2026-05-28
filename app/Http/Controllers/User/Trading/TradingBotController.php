<?php

namespace App\Http\Controllers\User\Trading;

use App\Http\Controllers\Controller;
use App\Models\TradingBot;
use App\Models\TradingBotActivation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TradingBotController extends Controller
{
    public function __construct()
    {
        if (!moduleEnabled('trading_bot_module')) {
            abort(404);
        }
    }

    /**
     * Display a listing of available trading bots for the user.
     */
    public function index()
    {
        $page_title = __('AI Trading Bots');
        $bots = TradingBot::active()->latest()->paginate(getSetting('pagination', 15));
        $total_activations = TradingBotActivation::where('user_id', Auth::id())->count();

        return view('templates.' . config('site.template') . '.blades.user.trading.bots.index', compact('page_title', 'bots', 'total_activations'));
    }

    /**
     * Process the activation of a trading bot.
     */
    public function activate(Request $request)
    {
        $request->validate([
            'bot_id' => 'required|exists:trading_bots,id',
            'amount' => 'required|numeric|min:0',
        ]);

        $bot = TradingBot::findOrFail($request->bot_id);
        $user = Auth::user();

        if (!$bot->is_active) {
            return response()->json(['success' => false, 'message' => __('This bot is currently unavailable.')], 400);
        }

        if ($request->amount < $bot->min_amount || $request->amount > $bot->max_amount) {
            return response()->json([
                'success' => false,
                'message' => __("Amount must be between :min and :max", ['min' => showAmount($bot->min_amount), 'max' => showAmount($bot->max_amount)])
            ], 400);
        }

        if ($user->balance < $request->amount) {
            return response()->json(['success' => false, 'message' => __('Insufficient balance.')], 400);
        }

        DB::beginTransaction();
        try {
            // Deduct balance
            $user->balance -= $request->amount;
            $user->save();

            $amount = $request->amount;
            $currency = getSetting('currency');
            $ref = \Str::orderedUuid();
            $description = __('Trading bot activation');

            recordTransaction($user, $amount, $currency, $amount, $currency, 1, 'debit', 'completed', $ref, $description, $user->balance);
            $title = 'Trading Bot Activation';
            $body = __('You have successfully activated :bot_name with :amount capital', ['bot_name' => $bot->name, 'amount' => showAmount($amount)]);
            recordNotificationMessage($user, $title, $body);

            // Create activation
            $startDate = now()->timestamp;
            $endDate = 0;

            // Calculate end date based on duration
            switch ($bot->duration_type) {
                case 'hour':
                    $endDate = now()->addHours($bot->duration)->timestamp;
                    break;
                case 'day':
                    $endDate = now()->addDays($bot->duration)->timestamp;
                    break;
                case 'week':
                    $endDate = now()->addWeeks($bot->duration)->timestamp;
                    break;
                case 'month':
                    $endDate = now()->addMonths($bot->duration)->timestamp;
                    break;
                case 'year':
                    $endDate = now()->addYears($bot->duration)->timestamp;
                    break;
            }

            $today_cycle_reset_at = now()->subDay()->startOfDay()->timestamp;



            $activation = TradingBotActivation::create([
                'user_id' => $user->id,
                'trading_bot_id' => $bot->id,
                'amount' => $request->amount,
                'status' => 'active',
                'start_date' => $startDate,
                'end_date' => $endDate,
                'today_cycle_reset_at' => $today_cycle_reset_at,
            ]);


            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('Trading bot activated successfully!'),
                'redirect' => route('user.trading-bots.activations')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => __('Failed to activate bot. Please try again.')], 500);
        }
    }

    /**
     * Display a listing of the user's trading bot activations.
     */
    public function activations()
    {
        $page_title = __('My Trading Bot Activations');
        $activations = TradingBotActivation::with('bot')
            ->withCount('logs')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(getSetting('pagination', 15));

        return view('templates.' . config('site.template') . '.blades.user.trading.bots.activations', compact('page_title', 'activations'));
    }

    /**
     * Display a listing of the user's trading bot logs with performance charts.
     */
    public function logs(Request $request)
    {
        $page_title = __('Performance & Trading Logs');
        $user = Auth::user();

        // 1. Aggregated Stats
        $stats = [
            'total_profit' => \App\Models\TradingBotLog::where('user_id', $user->id)->sum('profit'),
            'today_profit' => \App\Models\TradingBotLog::where('user_id', $user->id)
                ->where('created_at', '>=', now()->startOfDay())
                ->sum('profit'),
            'active_bots' => TradingBotActivation::where('user_id', $user->id)->where('status', 'active')->count(),
            'total_trades' => \App\Models\TradingBotLog::where('user_id', $user->id)->count(),
        ];

        // 2. Earnings Trend Chart (Interval specialized with gap filling)
        $days = $request->interval == '30d' ? 30 : ($request->interval == '90d' ? 90 : 7);
        $startDate = now()->subDays($days)->startOfDay();

        $trendData = \App\Models\TradingBotLog::where('user_id', $user->id)
            ->where('created_at', '>=', $startDate)
            ->select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(profit) as total_profit')
            ])
            ->groupBy('date')
            ->pluck('total_profit', 'date')
            ->toArray();

        // Fill gaps with 0
        $labels = [];
        $data = [];
        for ($i = $days; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('M d');
            $data[] = (float) ($trendData[$date] ?? 0);
        }

        $chart_trend = [
            'labels' => $labels,
            'data' => $data,
        ];

        if ($request->ajax() && $request->type == 'chart') {
            return response()->json($chart_trend);
        }

        $currency = [
            'code' => getSetting('currency'),
            'symbol' => getSetting('currency_symbol', '$'),
        ];

        // 3. Asset Distribution (Donut Chart)
        $distributionData = \App\Models\TradingBotLog::where('user_id', $user->id)
            ->select([
                'trading_pair',
                DB::raw('SUM(profit) as total_profit')
            ])
            ->groupBy('trading_pair')
            ->having('total_profit', '>', 0)
            ->orderBy('total_profit', 'DESC')
            ->limit(5)
            ->get();

        $chart_distribution = [
            'labels' => $distributionData->pluck('trading_pair'),
            'data' => $distributionData->pluck('total_profit')->map(fn($profit) => (float) $profit),
        ];

        // 4. Paginated Logs (AJAX supported)
        $query = \App\Models\TradingBotLog::with('activation.bot')
            ->where('user_id', $user->id);

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('trading_pair', 'like', "%$search%")
                    ->orWhere('exchange', 'like', "%$search%")
                    ->orWhere('direction', 'like', "%$search%");
            });
        }

        $logs = $query->latest()->paginate(getSetting('pagination', 15));

        if ($request->ajax()) {
            return view('templates.' . config('site.template') . '.blades.user.trading.bots.partials.logs_table', compact('logs'))->render();
        }

        return view('templates.' . config('site.template') . '.blades.user.trading.bots.logs', compact(
            'page_title',
            'stats',
            'chart_trend',
            'chart_distribution',
            'logs',
            'currency'
        ));
    }

    /**
     * Display a daily summary of trading performance with creative cards.
     */
    public function dailySummary(Request $request)
    {
        $page_title = __('Daily Performance Summary');
        $user = Auth::user();

        // 1. Global Stats (Always same)
        $total_profit = \App\Models\TradingBotLog::where('user_id', $user->id)->sum('profit');
        $daily_logs_count = \App\Models\TradingBotLog::where('user_id', $user->id)
            ->select(DB::raw('DATE(created_at) as date'))
            ->groupBy('date')
            ->get()
            ->count();

        $stats = [
            'total_profit' => $total_profit,
            'total_trades' => \App\Models\TradingBotLog::where('user_id', $user->id)->count(),
            'total_activations' => TradingBotActivation::where('user_id', $user->id)->count(),
            'avg_daily_profit' => $daily_logs_count > 0 ? $total_profit / $daily_logs_count : 0,
        ];

        // 2. Profit Distributions (Donuts - Always same)
        $pair_distribution = \App\Models\TradingBotLog::where('user_id', $user->id)
            ->select('trading_pair as label', DB::raw('SUM(profit) as value'))
            ->groupBy('label')
            ->having('value', '>', 0)
            ->orderBy('value', 'desc')
            ->get()
            ->map(function ($item) {
                $item->value = (float) $item->value;
                return $item;
            });

        $exchange_distribution = \App\Models\TradingBotLog::where('user_id', $user->id)
            ->select(DB::raw('COALESCE(exchange, "' . config('app.name') . '") as label'), DB::raw('SUM(profit) as value'))
            ->groupBy('label')
            ->having('value', '>', 0)
            ->orderBy('value', 'desc')
            ->get()
            ->map(function ($item) {
                $item->value = (float) $item->value;
                return $item;
            });

        $type_distribution = \App\Models\TradingBotLog::where('user_id', $user->id)
            ->select('type as label', DB::raw('SUM(profit) as value'))
            ->groupBy('label')
            ->having('value', '>', 0)
            ->orderBy('value', 'desc')
            ->get()
            ->map(function ($item) {
                $item->value = (float) $item->value;
                return $item;
            });

        // 3. Daily Summary Logic (Optimized & Filterable)
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $dailyLogs = \App\Models\TradingBotLog::where('user_id', $user->id)
            ->select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(profit) as daily_profit'),
                DB::raw('SUM(profit_percentage) as daily_profit_percentage'),
                DB::raw('COUNT(*) as trade_count'),
            ])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $cumulativeProfit = 0;
        $cumulativePercentage = 0;
        $allDaysData = [];

        foreach ($dailyLogs as $day) {
            $cumulativeProfit += $day->daily_profit;
            $cumulativePercentage += $day->daily_profit_percentage;

            $allDaysData[] = (object) [
                'date' => $day->date,
                'daily_profit' => (float) $day->daily_profit,
                'daily_profit_percentage' => (float) $day->daily_profit_percentage,
                'trade_count' => $day->trade_count,
                'cumulative_profit' => $cumulativeProfit,
                'cumulative_profit_percentage' => $cumulativePercentage,
            ];
        }

        // Apply Date Filters
        if ($startDate) {
            $allDaysData = array_filter($allDaysData, fn($d) => $d->date >= $startDate);
        }
        if ($endDate) {
            $allDaysData = array_filter($allDaysData, fn($d) => $d->date <= $endDate);
        }

        // Newest First
        $filteredDays = array_reverse($allDaysData);

        // Pagination
        $perPage = 30;
        $page = $request->input('page', 1);
        $offset = ($page - 1) * $perPage;
        $pageItems = array_slice($filteredDays, $offset, $perPage);

        // Bulk Fetch Best Pairs for current page only (High Performance)
        $pageDates = array_column($pageItems, 'date');
        $bestPairs = \App\Models\TradingBotLog::where('user_id', $user->id)
            ->whereIn(DB::raw('DATE(created_at)'), $pageDates)
            ->select('trading_pair', DB::raw('DATE(created_at) as date'), DB::raw('SUM(profit) as total_profit'))
            ->groupBy('date', 'trading_pair')
            ->get()
            ->groupBy('date');

        foreach ($pageItems as $item) {
            $dayPairs = $bestPairs->get($item->date);
            $item->best_pair = $dayPairs ? $dayPairs->sortByDesc('total_profit')->first()->trading_pair : 'N/A';
        }

        $summaries = new \Illuminate\Pagination\LengthAwarePaginator(
            $pageItems,
            count($filteredDays),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        if ($request->ajax()) {
            return view('templates.' . config('site.template') . '.blades.user.trading.bots.partials.daily_cards', compact('summaries'))->render();
        }

        $currency = [
            'code' => getSetting('currency'),
            'symbol' => getSetting('currency_symbol', '$'),
        ];

        return view('templates.' . config('site.template') . '.blades.user.trading.bots.daily_summary', compact(
            'page_title',
            'stats',
            'pair_distribution',
            'exchange_distribution',
            'type_distribution',
            'summaries',
            'currency'
        ));
    }
}
