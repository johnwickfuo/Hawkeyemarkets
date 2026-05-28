<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SeoController extends Controller
{
    /**
     * Display the SEO settings page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $page_title = __('SEO Settings');
        $template = config('site.template');

        $social_links = getSetting('social_media', config('site.settings_defaults.social_media'));
        if (!is_array($social_links)) {
            $social_links = json_decode($social_links, true) ?? [];
        }

        $seo = [
            'search_engine_indexing' => config('site.search_engine_indexing'),
            'description' => getSetting('seo_description'),
            'keywords' => getSetting('seo_keywords'),
            'social_title' => getSetting('social_title'),
            'social_description' => getSetting('social_description'),
            'seo_image' => getSetting('seo_image'),
            'social_media' => $social_links
        ];

        return view("templates.$template.blades.admin.settings.seo", compact('page_title', 'template', 'seo'));
    }

    /**
     * Update the SEO settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'seo_description' => 'required|string|max:500',
            'seo_keywords' => 'required|string|max:500',
            'social_title' => 'required|string|max:191',
            'social_description' => 'required|string|max:500',
            'seo_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'social_media' => 'nullable|array',
        ]);

        // Update Env
        updateEnv('SEARCH_ENGINE_INDEXING', $request->search_engine_indexing ? 'true' : 'false');

        // Update Settings
        updateSetting('seo_description', $request->seo_description);
        updateSetting('seo_keywords', $request->seo_keywords);
        updateSetting('social_title', $request->social_title);
        updateSetting('social_description', $request->social_description);
        updateSetting('social_media', $request->social_media ?? []);

        // Handle Image Upload
        if ($request->hasFile('seo_image')) {
            $path = public_path('assets/images');
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0755, true);
            }

            $file = $request->file('seo_image');
            $filename = 'seo_banner_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($path, $filename);

            // Delete old image if exists
            $old_image = getSetting('seo_image');
            if ($old_image && File::exists($path . '/' . $old_image)) {
                File::delete($path . '/' . $old_image);
            }

            updateSetting('seo_image', $filename);
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => __('SEO settings updated successfully.')
            ]);
        }

        return back()->with('success', __('SEO settings updated successfully.'));
    }
}
