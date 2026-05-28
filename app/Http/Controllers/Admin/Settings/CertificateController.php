<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CertificateController extends Controller
{
    /**
     * Display the certificate settings page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $page_title = __('Certificate Settings');
        $template = config('site.template');

        // Fetch structure or default
        $compliance = getSetting('regulatory_compliance');
        if ($compliance) {
            $compliance = json_decode($compliance, true);
        } else {
            $compliance = [
                'regulators' => [],
                'pdf_certificates' => []
            ];
        }

        return view("templates.$template.blades.admin.settings.certificate", compact('page_title', 'template', 'compliance'));
    }

    /**
     * Update the certificate settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'regulators' => 'nullable|array',
            'regulators.*' => 'nullable|string|max:255',
            'pdf_names' => 'nullable|array',
            'pdf_files' => 'nullable|array',
            'pdf_files.*' => 'nullable|file|mimes:pdf|max:5120', // 5MB limit
        ]);

        $compliance = [
            'regulators' => array_filter($request->regulators ?? []),
            'pdf_certificates' => json_decode(getSetting('regulatory_compliance'))->pdf_certificates ?? [],
        ];

        // Ensure directory exists
        $path = public_path('assets/pdf');
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true);
        }

        // Handle PDF removals (if any logic for that exists in the view, we'd process it here)
        // For now, let's process NEW uploads
        if ($request->has('pdf_names')) {
            $new_certificates = [];
            foreach ($request->pdf_names as $index => $name) {
                if (empty($name))
                    continue;

                $cert_data = [
                    'name' => $name,
                    'file' => $compliance['pdf_certificates'][$index]['file'] ?? null
                ];

                if ($request->hasFile("pdf_files.$index")) {
                    $file = $request->file("pdf_files.$index");
                    $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                    $file->move($path, $filename);

                    // Delete old file if exists
                    if ($cert_data['file'] && File::exists($path . '/' . $cert_data['file'])) {
                        File::delete($path . '/' . $cert_data['file']);
                    }

                    $cert_data['file'] = $filename;
                }

                $new_certificates[] = $cert_data;
            }
            $compliance['pdf_certificates'] = $new_certificates;
        }

        updateSetting('regulatory_compliance', json_encode($compliance));

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => __('Certificate settings updated successfully.')
            ]);
        }

        return back()->with('success', __('Certificate settings updated successfully.'));
    }
}
