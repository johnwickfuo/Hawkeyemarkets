<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\CronJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class OveriewController extends Controller
{
    //index
    public function index()
    {
        $page_title = __('Settings');
        $template = config('site.template');
        return view("templates.$template.blades.admin.settings.index", compact('page_title', 'template'));
    }

    //legal
    public function legal()
    {
        $page_title = __('Legal Disclaimer');
        $template = config('site.template');
        return view("templates.$template.blades.admin.settings.legal", compact('page_title', 'template'));
    }

    //system
    public function system()
    {
        $page_title = __('System Settings');
        $template = config('site.template');

        $system_information = [
            'php_version' => [
                'current' => phpversion(),
                'recommended' => '8.3',
                'status' => version_compare(phpversion(), '8.3', '>='),
            ],
            'laravel_version' => [
                'current' => app()->version(),
                'recommended' => '12.x',
                'status' => true,
            ],
            'lozand_version' => [
                'current' => '1.0.0',
                'recommended' => '1.0.0',
                'status' => true,
            ],
            'server_software' => [
                'current' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                'status' => true,
            ],
        ];

        //require php extension
        $required_extensions = [
            'bcmath',
            'ctype',
            'curl',
            'dom',
            'fileinfo',
            'gd',
            'gmp',
            'json',
            'mbstring',
            'openssl',
            'pcre',
            'pdo',
            'tokenizer',
            'xml',
            'zip'
        ];

        $php_extension = [];
        foreach ($required_extensions as $ext) {
            $php_extension[$ext] = [
                'status' => extension_loaded($ext),
                'name' => strtoupper($ext),
            ];
        }

        // Helper to convert ini sizes to bytes
        $parse_size = function ($size) {
            $unit = preg_replace('/[^bkmgtp]/i', '', $size);
            $size = preg_replace('/[^0-9\.]/', '', $size);
            if ($unit) {
                return round($size * pow(1024, stripos('bkmgtp', $unit[0])));
            }
            return round($size);
        };

        //server config
        $server_config = [
            'post_max_size' => [
                'recommended' => '256M',
                'current' => ini_get('post_max_size'),
            ],
            'upload_max_filesize' => [
                'recommended' => '256M',
                'current' => ini_get('upload_max_filesize'),
            ],
            'max_execution_time' => [
                'recommended' => '300',
                'current' => ini_get('max_execution_time'),
            ],
            'max_input_time' => [
                'recommended' => '300',
                'current' => ini_get('max_input_time'),
            ],
            'memory_limit' => [
                'recommended' => '512M',
                'current' => ini_get('memory_limit'),
            ],
        ];

        foreach ($server_config as $key => &$config) {
            if ($key == 'max_execution_time' || $key == 'max_input_time') {
                $config['status'] = (int) $config['current'] >= (int) $config['recommended'] || (int) $config['current'] == 0;
            } else {
                $config['status'] = $parse_size($config['current']) >= $parse_size($config['recommended']);
            }
        }


        $folders = [
            'storage' => storage_path(),
            'storage/framework' => storage_path('framework'),
            'storage/app' => storage_path('app'),
            'storage/debugbar' => storage_path('debugbar'),
            'storage/untranslated' => storage_path('untranslated'),
            'storage/logs' => storage_path('logs'),
            'bootstrap/cache' => base_path('bootstrap/cache'),
        ];

        $folder_and_file_permissions = [];
        foreach ($folders as $name => $path) {
            $exists = file_exists($path);
            $perms = $exists ? substr(sprintf('%o', fileperms($path)), -3) : 'N/A';
            $folder_and_file_permissions[$name] = [
                'status' => $exists && (int) $perms >= 775,
                'recommended' => '775',
                'current' => $perms,
                'exists' => $exists,
            ];
        }


        // require files .env, database.sql and .htaccess
        $required_files = [
            '.env' => [
                'status' => file_exists(base_path('.env')),
                'path' => '.env'
            ],
            'public/.htaccess' => [
                'status' => file_exists(public_path('.htaccess')),
                'path' => 'public/.htaccess'
            ],
            '.htaccess' => [
                'status' => file_exists(base_path('.htaccess')),
                'path' => '.htaccess'
            ],
            'public/install/database.sql' => [
                'status' => file_exists(public_path('install/database.sql')),
                'path' => 'public/install/database.sql'
            ],
            'storage/app/public' => [
                'status' => file_exists(storage_path('app/public')),
                'path' => 'storage/app/public'
            ],
        ];

        // require functions
        $required_functions = [
            'symlink' => [
                'status' => function_exists('symlink'),
                'name' => 'symlink',
            ],
            'proc_open' => [
                'status' => function_exists('proc_open'),
                'name' => 'proc_open',
            ],
            'popen' => [
                'status' => function_exists('popen'),
                'name' => 'popen',
            ],
            'putenv' => [
                'status' => function_exists('putenv'),
                'name' => 'putenv',
            ],
            'chmod' => [
                'status' => function_exists('chmod'),
                'name' => 'chmod',
            ],
            'fsockopen' => [
                'status' => function_exists('fsockopen'),
                'name' => 'fsockopen',
            ],

        ];


        $system_settings_tab = [
            'system_information' => $system_information,
            'php_extension' => $php_extension,
            'server_config' => $server_config,
            'folder_and_file_permissions' => $folder_and_file_permissions,
            'required_files' => $required_files,
            'required_functions' => $required_functions,
        ];

        return view("templates.$template.blades.admin.settings.system", compact('page_title', 'template', 'system_settings_tab'));
    }

    /**
     * Clear System Cache
     */
    public function clearCache()
    {
        try {
            Artisan::call('optimize:clear');
            return response()->json(['success' => true, 'message' => __('System cache cleared successfully.')]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update Environment Variables
     */
    public function updateEnvSetting(Request $request)
    {
        $request->validate([
            'APP_DEBUG' => 'required|in:true,false',
            'APP_ENV' => 'required|string|in:local,development,testing,production,sandbox',
            'LOG_LEVEL' => 'required|string|in:debug,info,notice,warning,error,critical,alert,emergency',
        ]);

        // do not allow changes if the environment is sandbox
        $current_environt = config('app.env');
        if ($current_environt == 'sandbox') {
            return response()->json(['success' => false, 'message' => __('You cannot change the environment in sandbox mode.')], 400);
        }

        // sanddbox mode can only be set by manually editing .env file
        if ($request->APP_ENV == 'sandbox') {
            return response()->json(['success' => false, 'message' => __('You cannot change the environment to sandbox mode. Edit .env file manually.')], 400);
        }

        $replacements = [
            'APP_DEBUG' => $request->APP_DEBUG,
            'APP_ENV' => $request->APP_ENV,
            'LOG_LEVEL' => $request->LOG_LEVEL,
        ];

        foreach ($replacements as $key => $value) {
            updateEnv($key, $value);
        }

        return response()->json(['success' => true, 'message' => __('Environment updated successfully.')]);
    }




    // cron jobs
    public function cronJob()
    {
        $page_title = __('Cron Jobs');
        $template = config('site.template');

        $cron_health = CronJob::get();

        $cron_job_curl_commands = [
            "wget -q -O- " . route('utils.cronjob') . " >/dev/null 2>&1",
            "curl -s -o /dev/null " . route('utils.cronjob'),
        ];

        return view("templates.$template.blades.admin.settings.cron-jobs", compact(
            'page_title',
            'template',
            'cron_health',
            'cron_job_curl_commands'
        ));
    }

    // audit
    public function audit()
    {
        $page_title = __('Security Audit');
        $template = config('site.template');
        return view("templates.$template.blades.admin.settings.audit", compact('page_title', 'template'));
    }

    // audit pdf
    public function auditPdf()
    {
        $page_title = __('Security Audit Report');
        $template = config('site.template');

        // --- REPLICATE AUDIT LOGIC FOR PDF ---
        $root = base_path();

        $checks = [];
        $warnings = [];
        $findings = [];
        $start = microtime(true);

        $row = function (string $label, bool $ok, string $details = ''): array {
            return ['label' => $label, 'ok' => $ok, 'details' => $details];
        };

        $humanBytes = function (int $bytes): string {
            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
            $i = 0;
            while ($bytes >= 1024 && $i < count($units) - 1) {
                $bytes /= 1024;
                $i++;
            }
            return sprintf('%.2f %s', $bytes, $units[$i]);
        };

        // 1) System Information
        $checks[] = $row('PHP version', version_compare(PHP_VERSION, '8.3', '>='), 'Current: ' . PHP_VERSION . ' | Recommended: 8.3');
        $checks[] = $row('Laravel version', true, app()->version());
        $checks[] = $row('Lozand version', true, '1.0.0');
        $checks[] = $row('Server software', true, $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown');
        $checks[] = $row(
            'Disk free space',
            (disk_free_space($root) ?: 0) > 1024 * 1024 * 200,
            $humanBytes((int) (disk_free_space($root) ?: 0))
        );

        // 1.1) PHP Extensions
        $required_extensions = [
            'bcmath',
            'ctype',
            'curl',
            'dom',
            'fileinfo',
            'gd',
            'gmp',
            'json',
            'mbstring',
            'openssl',
            'pcre',
            'pdo',
            'tokenizer',
            'xml',
            'zip'
        ];
        foreach ($required_extensions as $ext) {
            $loaded = extension_loaded($ext);
            $checks[] = $row('PHP Ext: ' . strtoupper($ext), $loaded, $loaded ? 'loaded' : 'missing');
        }

        // 1.2) Server Config
        $parse_size = function ($size) {
            $size = (string) $size;
            $unit = preg_replace('/[^bkmgtp]/i', '', $size);
            $sizeValue = preg_replace('/[^0-9\.]/', '', $size);
            if ($unit) {
                return round((float) $sizeValue * pow(1024, stripos('bkmgtp', $unit[0])));
            }
            return round((float) $sizeValue);
        };

        $server_config = [
            'post_max_size' => ['recommended' => '256M', 'current' => ini_get('post_max_size')],
            'upload_max_filesize' => ['recommended' => '256M', 'current' => ini_get('upload_max_filesize')],
            'max_execution_time' => ['recommended' => '300', 'current' => ini_get('max_execution_time')],
            'max_input_time' => ['recommended' => '300', 'current' => ini_get('max_input_time')],
            'memory_limit' => ['recommended' => '512M', 'current' => ini_get('memory_limit')],
        ];

        foreach ($server_config as $key => $config) {
            if ($key == 'max_execution_time' || $key == 'max_input_time') {
                $ok = (int) $config['current'] >= (int) $config['recommended'] || (int) $config['current'] == 0;
            } else {
                $ok = $parse_size($config['current']) >= $parse_size($config['recommended']);
            }
            $checks[] = $row("Server: $key", $ok, "Current: {$config['current']} | Recommended: {$config['recommended']}");
        }

        // 2) APP_KEY + cipher sanity
        try {
            $cipher = (string) config('app.cipher');
            $key = (string) config('app.key');

            $supported = ['aes-128-cbc', 'aes-256-cbc', 'aes-128-gcm', 'aes-256-gcm'];
            $checks[] = $row('APP_CIPHER supported', in_array($cipher, $supported, true), $cipher ?: '(empty)');

            $keyOk = false;
            $keyDetails = $key ?: '(empty)';
            $decodedLen = null;

            if (str_starts_with($key, 'base64:')) {
                $raw = base64_decode(substr($key, 7), true);
                if ($raw !== false) {
                    $decodedLen = strlen($raw);
                    if (str_contains($cipher, '256')) {
                        $keyOk = $decodedLen === 32;
                    }
                    if (str_contains($cipher, '128')) {
                        $keyOk = $decodedLen === 16;
                    }
                    $keyDetails = "base64 key decoded length: {$decodedLen} bytes";
                } else {
                    $keyDetails = 'base64 decode failed';
                }
            } else {
                $decodedLen = strlen($key);
                if (str_contains($cipher, '256')) {
                    $keyOk = $decodedLen === 32;
                }
                if (str_contains($cipher, '128')) {
                    $keyOk = $decodedLen === 16;
                }
                $keyDetails = "raw key length: {$decodedLen} bytes (consider using base64:...)";
                $warnings[] = 'APP_KEY is not base64-prefixed. Laravel commonly uses base64: keys.';
            }

            $checks[] = $row('APP_KEY length matches cipher', $keyOk, $keyDetails);
        } catch (\Throwable $e) {
            $checks[] = $row('APP_KEY / cipher check', false, $e->getMessage());
        }

        // 3) Critical directory permissions
        $folders = [
            'storage' => storage_path(),
            'storage/framework' => storage_path('framework'),
            'storage/app' => storage_path('app'),
            'storage/debugbar' => storage_path('debugbar'),
            'storage/untranslated' => storage_path('untranslated'),
            'storage/logs' => storage_path('logs'),
            'bootstrap/cache' => base_path('bootstrap/cache'),
        ];

        foreach ($folders as $name => $path) {
            $exists = file_exists($path);
            $perms = $exists ? substr(sprintf('%o', fileperms($path)), -3) : 'N/A';
            $status = $exists && (int) $perms >= 775;
            $checks[] = $row("Perms: $name", $status, "Current: $perms | Recommended: 775");
        }

        $pubStoragePath = $root . '/public/storage';
        $isLink = is_link($pubStoragePath);
        $checks[] = $row(
            'public/storage (symlink)',
            $isLink || is_dir($pubStoragePath),
            $isLink ? 'symlink OK' : (is_dir($pubStoragePath) ? 'dir exists' : 'missing')
        );
        if (!$isLink) {
            $warnings[] = 'public/storage is not a symlink. Consider: php artisan storage:link';
        }

        // 3.1) Required files
        $required_files = [
            '.env' => base_path('.env'),
            'public/.htaccess' => public_path('.htaccess'),
            '.htaccess' => base_path('.htaccess'),
            'public/install/database.sql' => public_path('install/database.sql'),
            'storage/app/public' => storage_path('app/public'),
        ];

        foreach ($required_files as $name => $path) {
            $exists = file_exists($path);
            $checks[] = $row("File: $name", $exists, $exists ? 'Exists' : 'Missing');
        }

        // 4) .env exposure check (basic)
        $envPublic = file_exists($root . '/public/.env') || file_exists($root . '/public/.env.example');
        $checks[] = $row('No .env file in public/', !$envPublic, $envPublic ? 'Found .env-like file in public/' : 'OK');

        // 5) DB connectivity
        try {
            $default = (string) config('database.default');
            $checks[] = $row('DB default connection', $default !== '', $default ?: '(empty)');
            $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
            $checks[] = $row('DB connection', $pdo !== null, 'Connected');
        } catch (\Throwable $e) {
            $checks[] = $row('DB connection', false, $e->getMessage());
        }

        // 6) Cache / Session / Queue sanity
        try {
            $checks[] = $row('CACHE_DRIVER', (string) config('cache.default') !== '', (string) config('cache.default'));
            $checks[] = $row('SESSION_DRIVER', (string) config('session.driver') !== '', (string) config('session.driver'));
            $checks[] = $row('QUEUE_CONNECTION', (string) config('queue.default') !== '', (string) config('queue.default'));
        } catch (\Throwable $e) {
            $checks[] = $row('Cache/session/queue config', false, $e->getMessage());
        }

        // 7) App env/debug
        try {
            $env = (string) config('app.env');
            $debug = (bool) config('app.debug');
            $checks[] = $row('APP_ENV', $env !== '', $env);
            if ($env === 'production') {
                $checks[] = $row('APP_DEBUG off (production)', $debug === false, $debug ? 'TRUE (bad)' : 'false');
            } else {
                $checks[] = $row('APP_DEBUG', true, $debug ? 'true' : 'false');
                $warnings[] = "APP_ENV is '{$env}'. Make sure this is intentional.";
            }
        } catch (\Throwable $e) {
            $checks[] = $row('APP_ENV/DEBUG', false, $e->getMessage());
        }

        // 8) Log file growth
        $logPath = $root . '/storage/logs/laravel.log';
        if (file_exists($logPath)) {
            $size = filesize($logPath) ?: 0;
            $checks[] = $row('laravel.log size reasonable', $size < 50 * 1024 * 1024, $humanBytes((int) $size));
            if ($size >= 50 * 1024 * 1024) {
                $warnings[] = 'laravel.log is large. Consider rotating/clearing logs.';
            }
        } else {
            $warnings[] = 'No storage/logs/laravel.log found (may be OK depending on logging config).';
        }

        // 9) Lightweight security pattern scan (Blade + PHP)
        $scanDirs = [$root . '/app', $root . '/resources/views', $root . '/routes'];

        $patterns = [
            [
                'name' => 'Blade raw echo {!! !!}',
                'regex' => '/\{!!\s*.*?\s*!!\}/s',
                'severity' => 'WARN',
                'note' => 'Raw output can cause XSS if user data is passed in. Prefer {{ }}.',
            ],
            ['name' => 'eval()', 'regex' => '/\beval\s*\(/', 'severity' => 'HIGH', 'note' => 'Avoid eval().'],
            [
                'name' => 'unserialize() (unsafe)',
                'regex' => '/\bunserialize\s*\(/',
                'severity' => 'HIGH',
                'note' => 'Unserialize on untrusted input can lead to RCE.',
            ],
            [
                'name' => 'preg_replace /e modifier',
                'regex' => '/preg_replace\s*\(.*\/e[\'"]\s*,/i',
                'severity' => 'HIGH',
                'note' => 'Deprecated and dangerous execution.',
            ],
            [
                'name' => 'shell_exec/exec/system/passthru',
                'regex' => '/\b(shell_exec|exec|system|passthru)\s*\(/',
                'severity' => 'WARN',
                'note' => 'Review command execution sources and escaping.',
            ],
            [
                'name' => 'DB::raw()',
                'regex' => '/DB::raw\s*\(/',
                'severity' => 'WARN',
                'note' => 'Review for SQL injection risks.',
            ],
        ];

        $maxFindings = 200;
        $foundCount = 0;

        foreach ($scanDirs as $dir) {
            if (!is_dir($dir)) {
                continue;
            }

            $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS));

            foreach ($it as $file) {
                if (!$file->isFile()) {
                    continue;
                }

                $path = $file->getPathname();
                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                if (!in_array($ext, ['php', 'blade.php'], true) && !str_ends_with($path, '.blade.php')) {
                    continue;
                }

                // Prevent self-matching false positives for the audit endpoints
                if (str_ends_with(str_replace('\\', '/', $path), 'audit.blade.php')) {
                    continue;
                }

                $content = @file_get_contents($path);
                if ($content === false) {
                    continue;
                }

                foreach ($patterns as $p) {
                    if (preg_match_all($p['regex'], $content, $matches, PREG_OFFSET_CAPTURE)) {
                        if (empty($matches[0]))
                            continue;

                        $lineNumbers = [];
                        foreach ($matches[0] as $match) {
                            $offset = $match[1];
                            $matchStr = $match[0];
                            $startLine = substr_count($content, "\n", 0, $offset) + 1;
                            $newlinesInMatch = substr_count($matchStr, "\n");

                            if ($newlinesInMatch > 0) {
                                $endLine = $startLine + $newlinesInMatch;
                                $lineNumbers[] = "{$startLine}-{$endLine}";
                            } else {
                                $lineNumbers[] = (string) $startLine;
                            }
                        }

                        $lineNumbers = array_unique($lineNumbers);
                        $linesStr = empty($lineNumbers) ? '' : ':' . implode(', ', $lineNumbers);

                        $findings[] = [
                            'severity' => $p['severity'],
                            'name' => $p['name'],
                            'file' => str_replace($root . '/', '', $path) . $linesStr,
                            'note' => $p['note'],
                        ];
                        $foundCount += count($lineNumbers);
                        if ($foundCount >= $maxFindings) {
                            break 3;
                        }
                    }
                }
            }
        }

        if ($foundCount === 0) {
            $findings[] = [
                'severity' => 'OK',
                'name' => 'Pattern scan',
                'file' => '-',
                'note' => 'No matches found in scanned directories.',
            ];
        }

        $elapsed = microtime(true) - $start;

        $okCount = 0;
        $badCount = 0;
        foreach ($checks as $c) {
            if ($c['ok']) {
                $okCount++;
            } else {
                $badCount++;
            }
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("templates.$template.blades.admin.pdf.audit", compact(
            'page_title',
            'checks',
            'warnings',
            'findings',
            'elapsed',
            'okCount',
            'badCount'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('security-audit-report-' . date('Y-m-d-H-i-s') . '.pdf');
    }
}


