<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UtilityController extends Controller
{
    public function index()
    {
        $page_title = __('Utility Settings');

        // Current Settings
        $pagination = getSetting('pagination', config('site.settings_defaults.pagination', 10));
        $delete_notification_message = getSetting('delete_notification_message', config('site.settings_defaults.delete_notification_message', 'enabled'));
        $preloader = getSetting('preloader', 'enabled');

        $all_languages = json_decode(file_get_contents(public_path('assets/json/languages.json')), true);
        $enabled_languages = array_keys(array_filter($all_languages, fn($l) => $l['enabled']));
        $template = config('site.template');

        return view('templates.' . $template . '.blades.admin.settings.utility', compact(
            'page_title',
            'pagination',
            'delete_notification_message',
            'preloader',
            'all_languages',
            'enabled_languages',
            'template'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'pagination' => 'required|integer|min:1|max:100',
            'delete_notification_message' => 'required|in:enabled,disabled',
            'preloader' => 'required|in:enabled,disabled',
            'enabled_languages' => 'required|array',
            'enabled_languages.*' => 'string',
        ]);

        updateSetting('pagination', $request->pagination);
        updateSetting('delete_notification_message', $request->delete_notification_message);
        updateSetting('preloader', $request->preloader);

        // Update languages.json
        $all_languages = json_decode(file_get_contents(public_path('assets/json/languages.json')), true);
        foreach ($all_languages as $code => &$lang) {
            $lang['enabled'] = in_array($code, $request->enabled_languages);
        }
        file_put_contents(public_path('assets/json/languages.json'), json_encode($all_languages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => __('Utility settings updated successfully.'),
            ]);
        }

        return back()->with('success', __('Utility settings updated successfully.'));
    }
}
