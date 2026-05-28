<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReferralNetworkController extends Controller
{
    public function index(Request $request)
    {
        $page_title = __('Referral Network');

        // Global Stats
        $total_referrals = \App\Models\User::whereNotNull('referrer_id')->count();
        $total_commissions = \App\Models\Transaction::where('type', 'credit')
            ->where('description', 'like', '%Referral Bonus%')
            ->sum('amount');

        // Referral bonus levels
        $referral_bonus_percentage = json_decode(getSetting('referral_bonus', json_encode([0, 0])), true);
        $total_levels = count($referral_bonus_percentage);

        // -- Multi-Level Top Referrers Logic --
        // Instead of sorting purely by direct referrals, we compute the recursive size for all eligible top affiliates.
        // For performance, we pre-filter users who actually have direct referrals.
        $affiliates = \App\Models\User::has('referrals')->with('referrals')->get();

        $top_referrers = collect();
        foreach ($affiliates as $affiliate) {
            $stats = $this->getNetworkStats($affiliate, $total_levels);
            $top_referrers->push((object) [
                'id' => $affiliate->id,
                'first_name' => $affiliate->first_name,
                'last_name' => $affiliate->last_name,
                'username' => $affiliate->username,
                'email' => $affiliate->email,
                'photo' => $affiliate->photo,
                'network_size' => $stats['size'],
                'network_deposits' => $stats['deposits'],
                'network_payouts' => $stats['payouts']
            ]);
        }
        // Sort logic
        $sort_by = $request->query('sort', 'size');
        if ($sort_by === 'deposits') {
            $top_referrers = $top_referrers->sortByDesc('network_deposits')->take(5)->values();
        } elseif ($sort_by === 'payouts') {
            $top_referrers = $top_referrers->sortByDesc('network_payouts')->take(5)->values();
        } else {
            $top_referrers = $top_referrers->sortByDesc('network_size')->take(5)->values();
        }


        // -- Individual Tree View Logic --
        $target_user_id = $request->query('user_id');
        $target_user = null;
        $referral_tree = [];
        $target_network_stats = ['size' => 0, 'deposits' => 0, 'payouts' => 0];

        if ($target_user_id) {
            $target_user = \App\Models\User::find($target_user_id);
            if ($target_user) {
                $referral_tree = $this->getReferralTree($target_user, $total_levels);
                $target_network_stats = $this->getNetworkStats($target_user, $total_levels);
            }
        }

        // Global Paginated List (used if no specific user_id is requested)
        $referrals = \App\Models\User::with('referrer')
            ->whereNotNull('referrer_id')
            ->latest()
            ->paginate(getSetting('pagination', 15));

        $template = config('site.template');
        return view("templates.$template.blades.admin.referral-networks.index", compact(
            'page_title',
            'total_referrals',
            'total_commissions',
            'top_referrers',
            'referral_bonus_percentage',
            'total_levels',
            'referrals',
            'target_user',
            'referral_tree',
            'target_network_stats'
        ));
    }

    /**
     * Recursively computes the total members, total deposits, and total referral payouts in the user's downline up to $max_level.
     */
    private function getNetworkStats($user, $max_level, $current_level = 1)
    {
        if ($current_level > $max_level) {
            return ['size' => 0, 'deposits' => 0, 'payouts' => 0];
        }

        $direct_referrals = $user->referrals()->with('deposits')->get();

        $size = count($direct_referrals);
        $deposits = 0;
        $payouts = 0;

        foreach ($direct_referrals as $referral) {
            // Count their direct deposits (status = completed)
            $deposits += $referral->deposits()->where('status', 'completed')->sum('amount');

            // Count referral bonuses paid to this specific affiliate
            $payouts += \App\Models\Transaction::where('user_id', $referral->id)
                ->where('type', 'credit')
                ->where('description', 'like', '%Referral Bonus%')
                ->sum('amount');

            // Recurse down
            $downlineStats = $this->getNetworkStats($referral, $max_level, $current_level + 1);
            $size += $downlineStats['size'];
            $deposits += $downlineStats['deposits'];
            $payouts += $downlineStats['payouts'];
        }

        return ['size' => $size, 'deposits' => $deposits, 'payouts' => $payouts];
    }

    /**
     * Recursively builds the tree array containing users and their individual deposits/payouts.
     */
    private function getReferralTree($user, $max_level, $current_level = 1)
    {
        if ($current_level > $max_level) {
            return [];
        }

        $referrals = $user->referrals()->with('deposits')->get();
        $tree = [];

        foreach ($referrals as $referral) {
            $personal_deposits = $referral->deposits()->where('status', 'completed')->sum('amount');
            $personal_payouts = \App\Models\Transaction::where('user_id', $referral->id)
                ->where('type', 'credit')
                ->where('description', 'like', '%Referral Bonus%')
                ->sum('amount');

            $stats = $this->getNetworkStats($referral, $max_level - $current_level); // Remaining levels

            $tree[] = [
                'user' => $referral,
                'level' => $current_level,
                'personal_deposits' => $personal_deposits,
                'personal_payouts' => $personal_payouts,
                'network_size' => $stats['size'],
                'network_deposits' => $stats['deposits'],
                'network_payouts' => $stats['payouts'],
                'children' => $this->getReferralTree($referral, $max_level, $current_level + 1)
            ];
        }

        return $tree;
    }
}
