<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\InvestmentEarning;
use App\Models\InvestmentPlan;
use Illuminate\Http\Request;
use function Illuminate\Support\now;
use Illuminate\Support\Facades\DB;

class InvestmentController extends Controller
{

    // check if investment module is loaded
    public function __construct()
    {
        if (!moduleEnabled('investment_module')) {
            abort(404);
        }
    }

    //index

    public function index()
    {
        $template = config('site.template');
        $page_title = __('My Investments');
        $user_id = auth()->id();

        // Eager load plan to avoid N+1 in the view
        $my_investments = Investment::with('plan')
            ->where('user_id', $user_id)
            ->orderByDesc('id')
            ->get();

        $investment_earnings = InvestmentEarning::where('user_id', $user_id)
            ->orderByDesc('id')
            ->limit(10)->get();

        $now = now();
        $today = $now->copy()->startOfDay();
        $last7 = $now->copy()->subDays(7);
        $last30 = $now->copy()->subDays(30);


        $dec = 18;
        $scale = (int) getSetting('decimal_places', 2);

        $sumCapital = fn($q) => $q->selectRaw("COALESCE(SUM(CAST(capital_invested AS DECIMAL($dec,$scale))),0) as v")->value('v');
        $sumRoi = fn($q) => $q->selectRaw("COALESCE(SUM(CAST(roi_earned AS DECIMAL($dec,$scale))),0) as v")->value('v');
        $sumEarn = fn($q) => $q->selectRaw("COALESCE(SUM(CAST(amount AS DECIMAL($dec,$scale))),0) as v")->value('v');

        // Overview
        $total_investments = Investment::where('user_id', $user_id)->count();
        $active_investments = Investment::where('user_id', $user_id)->where('status', 'active')->count();

        $total_capital = $sumCapital(Investment::where('user_id', $user_id));
        $active_capital = $sumCapital(Investment::where('user_id', $user_id)->where('status', 'active'));

        $total_roi_earned = $sumRoi(Investment::where('user_id', $user_id));
        $total_earnings_credited = $sumEarn(InvestmentEarning::where('user_id', $user_id));

        // Time performance
        $earnings_today = $sumEarn(InvestmentEarning::where('user_id', $user_id)->where('created_at', '>=', $today));
        $earnings_7d = $sumEarn(InvestmentEarning::where('user_id', $user_id)->where('created_at', '>=', $last7));
        $earnings_30d = $sumEarn(InvestmentEarning::where('user_id', $user_id)->where('created_at', '>=', $last30));

        // Earnings trend (last 14 days)
        $earnings_trend_14d = InvestmentEarning::where('user_id', $user_id)
            ->where('created_at', '>=', $now->copy()->subDays(14))
            ->selectRaw("DATE(created_at) as date")
            ->selectRaw("SUM(CAST(amount AS DECIMAL($dec,$scale))) as total")
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Breakdowns
        $earnings_by_interest = InvestmentEarning::where('user_id', $user_id)
            ->select('interest')
            ->selectRaw("SUM(CAST(amount AS DECIMAL($dec,$scale))) as total")
            ->groupBy('interest')
            ->orderByDesc('total')
            ->get();

        $earnings_by_risk = InvestmentEarning::where('user_id', $user_id)
            ->select('risk_profile')
            ->selectRaw("SUM(CAST(amount AS DECIMAL($dec,$scale))) as total")
            ->groupBy('risk_profile')
            ->orderByDesc('total')
            ->get();

        $earnings_by_goal = InvestmentEarning::where('user_id', $user_id)
            ->select('investment_goal')
            ->selectRaw("SUM(CAST(amount AS DECIMAL($dec,$scale))) as total")
            ->groupBy('investment_goal')
            ->orderByDesc('total')
            ->get();

        // Operational
        $next_roi_count = Investment::where('user_id', $user_id)
            ->where('status', 'active')
            ->whereNotNull('next_roi_at')
            ->count();

        $next_roi_soonest = Investment::where('user_id', $user_id)
            ->where('status', 'active')
            ->whereNotNull('next_roi_at')
            ->min('next_roi_at'); // unix timestamp

        $expiring_7d = Investment::where('user_id', $user_id)
            ->where('status', 'active')
            ->where('expires_at', '<=', $now->copy()->addDays(7)->timestamp)
            ->count();

        $avg_cycle_progress = Investment::where('user_id', $user_id)
            ->where('total_cycles', '>', 0)
            ->selectRaw("AVG(cycle_count / total_cycles) as progress")
            ->value('progress');

        $analytics = compact(
            'total_investments',
            'active_investments',
            'total_capital',
            'active_capital',
            'total_roi_earned',
            'total_earnings_credited',
            'earnings_today',
            'earnings_7d',
            'earnings_30d',
            'earnings_trend_14d',
            'earnings_by_interest',
            'earnings_by_risk',
            'earnings_by_goal',
            'next_roi_count',
            'next_roi_soonest',
            'expiring_7d',
            'avg_cycle_progress',
        );

        return view("templates.$template.blades.user.investments.index", compact(
            'page_title',
            'my_investments',
            'investment_earnings',
            'analytics'
        ));
    }



