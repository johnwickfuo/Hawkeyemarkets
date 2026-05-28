<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BonusSystemController extends Controller
{
    /**
     * Display the bonus system settings page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $page_title = __('Bonus System');
        $template = config('site.template');

        // Fetch referral bonus array or default
        $referral_bonus = getSetting('referral_bonus');
        if ($referral_bonus) {
            $referral_bonus = json_decode($referral_bonus, true);
        } else {
            $referral_bonus = [10, 5, 3, 0, 0, 0];
        }

        return view("templates.$template.blades.admin.settings.bonus-system", compact('page_title', 'template', 'referral_bonus'));
    }

    /**
     * Update the bonus system settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'welcome_bonus' => 'required|numeric|min:0',
            'referral_bonus' => 'required|array|min:1',
            'referral_bonus.*' => 'required|numeric|min:0',
        ]);

        // Update Welcome Bonus
        updateSetting('welcome_bonus', $request->welcome_bonus);

        // Update Referral Bonuses (Stored as JSON for flexibility)
        updateSetting('referral_bonus', json_encode($request->referral_bonus));

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => __('Bonus system settings updated successfully.')
            ]);
        }

        return back()->with('success', __('Bonus system settings updated successfully.'));
    }
}
