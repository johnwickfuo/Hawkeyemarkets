<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LiveChatController extends Controller
{
    public function index()
    {
        $page_title = __('Livechat & Scripts');

        // Current Settings
        $scripts = [
            'livechat_scripts' => getSetting('livechat_scripts'),
            'header_scripts' => getSetting('header_scripts'),
            'footer_scripts' => getSetting('footer_scripts'),
        ];

        $template = config('site.template');

        return view('templates.' . $template . '.blades.admin.settings.livechat', compact(
            'page_title',
            'scripts',
            'template'
        ));
    }

    public function update(Request $request)
    {
        $scripts = [
            'livechat_scripts' => $request->livechat_scripts,
            'header_scripts' => $request->header_scripts,
            'footer_scripts' => $request->footer_scripts,
        ];

        foreach ($scripts as $key => $value) {
            updateSetting($key, $value);
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => __('Livechat and Scripts settings updated successfully.')
            ]);
        }

        return back()->with('success', __('Livechat and Scripts settings updated successfully.'));
    }
}
