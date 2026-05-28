<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class LoginMethodController extends Controller
{
    /**
     * Display the login methods settings page.
     */
    public function index()
    {
        $page_title = __('Login Methods');
        $template = config('site.template');

        // Fetch current login methods from settings
        $login_methods = json_decode(getSetting('login_methods'), true) ?? [];

        return view("templates.$template.blades.admin.settings.login-method", compact(
            'page_title',
            'template',
            'login_methods'
        ));
    }

    /**
     * Update the login methods settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'provider' => 'required|string',
            'status' => 'required|in:enabled,disabled',
            'env' => 'nullable|array',
        ]);

        $provider = $request->input('provider');
        $status = $request->input('status');

        // 1. Update the 'login_methods' setting (JSON)
        $login_methods = json_decode(getSetting('login_methods'), true) ?? [];

        if (isset($login_methods[$provider])) {
            $login_methods[$provider]['status'] = $status;
            updateSetting('login_methods', $login_methods);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => __('Login method not found.')
            ], 404);
        }

        // 2. Update Environment Variables for Social Providers
        if ($request->has('env') && !empty($request->input('env'))) {
            foreach ($request->input('env') as $key => $value) {
                // We only update if the value is not a masked sandbox value
                if (!empty($value) && !str_contains($value, '****')) {
                    updateEnv($key, $value, true); // encrypt = true
                }
            }

            // Clear config cache to apply .env changes
            Artisan::call('config:clear');
        }

        // if all are disabled, we need to force email enable, fetch login methods afresh
        $login_methods_setting = Setting::where('key', 'login_methods')->first();
        $login_methods = json_decode($login_methods_setting->value, true);

        $enabled_methods = array_filter($login_methods, function ($method) {
            return ($method['status'] ?? '') === 'enabled';
        });

        if (empty($enabled_methods)) {
            $login_methods['email']['status'] = 'enabled';
            updateSetting('login_methods', $login_methods);
            // fetch afresh
            $login_methods = json_decode(getSetting('login_methods'), true) ?? [];
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => __(':name updated successfully.', ['name' => $login_methods[$provider]['name'] ?? $provider])
            ]);
        }

        return back()->with('success', __(':name updated successfully.', ['name' => $login_methods[$provider]['name'] ?? $provider]));
    }
}
