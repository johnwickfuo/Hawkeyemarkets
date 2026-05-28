<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $referrals = $user->referrals()->paginate(getSetting('pagination', 15));
        $referrer = $user->referrer;
        $page_title = __('Referrals');
        $referral_link = route('user.register', ['ref' => $user->referral_code]);
        $referral_earnings = Transaction::where('user_id', $user->id)->where('description', 'like', '%Referral Bonus%')->sum('amount');
        $referral_bonus_percentage = json_decode(getSetting('referral_bonus', json_encode([0, 0])), true);
        $total_levels = count($referral_bonus_percentage);

        // Build the referral tree
        $referral_tree = $this->getReferralTree($user, $total_levels);

        // Network stats
        $network_count = $this->countNetwork($referral_tree);

        return view('templates.' . config('site.template') . '.blades.user.referrals', compact(
            'referrals',
            'referrer',
            'page_title',
            'referral_link',
            'referral_earnings',
            'referral_bonus_percentage',
            'total_levels',
            'referral_tree',
            'network_count'
        ));
    }

    private function getReferralTree($user, $max_level, $current_level = 1)
    {
        if ($current_level > $max_level) {
            return [];
        }

        $referrals = $user->referrals()->get();
        $tree = [];

        foreach ($referrals as $referral) {
            $tree[] = [
                'user' => $referral,
                'level' => $current_level,
                'children' => $this->getReferralTree($referral, $max_level, $current_level + 1)
            ];
        }

        return $tree;
    }

    private function countNetwork($tree)
    {
        $count = count($tree);
        foreach ($tree as $node) {
            $count += $this->countNetwork($node['children']);
        }
        return $count;
    }
}
