<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Services\LozandServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use function Symfony\Component\Clock\now;

class ActivationController extends Controller
{
    /**
     * Display the Activation settings page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $page_title = __('Activation Settings');
        $template = config('site.template');

        $license_key = safeDecrypt(config('site.product_key'));
        $url = 'https://lozand.com/api/v1/license/license-key/' . $license_key;
        $license_information = null;

        /**
         * NULLED BY PLACEHOLDER DEV
         */

        $license_information = [
            'name' => 'Nulled by Placeholder Dev',
            'date' => now()->format('y-m-d'),
            'license_key' => $license_key
        ];

        // try {
        //     $response = Http::withHeaders([
        //         'x-license-key' => $license_key,
        //         'x-domain' => request()->getHost()
        //     ])->timeout(60)->get($url);
        //     $data = $response->json();

        //     if ($response->status() == 200 || $response->status() == 201) {
        //         $license_information = $data['data'];
        //     }

        // } catch (\Exception $e) {
        //     $license_information = null;
        // }


        $lozand = new LozandServices();
        $server_ip_request = $lozand->getIp();
        $server_ip = $_SERVER['SERVER_ADDR'] ?? null;
        if ($server_ip_request['status'] == 'success') {
            $server_ip = $server_ip_request['data']['ip'];
        }

        return view("templates.$template.blades.admin.settings.activation", compact('page_title', 'template', 'license_information', 'server_ip'));
    }

    /**
     * Update the Activation settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'product_key'       => 'nullable|string|max:255',
            'twelve_data_key'   => 'nullable|string|max:255',
        ]);
        $product_key     = $request->product_key;
        $twelve_data_key = $request->twelve_data_key;

        if ($product_key) {
            // check if its valid UUID
            if (!str()->isUuid($product_key)) {
                if ($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => __('Invalid product key.')
                    ], 400);
                }
                return back()->with('error', __('Invalid product key.'));
            }

        }


        // Actiave 
        $message = __("License activation failed.");
        $status = 'error';

        try {

            $url = 'https://lozand.com/api/v1/license/activate';

            $params = [
                'license_key' => $product_key,
                'domain' => $request->getHost(),
            ];
            $response = Http::post($url, $params);

            $response_json = $response->json();


            if ($response->status() == 200 || $response->status() == 201) {

                $license_information = $response_json['data'];

                if (safeDecrypt(config('site.product_key')) != $product_key) {
                    updateEnv('PRODUCT_KEY', encrypt($product_key));
                }

                $message = $license_information['domain'] ? __('License activated successfully for :domain', ['domain' => $license_information['domain']]) : __('License activated successfully.');
                $status = 'success';
            }

            if ($response_json['message']) {
                $message = $response_json['message'];
            }

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }



        if ($twelve_data_key && env('TWELVE_DATA_API_KEY') !== $twelve_data_key) {
            updateEnv('TWELVE_DATA_API_KEY', $twelve_data_key);
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => $status,
                'message' => $message
            ], $status == 'success' ? 200 : 400);
        }

        return back()->with($status, $message);
    }
}