    // new investment
    public function newInvestment()
    {
        $template = config('site.template');
        $page_title = __('New Investment');

        $investment_plans = InvestmentPlan::active()->get();
        $recommended_plans = InvestmentPlan::recommended()->limit(3)->get();

        return view("templates.$template.blades.user.investments.new", compact(
            'page_title',
            'investment_plans',
            'recommended_plans'
        ));
    }


    // create investment
    public function newInvestmentValidation(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:investment_plans,id',
            'amount' => 'required|numeric',
        ]);

        $user = auth()->user();

        // active investment plan,  
        $plan = InvestmentPlan::active()->find($request->plan_id);
        if (!$plan) {
            return response()->json([
                'status' => 'error',
                'message' => __('Investment plan not found')
            ]);
        }

        // check if user has enough balance
        if ($user->balance < $request->amount) {
            return response()->json([
                'status' => 'error',
                'message' => __('Insufficient balance')
            ]);
        }

        $currency = getSetting('currency');

        // amount must not be greater than plan max and not less than plan min
        if ($request->amount > $plan->max_investment || $request->amount < $plan->min_investment) {
            return response()->json([
                'status' => 'error',
                'message' => __('Amount must be between :min :currency and :max :currency', [
                    'min' => $plan->min_investment,
                    'max' => $plan->max_investment,
                    'currency' => $currency
                ])
            ]);
        }


        $user->refresh();
        // deduct balance
        $user->balance -= $request->amount;
        $user->save();

        // calculate expires_at based on the plan duration and duration type
        // ['hours', 'days', 'weeks', 'months', 'years'] //duration type
        //['hourly', 'daily', 'weekly', 'monthly', 'yearly'] //return intervals
        $duration_type = ucfirst($plan->duration_type);
        $date_function = "add$duration_type";
        $expires_at = now()->$date_function($plan->duration)->timestamp;

        $next_roi_at = getNextReturnTime($plan);
        $total_cycles = calculateTotalCycles($plan);


        //record investment plan activation
        $investment = new Investment();
        $investment->user_id = $user->id;
        $investment->investment_plan_id = $plan->id;
        $investment->capital_invested = $request->amount;
        $investment->compounding_capital = $request->amount;
        // $investment->auto_reinvest = $request->auto_reinvest;
        $investment->roi_earned = 0;
        $investment->next_roi_at = $next_roi_at->timestamp;
        $investment->expires_at = $expires_at;
        $investment->total_cycles = $total_cycles;
        $investment->cycle_count = 0;
        $investment->status = 'active';
        $investment->save();

        // record new transaction
        $ref = \Str::random(12);
        $amount = $request->amount;
        recordTransaction($user, $amount, $currency, $amount, $currency, 1, 'debit', 'completed', $ref, 'Investment Plan Activation', $user->balance);

        // record new notification
        $title = "Investment Plan Activation";
        $body = __("You have activated :plan_name plan. Amount: :amount :currency", [
            'plan_name' => $plan->name,
            'amount' => $amount,
            'currency' => $currency
        ]);
        recordNotificationMessage($user, $title, $body);

        // send new investment email
        $custom_subject = "Investment Plan Activation";
        $custom_message = "Great news. Your investment plan has been successfully activated and is now live on your account.";
        sendInvestmentEmail($custom_subject, $custom_message, $investment);


        return response()->json([
            'status' => 'success',
            'message' => __('Investment created successfully'),
            'redirect' => route('user.investments.index')
        ]);
    }


    // Earning history

    public function investmentEarnings()
    {
        $template = config('site.template');
        $page_title = __('Investment Earnings');
        $user_id = auth()->id();

        $investment_earnings = InvestmentEarning::where('user_id', $user_id)
            ->orderByDesc('id')
            ->paginate(getSetting('pagination'))->onEachSide(1);

        $now = now();
        $today = $now->copy()->startOfDay();

        $last7_start = $now->copy()->subDays(7);
        $prev7_start = $now->copy()->subDays(14);
        $prev7_end = $now->copy()->subDays(7);

        $last30_start = $now->copy()->subDays(30);
        $prev30_start = $now->copy()->subDays(60);
        $prev30_end = $now->copy()->subDays(30);

        // Your amount column is string in migration, so cast during SUM.
        $dec = 18;
        $scale = (int) config('site.decimal_places', 2);
        $sumAmount = fn($q) => (float) $q->selectRaw("COALESCE(SUM(CAST(amount AS DECIMAL($dec,$scale))),0) as v")->value('v');

        // Core totals
        $total_earned = $sumAmount(InvestmentEarning::where('user_id', $user_id));
        $earned_today = $sumAmount(InvestmentEarning::where('user_id', $user_id)->where('created_at', '>=', $today));

        // 7d / 30d windows
        $earned_7d = $sumAmount(InvestmentEarning::where('user_id', $user_id)->where('created_at', '>=', $last7_start));
        $earned_prev_7d = $sumAmount(
            InvestmentEarning::where('user_id', $user_id)
                ->where('created_at', '>=', $prev7_start)
                ->where('created_at', '<', $prev7_end)
        );

        $earned_30d = $sumAmount(InvestmentEarning::where('user_id', $user_id)->where('created_at', '>=', $last30_start));
        $earned_prev_30d = $sumAmount(
            InvestmentEarning::where('user_id', $user_id)
                ->where('created_at', '>=', $prev30_start)
                ->where('created_at', '<', $prev30_end)
        );

        $pctChange = function (float $current, float $previous): float {
            if ($previous <= 0)
                return $current > 0 ? 100.0 : 0.0;
            return round((($current - $previous) / $previous) * 100, 2);
        };

        $change_7d = $pctChange($earned_7d, $earned_prev_7d);
        $change_30d = $pctChange($earned_30d, $earned_prev_30d);

        $avg_daily_7d = round($earned_7d / 7, 2);
        $avg_daily_30d = round($earned_30d / 30, 2);

        // Earnings trend (last 14 days)
        $trend_14d = InvestmentEarning::where('user_id', $user_id)
            ->where('created_at', '>=', $now->copy()->subDays(14))
            ->selectRaw("DATE(created_at) as date")
            ->selectRaw("SUM(CAST(amount AS DECIMAL($dec,$scale))) as total")
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Breakdowns
        $by_interest = InvestmentEarning::where('user_id', $user_id)
            ->select('interest')
            ->selectRaw("SUM(CAST(amount AS DECIMAL($dec,$scale))) as total")
            ->groupBy('interest')
            ->orderByDesc('total')
            ->get();

        $by_risk = InvestmentEarning::where('user_id', $user_id)
            ->select('risk_profile')
            ->selectRaw("SUM(CAST(amount AS DECIMAL($dec,$scale))) as total")
            ->groupBy('risk_profile')
            ->orderByDesc('total')
            ->get();

        $by_goal = InvestmentEarning::where('user_id', $user_id)
            ->select('investment_goal')
            ->selectRaw("SUM(CAST(amount AS DECIMAL($dec,$scale))) as total")
            ->groupBy('investment_goal')
            ->orderByDesc('total')
            ->get();

        // Consistency: days with earnings in last 30 days
        $days_with_earnings_30d = InvestmentEarning::where('user_id', $user_id)
            ->where('created_at', '>=', $last30_start)
            ->selectRaw("COUNT(DISTINCT DATE(created_at)) as d")
            ->value('d');

        // Credits count last 30 days
        $credits_30d = InvestmentEarning::where('user_id', $user_id)
            ->where('created_at', '>=', $last30_start)
            ->count();

        $avg_credits_per_day_30d = round($credits_30d / 30, 2);

        // Best day of week (0=Sun in MySQL; depends on DB). We'll compute in DB for speed.
        $best_day = InvestmentEarning::where('user_id', $user_id)
            ->where('created_at', '>=', $prev30_start) // use wider range for better signal
            ->selectRaw("DAYOFWEEK(created_at) as dow")
            ->selectRaw("SUM(CAST(amount AS DECIMAL($dec,$scale))) as total")
            ->groupBy('dow')
            ->orderByDesc('total')
            ->first();

        $dow_map = [
            1 => 'Sunday',
            2 => 'Monday',
            3 => 'Tuesday',
            4 => 'Wednesday',
            5 => 'Thursday',
            6 => 'Friday',
            7 => 'Saturday',
        ];
        $best_day_name = $best_day ? ($dow_map[$best_day->dow] ?? null) : null;

        // Simple insight text
        $top_interest = $by_interest->first();
        $top_interest_text = $top_interest ? $top_interest->interest : null;

        $insights = [];
        $insights[] = [
            'index' => 1,
            'type' => $change_7d >= 0 ? 'trend_up' : 'trend_down',
            'text' => $change_7d >= 0
                ? (__('Your earnings are up :change% compared to the previous 7 days.', ['change' => $change_7d]))
                : (__('Your earnings are down :change% compared to the previous 7 days.', ['change' => abs($change_7d)]))
        ];

        if ($top_interest_text) {
            $insights[] = [
                'index' => 2,
                'type' => 'source',
                'text' => __("Most of your earnings come from :top_interest_text.", ['top_interest_text' => __($top_interest_text)])
            ];
        }

        if ($days_with_earnings_30d !== null) {
            $insights[] = [
                'index' => 3,
                'type' => 'frequency',
                'text' => __("You earned on :days_with_earnings_30d of the last 30 days.", ['days_with_earnings_30d' => $days_with_earnings_30d])
            ];
        }

        if ($best_day_name) {
            $insights[] = [
                'index' => 4,
                'type' => 'best_day',
                'text' => __("Your strongest earning day is typically :best_day_name.", ['best_day_name' => __($best_day_name)])
            ];
        }

        $duplicated_insights = [];
        $number_of_duplications = 2;
        $i = 0;
        while ($i < $number_of_duplications) {
            foreach ($insights as $insight) {
                $duplicated_insights[] = $insight;
            }
            $i++;
        }


        $insights = $duplicated_insights;

        $analytics = compact(
            'total_earned',
            'earned_today',
            'earned_7d',
            'earned_30d',
            'earned_prev_7d',
            'earned_prev_30d',
            'change_7d',
            'change_30d',
            'avg_daily_7d',
            'avg_daily_30d',
            'trend_14d',
            'by_interest',
            'by_risk',
            'by_goal',
            'days_with_earnings_30d',
            'credits_30d',
            'avg_credits_per_day_30d',
            'best_day_name',
            'insights'
        );

        return view("templates.$template.blades.user.investments.earnings", compact(
            'page_title',
            'investment_earnings',
            'analytics'
        ));
    }

}
