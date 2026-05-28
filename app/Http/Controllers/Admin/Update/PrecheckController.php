<?php

namespace App\Http\Controllers\Admin\Update;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PrecheckController extends Controller
{


    public function index()
    {
        $page_title = __('System Update');
        $template = config('site.template');

        $current_version = config('site.version', '1.0.0');
        $license_key = safeDecrypt(config('site.product_key'));

        try {
            $response = Http::withHeaders([
                'x-license-key' => $license_key,
                'x-domain' => request()->getHost()
            ])->timeout(60)->get('https://lozand.com/api/v1/update');

            if ($response->failed()) {
                $error = $response->json()['message'] ?? __('Failed to connect to the update server.');
                $latest_version = $current_version;
                $is_update_available = false;
                $php_version_supported = true;
                $required_php = '8.3';
                $update_data = [];

                return view("templates.$template.blades.admin.update.index", compact(
                    'page_title',
                    'template',
                    'current_version',
                    'latest_version',
                    'is_update_available',
                    'php_version_supported',
                    'required_php',
                    'update_data'
                ))->with('error', $error);
            }

            $update_data = $response->json()['data'] ?? [];
            session()->put('lozand_update_data', $update_data);

            $latest_version = $update_data['version'] ?? $current_version;

            $is_update_available = version_compare($current_version, $latest_version, '<');

            // Check PHP Version compatibility
            $required_php = $update_data['php_version'];
            $php_version_supported = version_compare(PHP_VERSION, $required_php, '>=');

            return view("templates.$template.blades.admin.update.index", compact(
                'page_title',
                'template',
                'current_version',
                'latest_version',
                'is_update_available',
                'php_version_supported',
                'update_data',
                'required_php'
            ));

        } catch (\Exception $e) {
            $latest_version = $current_version;
            $is_update_available = false;
            $php_version_supported = true;
            $required_php = '8.3';
            $update_data = [];

            return view("templates.$template.blades.admin.update.index", compact(
                'page_title',
                'template',
                'current_version',
                'latest_version',
                'is_update_available',
                'php_version_supported',
                'required_php',
                'update_data'
            ))->with('error', $e->getMessage());
        }
    }

    public function verifyRequirements(Request $request)
    {
        $update_data = session()->get('lozand_update_data', []);
        $required_php = $update_data['php_version'] ?? '8.3';

        if (!version_compare(PHP_VERSION, $required_php, '>=')) {
            $msg = __('PHP version :required or higher is required to run diagnostics.', ['required' => $required_php]);
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => $msg], 403);
            }
            return back()->with('error', $msg);
        }

        $server_config_req = $update_data['server_config'];
        $extensions = $update_data['extensions'];

        $server_config = $this->getServerRequirements($server_config_req);

        $extension_results = [];
        foreach ($extensions as $ext) {
            $extension_results[$ext] = extension_loaded($ext);
        }

        $all_met = !in_array(false, array_column($server_config, 'status')) && !in_array(false, $extension_results);

        if ($request->ajax()) {
            return response()->json([
                'status' => $all_met ? 'success' : 'error',
                'server_config' => $server_config,
                'extensions' => $extension_results,
                'message' => $all_met ? __('All requirements met.') : __('Some requirements are not met.')
            ]);
        }

        return back()->with($all_met ? 'success' : 'error', $all_met ? __('All requirements met.') : __('Some requirements are not met.'));
    }

    public function updateUpdater(Request $request)
    {
        $license_key = safeDecrypt(config('site.product_key'));



        $update_data = session()->get('lozand_update_data', []);
        $version = $update_data['version'] ?? null;

        if (!$version) {
            return response()->json(['status' => 'error', 'message' => __('Update version information missing.')], 400);
        }


        try {
            $response = Http::withHeaders([
                'x-license-key' => $license_key,
                'x-domain' => request()->getHost()
            ])->timeout(120)->get('https://lozand.com/api/v1/update/download/updater', [
                        'version' => $version
                    ]);

            if ($response->failed()) {
                \Log::error($response->body());
                return response()->json(['status' => 'error', 'message' => __('Failed to download updater.')], 400);
            }

            $data = $response->json()['data'] ?? [];

            foreach ($data as $file_path => $content) {
                $full_path = base_path($file_path);
                $dir = dirname($full_path);
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                file_put_contents($full_path, base64_decode($content));
            }

            $uid = uniqid();
            cache()->put('lozand_update_uid', $uid, now()->addHours(2));

            return response()->json([
                'status' => 'success',
                'message' => __('Updater updated successfully.'),
                'redirect_url' => route('admin.update.process.index', ['uid' => $uid])
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    private function getServerRequirements($requirements = [])
    {
        $parse_size = function ($size) {
            if (!$size || $size == -1)
                return PHP_INT_MAX;
            $unit = preg_replace('/[^bkmgtp]/i', '', $size);
            $size = preg_replace('/[^0-9\.]/', '', $size);
            if ($unit)
                return round($size * pow(1024, stripos('bkmgtp', $unit[0])));
            return round($size);
        };

        $server_config = [];
        foreach ($requirements as $key => $recommended) {
            $server_config[$key] = [
                'recommended' => $recommended,
                'current' => ini_get($key),
            ];
        }

        foreach ($server_config as $key => &$config) {
            if ($key == 'max_execution_time' || $key == 'max_input_time') {
                $config['status'] = (int) $config['current'] >= (int) $config['recommended'] || (int) $config['current'] == 0;
            } else {
                $config['status'] = $parse_size($config['current']) >= $parse_size($config['recommended']);
            }
        }

        return $server_config;
    }
}
