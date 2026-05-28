<?php

namespace App\Http\Controllers\Admin\FileManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CodeEditorController extends Controller
{
    public function __construct()
    {
        if (!moduleEnabled('file_manager_module')) {
            abort(403, __('File Manager module is disabled.'));
        }


        // check sandbox mode
        if (config('app.env') == 'sandbox') {
            abort(403, __('Sandbox mode is enabled. You cannot edit files in sandbox mode.'));
        }
    }
    // index
    public function index(Request $request)
    {
        $page_title = __('Code Editor');
        $template = config('site.template');
        $path = $request->path;
        // check if file exist
        if (!file_exists($path)) {
            return redirect()->back()->with('error', __('File not found'));
        }
        $code = file_get_contents($path);

        return view("templates.$template.blades.admin.file-manager.code-editor", compact('page_title', 'path', 'code'));
    }

    // update
    public function update(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'path' => 'required|string',
        ]);

        $code = $request->code;
        $path = $request->path;

        file_put_contents($path, $code);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('Code updated successfully'),
            ]);
        }

        return redirect()->back()->with('success', __('Code updated successfully'));
    }
}
