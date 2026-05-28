<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\InvestmentEarning;
use App\Models\InvestmentPlan;
use Illuminate\Http\Request;

class SectorController extends Controller
{
    // public function index
    public function index(Request $request)
    {
        $route_name = $request->route()->getName();
        // exploade and get the last
        $routes_name_array = explode('.', $route_name);
        $sector = $routes_name_array[array_key_last($routes_name_array)];

        $sectors = array_keys(config('interests'));
        if (!in_array($sector, $sectors)) {
            return redirect()->route('user.dashboard')->with('error', __('Invalid sector'));
        }

        $page_title = __($sector);
        $template = config('site.template');

        $sector_data = config('sectors.' . $sector);

        $dec = 18;
        $scale = (int) getSetting('decimal_places');

        $total_invested = Investment::whereHas('plan', function ($q) use ($sector) {
            $q->whereJsonContains('interests', $sector);
        })
            ->selectRaw("COALESCE(SUM(CAST(capital_invested AS DECIMAL($dec,$scale))),0) as total")
            ->value('total');

        $earnings_generated = InvestmentEarning::where('interest', $sector)
            ->selectRaw("COALESCE(SUM(CAST(amount AS DECIMAL($dec,$scale))),0) as total")
            ->value('total');

        $active_investors = Investment::active()
            ->whereHas('plan', function ($q) use ($sector) {
                $q->whereJsonContains('interests', $sector);
            })
            ->distinct('user_id')
            ->count('user_id');

        $metrics = [
            'total_invested' => $total_invested,
            'earnings_generated' => $earnings_generated,
            'active_investors' => $active_investors,
        ];

        $sector_data['metrics'] = $metrics;


        // get recommended plans
        $user = auth()->user();
        $onboarding = $user->onboarding;

        $riskProfile = $onboarding->risk_profile ?? null;
        $investmentGoal = $onboarding->investment_goal ?? null;
        $query = InvestmentPlan::active()
            ->whereJsonContains('interests', $sector);

        if ($riskProfile && $investmentGoal) {
            $query->orderByRaw("
                (risk_profile = ?) DESC,
                (investment_goal = ?) DESC,
                is_featured DESC,
                created_at DESC
            ", [$riskProfile, $investmentGoal]);
        } else {
            // fallback ordering
            $query->orderByDesc('is_featured')
                ->orderByDesc('created_at');
        }

        $recommended_plans = $query->get();

        return view('templates.' . $template . '.blades.user.sector', compact(
            'page_title',
            'sector',
            'sector_data',
            'recommended_plans'
        ));
    }
}
