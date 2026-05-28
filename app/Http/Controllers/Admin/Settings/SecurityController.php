<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    /**
     * Display the security settings page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $page_title = __('Security Settings');
        $template = config('site.template');

        $kyc_settings = json_decode(getSetting('kyc'), true) ?? [];

        return view("templates.$template.blades.admin.settings.security", compact(
            'page_title',
            'template',
            'kyc_settings'
        ));
    }

    /**
     * Update the security settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'email_verification' => 'required|in:enabled,disabled',
            'google_recaptcha' => 'required|in:enabled,disabled',
            'require_strong_password' => 'required|in:enabled,disabled',
            'login_otp' => 'required|in:enabled,disabled',
            'nocaptcha_sitekey' => 'nullable|string|max:255',
            'nocaptcha_secret' => 'nullable|string|max:255',
            'kyc' => 'nullable|array',
        ]);

        // Security Options
        updateSetting('email_verification', $request->email_verification);
        updateSetting('google_recaptcha', $request->google_recaptcha);
        updateSetting('require_strong_password', $request->require_strong_password);
        updateSetting('login_otp', $request->login_otp);



        if (moduleEnabled('kyc_module')) {
            // KYC Settings
            $kycInput = $request->input('kyc', []);
            $existingKyc = json_decode(getSetting('kyc'), true) ?? [];

            foreach ($existingKyc as &$setting) {
                if (isset($kycInput[$setting['name']])) {
                    $setting['status'] = $kycInput[$setting['name']] === 'enabled' ? 'enabled' : 'disabled';
                } else {
                    $setting['status'] = 'disabled';
                }
            }

            updateSetting('kyc', $existingKyc);
        }

        // reCAPTCHA Keys
        if ($request->has('nocaptcha_sitekey')) {
            updateSetting('nocaptcha_sitekey', $request->nocaptcha_sitekey);
        }
        if ($request->has('nocaptcha_secret')) {
            updateSetting('nocaptcha_secret', $request->nocaptcha_secret);
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => __('Security settings updated successfully.')
            ]);
        }

        return back()->with('success', __('Security settings updated successfully.'));
    }
}
