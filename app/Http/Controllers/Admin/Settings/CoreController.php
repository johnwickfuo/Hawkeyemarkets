<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class CoreController extends Controller
{
    // index
    public function index()
    {
        $page_title = __('Core Settings');
        $template = config('site.template');

        // Timezones & Currencies list
        $timezones = json_decode(file_get_contents(public_path('assets/json/timezones.json')), true);
        $currencies = json_decode(file_get_contents(public_path('assets/json/currencies.json')), true);


        return view("templates.$template.blades.admin.settings.core", compact('page_title', 'template', 'timezones', 'currencies'));
    }

    // update
    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:191',
            'support_email' => 'required|email|max:191',
            'app_timezone' => 'required|string',
            'currency_name' => 'required|string|max:50',
            'currency_symbol' => 'required|string|max:10',
            'currency_position' => 'required|in:before,after',
            'decimal_places' => 'required|integer|min:0|max:8',
            'logo_square' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'logo_rectangle' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico|max:1024',
            'offices' => 'nullable|array',
            'offices.*.name' => 'required_with:offices|string|max:191',
            'offices.*.address' => 'required_with:offices|string',
            'offices.*.email' => 'nullable|email|max:191',
            'offices.*.phone' => 'nullable|string|max:191',
        ]);

        // General Site Info
        updateSetting('name', $request->site_name);
        updateSetting('email', $request->support_email);
        updateSetting('app_timezone', $request->app_timezone);
        updateSetting('offices', $request->offices ?: []);

        // Env Sync for Site Name and Timezone
        updateEnv('APP_NAME', $request->site_name);
        updateEnv('APP_TIMEZONE', $request->app_timezone);

        // Financials
        updateSetting('currency', $request->currency_name);
        updateSetting('currency_symbol', $request->currency_symbol);
        updateSetting('currency_symbol_position', $request->currency_position);
        updateSetting('decimal_places', $request->decimal_places);

        // Branding (Images)
        $path = 'assets/images/';

        if ($request->hasFile('logo_square')) {
            $logoSquare = 'logo-square.' . $request->logo_square->extension();
            $request->logo_square->move(public_path($path), $logoSquare);
            $logoSquare = $logoSquare . "?v=" . time();
            updateSetting('logo_square', $logoSquare);
        }

        if ($request->hasFile('logo_rectangle')) {
            $logoRectangle = 'logo-rectangle.' . $request->logo_rectangle->extension();
            $request->logo_rectangle->move(public_path($path), $logoRectangle);
            $logoRectangle = $logoRectangle . "?v=" . time();
            updateSetting('logo_rectangle', $logoRectangle);
        }

        if ($request->hasFile('favicon')) {
            $favicon = 'favicon.' . $request->favicon->extension();
            $request->favicon->move(public_path($path), $favicon);
            $favicon = $favicon . "?v=" . time();
            updateSetting('favicon', $favicon);
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => __('Core settings updated successfully. System synchronized with .env configuration.')
            ]);
        }

        return back()->with('success', __('Core settings updated successfully. System synchronized with .env configuration.'));
    }
}
