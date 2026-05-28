<?php

namespace App\Http\Controllers\Admin\FileManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function __construct()
    {

    }
    public function index(Request $request)
    {
        $page_title = __('File Manager');
        $template = config('site.template');

        $base_path = base_path();
        $requested_path = $request->path ?? $base_path;

        // Security check: Ensure requested path is within base_path
        $real_path = realpath($requested_path);
        if ($real_path === false || !str_starts_with($real_path, $base_path)) {
            $real_path = $base_path;
        }

        $items = [];
        $files = scandir($real_path);

        foreach ($files as $file) {
            if ($file == '.')
                continue;
            if ($file == '..' && $real_path == $base_path)
                continue;

            $full_path = $real_path . DIRECTORY_SEPARATOR . $file;
            $is_dir = is_dir($full_path);

            $items[] = [
                'name' => $file,
                'path' => $full_path,
                'is_dir' => $is_dir,
                'size' => $is_dir ? '-' : $this->formatFileSize(filesize($full_path)),
                'last_modified' => date('Y-m-d H:i:s', filemtime($full_path)),
                'extension' => $is_dir ? 'folder' : pathinfo($full_path, PATHINFO_EXTENSION),
                'permissions' => substr(sprintf('%o', fileperms($full_path)), -4),
            ];
        }

        // Sort: Folders first, then alphabetically
        usort($items, function ($a, $b) {
            if ($a['is_dir'] && !$b['is_dir'])
                return -1;
            if (!$a['is_dir'] && $b['is_dir'])
                return 1;
            if ($a['name'] == '..')
                return -1;
            if ($b['name'] == '..')
                return 1;
            return strcasecmp($a['name'], $b['name']);
        });

        $breadcrumbs = $this->generateBreadcrumbs($real_path, $base_path);
        $current_path = $real_path;

        return view('templates.' . $template . '.blades.admin.file-manager.index', compact(
            'page_title',
            'template',
            'items',
            'current_path',
            'breadcrumbs',
            'base_path'
        ));
    }

    public function upload(Request $request)
    {
        if (!moduleEnabled('file_manager_module')) {
            return response()->json(['success' => false, 'message' => __('File Manager module is disabled.')], 400);
        }
        $request->validate([
            'file' => 'required|file',
            'path' => 'required|string',
        ]);

        $path = $request->path;
        if (!is_dir($path)) {
            return response()->json(['success' => false, 'message' => __('Target directory does not exist.')], 400);
        }

        $file = $request->file('file');
        $file->move($path, $file->getClientOriginalName());

        return response()->json(['success' => true, 'message' => __('File uploaded successfully.')]);
    }

    public function create(Request $request)
    {
        if (!moduleEnabled('file_manager_module')) {
            return response()->json(['success' => false, 'message' => __('File Manager module is disabled.')], 400);
        }
        $request->validate([
            'name' => 'required|string',
            'path' => 'required|string',
            'type' => 'required|in:file,folder',
        ]);

        $full_path = $request->path . DIRECTORY_SEPARATOR . $request->name;

        if (file_exists($full_path)) {
            return response()->json(['success' => false, 'message' => __('Item already exists.')], 400);
        }

        if ($request->type === 'folder') {
            mkdir($full_path, 0755, true);
        } else {
            file_put_contents($full_path, '');
        }

        return response()->json(['success' => true, 'message' => __('Item created successfully.')]);
    }

    public function rename(Request $request)
    {
        if (!moduleEnabled('file_manager_module')) {
            return response()->json(['success' => false, 'message' => __('File Manager module is disabled.')], 400);
        }
        $request->validate([
            'old_path' => 'required|string',
            'new_name' => 'required|string',
        ]);

        $old_path = $request->old_path;
        $dir = dirname($old_path);
        $new_path = $dir . DIRECTORY_SEPARATOR . $request->new_name;

        if (file_exists($new_path)) {
            return response()->json(['success' => false, 'message' => __('Target name already exists.')], 400);
        }

        rename($old_path, $new_path);

        return response()->json(['success' => true, 'message' => __('Item renamed successfully.')]);
    }

    public function move(Request $request)
    {
        if (!moduleEnabled('file_manager_module')) {
            return response()->json(['success' => false, 'message' => __('File Manager module is disabled.')], 400);
        }
        $request->validate([
            'old_path' => 'required|string',
            'new_dir' => 'required|string',
        ]);

        $old_path = $request->old_path;
        $new_path = $request->new_dir . DIRECTORY_SEPARATOR . basename($old_path);

        if (file_exists($new_path)) {
            return response()->json(['success' => false, 'message' => __('Item already exists in target directory.')], 400);
        }

        rename($old_path, $new_path);

        return response()->json(['success' => true, 'message' => __('Item moved successfully.')]);
    }

    public function copy(Request $request)
    {
        if (!moduleEnabled('file_manager_module')) {
            return response()->json(['success' => false, 'message' => __('File Manager module is disabled.')], 400);
        }
        $request->validate([
            'old_path' => 'required|string',
            'new_dir' => 'required|string',
        ]);

        $old_path = $request->old_path;
        $new_path = $request->new_dir . DIRECTORY_SEPARATOR . basename($old_path);

        if (file_exists($new_path)) {
            return response()->json(['success' => false, 'message' => __('Item already exists in target directory.')], 400);
        }

        if (is_dir($old_path)) {
            $this->recursiveCopy($old_path, $new_path);
        } else {
            copy($old_path, $new_path);
        }

        return response()->json(['success' => true, 'message' => __('Item copied successfully.')]);
    }

    public function delete(Request $request)
    {
        if (!moduleEnabled('file_manager_module')) {
            return response()->json(['success' => false, 'message' => __('File Manager module is disabled.')], 400);
        }
        $request->validate([
            'path' => 'required|string',
        ]);

        $path = $request->path;

        if (!file_exists($path)) {
            return response()->json(['success' => false, 'message' => __('Item not found.')], 404);
        }

        if (is_dir($path)) {
            $this->recursiveDelete($path);
        } else {
            unlink($path);
        }

        return response()->json(['success' => true, 'message' => __('Item deleted successfully.')]);
    }

    public function permission(Request $request)
    {
        if (!moduleEnabled('file_manager_module')) {
            return response()->json(['success' => false, 'message' => __('File Manager module is disabled.')], 400);
        }
        $request->validate([
            'path' => 'required|string',
            'permissions' => 'required|string|size:4', // Expecting something like 0755
        ]);

        $path = $request->path;
        if (!file_exists($path)) {
            return response()->json(['success' => false, 'message' => __('Item not found.')], 404);
        }

        $perms = octdec($request->permissions);
        if (@chmod($path, $perms)) {
            clearstatcache(true, $path);
            return response()->json(['success' => true, 'message' => __('Permissions updated successfully.')]);
        }

        return response()->json(['success' => false, 'message' => __('Failed to update permissions.')], 500);
    }

    public function bulkDelete(Request $request)
    {
        if (!moduleEnabled('file_manager_module')) {
            return response()->json(['success' => false, 'message' => __('File Manager module is disabled.')], 400);
        }
        $request->validate([
            'paths' => 'required|array',
            'paths.*' => 'string',
        ]);

        $paths = $request->paths;
        $successCount = 0;

        foreach ($paths as $path) {
            if (file_exists($path)) {
                if (is_dir($path)) {
                    $this->recursiveDelete($path);
                } else {
                    unlink($path);
                }
                $successCount++;
            }
        }

        return response()->json(['success' => true, 'message' => __(':count items deleted successfully.', ['count' => $successCount])]);
    }

    public function bulkMove(Request $request)
    {
        if (!moduleEnabled('file_manager_module')) {
            return response()->json(['success' => false, 'message' => __('File Manager module is disabled.')], 400);
        }
        $request->validate([
            'paths' => 'required|array',
            'paths.*' => 'string',
            'new_dir' => 'required|string',
        ]);

        $newDir = $request->new_dir;
        if (!is_dir($newDir)) {
            return response()->json(['success' => false, 'message' => __('Target directory does not exist.')], 400);
        }

        $successCount = 0;
        foreach ($request->paths as $path) {
            $dest = $newDir . DIRECTORY_SEPARATOR . basename($path);
            if (!file_exists($dest)) {
                if (rename($path, $dest)) {
                    $successCount++;
                }
            }
        }

        return response()->json(['success' => true, 'message' => __(':count items moved successfully.', ['count' => $successCount])]);
    }

    public function bulkCopy(Request $request)
    {
        if (!moduleEnabled('file_manager_module')) {
            return response()->json(['success' => false, 'message' => __('File Manager module is disabled.')], 400);
        }
        $request->validate([
            'paths' => 'required|array',
            'paths.*' => 'string',
            'new_dir' => 'required|string',
        ]);

        $newDir = $request->new_dir;
        if (!is_dir($newDir)) {
            return response()->json(['success' => false, 'message' => __('Target directory does not exist.')], 400);
        }

        $successCount = 0;
        foreach ($request->paths as $path) {
            $dest = $newDir . DIRECTORY_SEPARATOR . basename($path);
            if (!file_exists($dest)) {
                if (is_dir($path)) {
                    $this->recursiveCopy($path, $dest);
                } else {
                    copy($path, $dest);
                }
                $successCount++;
            }
        }

        return response()->json(['success' => true, 'message' => __(':count items copied successfully.', ['count' => $successCount])]);
    }

    public function bulkZip(Request $request)
    {
        if (!moduleEnabled('file_manager_module')) {
            return response()->json(['success' => false, 'message' => __('File Manager module is disabled.')], 400);
        }
        $request->validate([
            'paths' => 'required|array',
            'paths.*' => 'string',
            'name' => 'required|string',
            'dest_dir' => 'required|string',
        ]);

        if (!class_exists('ZipArchive')) {
            return response()->json(['success' => false, 'message' => __('ZipArchive extension is not installed.')], 500);
        }

        $zip = new \ZipArchive();
        $fileName = rtrim($request->name, '.zip') . '.zip';
        $full_path = $request->dest_dir . DIRECTORY_SEPARATOR . $fileName;

        if ($zip->open($full_path, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            foreach ($request->paths as $itemPath) {
                if (!file_exists($itemPath))
                    continue;

                $baseName = basename($itemPath);
                if (is_dir($itemPath)) {
                    $this->addDirToZip($zip, $itemPath, $baseName);
                } else {
                    $zip->addFile($itemPath, $baseName);
                }
            }
            $zip->close();
            return response()->json(['success' => true, 'message' => __('Archive created successfully: :name', ['name' => $fileName])]);
        }

        return response()->json(['success' => false, 'message' => __('Failed to create archive.')], 500);
    }

    private function addDirToZip($zip, $path, $internalPath)
    {

        $zip->addEmptyDir($internalPath);
        $files = scandir($path);
        foreach ($files as $file) {
            if ($file == '.' || $file == '..')
                continue;

            $filePath = $path . DIRECTORY_SEPARATOR . $file;
            $internalFilePath = $internalPath . '/' . $file;

            if (is_dir($filePath)) {
                $this->addDirToZip($zip, $filePath, $internalFilePath);
            } else {
                $zip->addFile($filePath, $internalFilePath);
            }
        }
    }

    public function view(Request $request)
    {
        if (!moduleEnabled('file_manager_module')) {
            return response()->json(['success' => false, 'message' => __('File Manager module is disabled.')], 400);
        }
        $path = $request->path;
        if (!file_exists($path) || is_dir($path)) {
            return redirect()->back()->with('error', __('File not found.'));
        }

        return redirect()->route('admin.file-manager.code-editor', ['path' => $path]);
    }

    public function download(Request $request)
    {
        if (!moduleEnabled('file_manager_module')) {
            return response()->json(['success' => false, 'message' => __('File Manager module is disabled.')], 400);
        }
        $path = $request->path;
        if (!file_exists($path) || is_dir($path)) {
            return redirect()->back()->with('error', __('File not found.'));
        }

        return response()->download($path);
    }

    private function generateBreadcrumbs($path, $base_path)
    {
        $relative = str_replace($base_path, '', $path);
        $parts = explode(DIRECTORY_SEPARATOR, trim($relative, DIRECTORY_SEPARATOR));
        $breadcrumbs = [];
        $current = $base_path;

        $breadcrumbs[] = ['name' => 'root', 'path' => $base_path];

        foreach ($parts as $part) {
            if (empty($part))
                continue;
            $current .= DIRECTORY_SEPARATOR . $part;
            $breadcrumbs[] = ['name' => $part, 'path' => $current];
        }

        return $breadcrumbs;
    }

    private function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    private function recursiveDelete($dir)
    {
        if (!file_exists($dir))
            return true;
        if (!is_dir($dir))
            return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..')
                continue;
            if (!$this->recursiveDelete($dir . DIRECTORY_SEPARATOR . $item))
                return false;
        }
        return rmdir($dir);
    }

    private function recursiveCopy($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . DIRECTORY_SEPARATOR . $file)) {
                    $this->recursiveCopy($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);
                } else {
                    copy($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);
                }
            }
        }
        closedir($dir);
    }
}
