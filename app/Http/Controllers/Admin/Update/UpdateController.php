<?php

namespace App\Http\Controllers\Admin\Update;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class UpdateController extends Controller
{


    public function index(Request $request)
    {
        $uid = $request->uid;
        $cached_uid = cache()->get('lozand_update_uid');

        if (!$uid || $uid !== $cached_uid) {
            return redirect()->route('admin.update.index')->with('error', __('Invalid or expired update session.'));
        }

        $page_title = __('Processing Update');
        $template = config('site.template');

        return view("templates.$template.blades.admin.update.process", compact('page_title', 'template', 'uid'));
    }

    private function getUpdatePath($file = '')
    {
        $path = storage_path('updates');
        if (!is_dir($path)) {
            mkdir($path, 0775, true);
        }
        return $file ? $path . '/' . $file : $path;
    }

    private function verifySession(Request $request)
    {
        $uid = $request->uid;
        $cached_uid = cache()->get('lozand_update_uid');

        if (!$uid || $uid !== $cached_uid) {
            return false;
        }

        return true;
    }

    public function initCleanup(Request $request)
    {
        if (!$this->verifySession($request)) {
            return response()->json(['status' => 'error', 'message' => __('Invalid or expired update session.')], 403);
        }

        try {
            $zip_path = $this->getUpdatePath('core_update.zip');
            $extract_path = $this->getUpdatePath('core_update');

            if (file_exists($zip_path)) {
                unlink($zip_path);
            }

            if (is_dir($extract_path)) {
                File::deleteDirectory($extract_path);
            }

            return response()->json(['status' => 'success', 'message' => __('Environment cleaned and ready for update.')]);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function download(Request $request)
    {
        if (!$this->verifySession($request)) {
            return response()->json(['status' => 'error', 'message' => __('Invalid or expired update session.')], 403);
        }

        $license_key = safeDecrypt(config('site.product_key'));
        $update_data = session()->get('lozand_update_data', []);
        $version = $update_data['version'] ?? null;

        if (!$version) {
            return response()->json(['status' => 'error', 'message' => __('Update version information missing.')], 400);
        }

        try {
            $zip_name = 'core_update.zip';
            $zip_path = $this->getUpdatePath($zip_name);

            if (file_exists($zip_path)) {
                unlink($zip_path);
            }

            $response = Http::withHeaders([
                'x-license-key' => $license_key,
                'x-domain' => request()->getHost()
            ])
                ->timeout(1200)
                ->sink($zip_path)
                ->get('https://lozand.com/api/v1/update/download/core', [
                    'version' => $version
                ]);

            if ($response->failed() || !file_exists($zip_path)) {
                if (file_exists($zip_path)) {
                    unlink($zip_path);
                }
                return response()->json([
                    'status' => 'error',
                    'message' => $response->json()['message'] ?? __('Failed to download core update.')
                ], 400);
            }

            return response()->json(['status' => 'success', 'message' => __('Update package downloaded successfully.')]);

        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function extract(Request $request)
    {
        if (!$this->verifySession($request)) {
            return response()->json(['status' => 'error', 'message' => __('Invalid or expired update session.')], 403);
        }

        try {
            $zip_path = $this->getUpdatePath('core_update.zip');
            $extract_path = $this->getUpdatePath('core_update');

            if (!file_exists($zip_path)) {
                return response()->json(['status' => 'error', 'message' => __('Update package not found.')], 400);
            }

            if (is_dir($extract_path)) {
                File::deleteDirectory($extract_path);
            }

            mkdir($extract_path, 0775, true);

            $zip = new \ZipArchive;
            if ($zip->open($zip_path) === true) {
                $zip->extractTo($extract_path);
                $zip->close();
                // We don't unlink zip yet, we do it in cleanup
                return response()->json([
                    'status' => 'success',
                    'message' => __('Update package extracted successfully.'),
                    'folder' => 'storage/updates/core_update'
                ]);
            } else {
                return response()->json(['status' => 'error', 'message' => __('Failed to open zip file.')], 500);
            }
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function sanitize(Request $request)
    {
        if (!$this->verifySession($request)) {
            return response()->json(['status' => 'error', 'message' => __('Invalid or expired update session.')], 403);
        }

        $final_path = $this->getUpdatePath('core_update/lozand/Files');

        //files to delete 
        $files_to_delete = [
            '/.env',
            '/public/assets/images/favicon.png',
            '/public/assets/images/logo-rectangle.png',
            '/public/assets/images/logo-square.png',
            '/app/Http/Controllers/Admin/Update/UpdateController.php',
            '/resources/views/templates/bento/blades/admin/update/process.blade.php',
        ];





        // folders to delete 
        $folders_to_delete = [
            '/bootstrap/cache',
            '/storage/framework',
            '/storage/debugbar',
            '/storage/logs',
            '/storage/untranslated'
        ];

        try {
            foreach ($files_to_delete as $file) {
                if ($file === '/')
                    continue; // Safety check
                $path = $final_path . DIRECTORY_SEPARATOR . ltrim($file, DIRECTORY_SEPARATOR);
                if (file_exists($path) && is_file($path)) {
                    unlink($path);
                }
            }

            foreach ($folders_to_delete as $folder) {
                $path = $final_path . DIRECTORY_SEPARATOR . ltrim($folder, DIRECTORY_SEPARATOR);
                if (is_dir($path)) {
                    File::deleteDirectory($path);
                }
            }


            // Clean storage/app/public subfolders
            $packagePublicPath = $final_path . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public';
            if (is_dir($packagePublicPath)) {
                $subfolders = array_filter(glob($packagePublicPath . DIRECTORY_SEPARATOR . '*'), 'is_dir');
                foreach ($subfolders as $folder) {
                    File::cleanDirectory($folder);
                }
            }

            updateEnv('APP_ENV', 'local');

            return response()->json(['status' => 'success', 'message' => __('Files sanitized successfully.')]);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function replace(Request $request)
    {
        if (!$this->verifySession($request)) {
            return response()->json(['status' => 'error', 'message' => __('Invalid or expired update session.')], 403);
        }

        try {
            $final_path = $this->getUpdatePath('core_update/lozand/Files');

            if (!is_dir($final_path)) {
                return response()->json(['status' => 'error', 'message' => __('Update files not found for replacement.')], 400);
            }

            // copy files and folders from final to the to the base_path 
            File::copyDirectory($final_path, base_path());

            // run migration
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);

            $update_data = session()->get('lozand_update_data', []);
            $version = $update_data['version'] ?? '1.0.0';

            updateEnv('APP_VERSION', $version);
            updateEnv('APP_ENV', 'production');

            session()->forget('lozand_update_data');
            cache()->forget('lozand_update_uid');

            // Clear all caches after update
            \Illuminate\Support\Facades\Artisan::call('optimize:clear');

            return response()->json(['status' => 'success', 'message' => __('System files updated successfully.')]);
        } catch (\Throwable $e) {
            \Log::error('Update Replacement Failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function cleanup(Request $request)
    {

        try {
            if (is_dir($this->getUpdatePath())) {
                File::deleteDirectory($this->getUpdatePath());
            }

            $zip_path = $this->getUpdatePath('core_update.zip');
            if (file_exists($zip_path)) {
                unlink($zip_path);
            }

            // Clean up old storage folder if it exists (prevents storage:link from being skipped)
            if (File::isDirectory(public_path('storage'))) {
                File::deleteDirectory(public_path('storage'));
                Artisan::call('storage:link', ['--force' => true]);
            }



            return response()->json(['status' => 'success', 'message' => __('Update completed successfully. Returning to dashboard.')]);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
