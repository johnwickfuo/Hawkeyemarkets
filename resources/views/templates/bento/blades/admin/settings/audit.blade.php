@extends('templates.bento.blades.admin.layouts.admin')

@php
    $root = base_path();

    if (!function_exists('row')) {
        function row(string $label, bool $ok, string $details = ''): array
        {
            return ['label' => $label, 'ok' => $ok, 'details' => $details];
        }
    }

    if (!function_exists('humanBytes')) {
        function humanBytes(int $bytes): string
        {
            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
            $i = 0;
            while ($bytes >= 1024 && $i < count($units) - 1) {
                $bytes /= 1024;
                $i++;
            }
            return sprintf('%.2f %s', $bytes, $units[$i]);
        }
    }

    $checks = [];
    $warnings = [];
    $findings = [];

    $start = microtime(true);

    // 1) System Information
    $checks[] = row(
        'PHP version',
        version_compare(PHP_VERSION, '8.3', '>='),
        'Current: ' . PHP_VERSION . ' | Recommended: 8.3',
    );
    $checks[] = row('Laravel version', true, app()->version());
    $checks[] = row('Lozand version', true, '1.0.0');
    $checks[] = row('Server software', true, $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown');
    $checks[] = row(
        'Disk free space',
        (disk_free_space($root) ?: 0) > 1024 * 1024 * 200,
        humanBytes((int) (disk_free_space($root) ?: 0)),
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
        'zip',
    ];
    foreach ($required_extensions as $ext) {
        $loaded = extension_loaded($ext);
        $checks[] = row('PHP Ext: ' . strtoupper($ext), $loaded, $loaded ? 'loaded' : 'missing');
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
        $checks[] = row("Server: $key", $ok, "Current: {$config['current']} | Recommended: {$config['recommended']}");
    }

    // 2) APP_KEY + cipher sanity
    try {
        $cipher = (string) config('app.cipher');
        $key = (string) config('app.key');

        $supported = ['aes-128-cbc', 'aes-256-cbc', 'aes-128-gcm', 'aes-256-gcm'];
        $checks[] = row('APP_CIPHER supported', in_array($cipher, $supported, true), $cipher ?: '(empty)');

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

        $checks[] = row('APP_KEY length matches cipher', $keyOk, $keyDetails);
    } catch (Throwable $e) {
        $checks[] = row('APP_KEY / cipher check', false, $e->getMessage());
    }

    // 3) Critical directory permissions
    $folders = [
        'storage' => storage_path(),
        'storage/framework' => storage_path('framework'),
        'storage/app' => storage_path('app'),
        'storage/logs' => storage_path('logs'),
        'bootstrap/cache' => base_path('bootstrap/cache'),
    ];

    foreach ($folders as $name => $path) {
        $exists = file_exists($path);
        $perms = $exists ? substr(sprintf('%o', fileperms($path)), -3) : 'N/A';
        $writable = $exists && is_writable($path);
        $checks[] = row("Perms: $name", $writable, "Current: $perms | Recommended: 775/755");
    }

    $pubStoragePath = $root . '/public/storage';
    $isLink = is_link($pubStoragePath);
    $checks[] = row(
        'public/storage (symlink)',
        $isLink || is_dir($pubStoragePath),
        $isLink ? 'symlink OK' : (is_dir($pubStoragePath) ? 'dir exists' : 'missing'),
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
        $checks[] = row("File: $name", $exists, $exists ? 'Exists' : 'Missing');
    }

    // 4) .env exposure check (basic)
    $envPublic = file_exists($root . '/public/.env') || file_exists($root . '/public/.env.example');
    $checks[] = row('No .env file in public/', !$envPublic, $envPublic ? 'Found .env-like file in public/' : 'OK');

    // 5) DB connectivity
    try {
        $default = (string) config('database.default');
        $checks[] = row('DB default connection', $default !== '', $default ?: '(empty)');
        $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
        $checks[] = row('DB connection', $pdo !== null, 'Connected');
    } catch (Throwable $e) {
        $checks[] = row('DB connection', false, $e->getMessage());
    }

    // 6) Cache / Session / Queue sanity
    try {
        $checks[] = row('CACHE_DRIVER', (string) config('cache.default') !== '', (string) config('cache.default'));
        $checks[] = row('SESSION_DRIVER', (string) config('session.driver') !== '', (string) config('session.driver'));
        $checks[] = row('QUEUE_CONNECTION', (string) config('queue.default') !== '', (string) config('queue.default'));
    } catch (Throwable $e) {
        $checks[] = row('Cache/session/queue config', false, $e->getMessage());
    }

    // 7) App env/debug
    try {
        $env = (string) config('app.env');
        $debug = (bool) config('app.debug');
        $checks[] = row('APP_ENV', $env !== '', $env);
        if ($env === 'production') {
            $checks[] = row('APP_DEBUG off (production)', $debug === false, $debug ? 'TRUE (bad)' : 'false');
        } else {
            $checks[] = row('APP_DEBUG', true, $debug ? 'true' : 'false');
            $warnings[] = "APP_ENV is '{$env}'. Make sure this is intentional.";
        }
    } catch (Throwable $e) {
        $checks[] = row('APP_ENV/DEBUG', false, $e->getMessage());
    }

    // 8) Log file growth
    $logPath = $root . '/storage/logs/laravel.log';
    if (file_exists($logPath)) {
        $size = filesize($logPath) ?: 0;
        $checks[] = row('laravel.log size reasonable', $size < 50 * 1024 * 1024, humanBytes((int) $size));
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

        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));

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
                    if (empty($matches[0])) {
                        continue;
                    }

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
@endphp

@section('content')
    <div class="flex flex-col lg:flex-row min-h-[calc(100vh-160px)]">
        {{-- Settings Sidebar --}}
        <div class="w-full lg:w-80 shrink-0 border-b lg:border-b-0 lg:border-r border-white/5 flex flex-col pt-8 pr-8">
            <div id="sideBarSelector">
                @include("templates.$template.blades.admin.settings.partials.sidebar")
            </div>
        </div>

        {{-- Settings Content Area --}}
        <div class="flex-1 flex flex-col pt-8 lg:pl-16 overflow-y-auto custom-scrollbar" id="contentScrollContainer">
            <div class="max-w-5xl pb-24" id="printableAuditArea">
                <div class="flex items-center justify-between mb-12">
                    <div class="flex flex-col gap-1">
                        <h2 class="text-3xl font-light text-white tracking-tight leading-none">{{ __('Security Audit') }}
                        </h2>
                        <p class="text-slate-500 text-xs font-medium tracking-wide">
                            {{ __('Generated:') }} {{ date('Y-m-d H:i:s') }} <span class="mx-2">|</span>
                            {{ __('Elapsed:') }} {{ number_format($elapsed, 3) }}s
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('admin.settings.audit.pdf') }}" target="_blank"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 font-bold text-xs uppercase tracking-widest hover:bg-indigo-500/20 transition-colors shadow-lg shadow-indigo-500/5 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            {{ __('Download PDF') }}
                        </a>
                    </div>
                </div>

                {{-- Status Pills --}}
                <div class="flex items-center gap-4 mb-8">
                    <div
                        class="px-4 py-2 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">{{ __('PASS:') }}
                            {{ $okCount }}</span>
                    </div>
                    @if ($badCount > 0)
                        <div class="px-4 py-2 bg-red-500/10 border border-red-500/20 rounded-xl flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                            <span class="text-[10px] font-black text-red-500 uppercase tracking-widest">{{ __('FAIL:') }}
                                {{ $badCount }}</span>
                        </div>
                    @endif
                </div>

                {{-- Checks Table --}}
                <div class="mb-12">
                    <h3 class="text-xl font-medium text-white tracking-wide mb-6">{{ __('System Checks') }}</h3>
                    <div class="bg-white/5 border border-white/10 rounded-2xl overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-white/10 bg-white/5">
                                        <th
                                            class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                            {{ __('Status') }}</th>
                                        <th
                                            class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                            {{ __('Check') }}</th>
                                        <th
                                            class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                            {{ __('Details') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @foreach ($checks as $c)
                                        <tr class="hover:bg-white/5 transition-colors">
                                            <td class="py-4 px-6 w-32">
                                                @if ($c['ok'])
                                                    <span
                                                        class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-emerald-500/10 text-[10px] font-bold text-emerald-500 uppercase tracking-widest border border-emerald-500/20">{{ __('PASS') }}</span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-red-500/10 text-[10px] font-bold text-red-500 uppercase tracking-widest border border-red-500/20">{{ __('FAIL') }}</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-6 text-sm font-medium text-white">{{ $c['label'] }}</td>
                                            <td class="py-4 px-6 text-xs text-slate-400 font-mono">{{ $c['details'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Warnings --}}
                <div class="mb-12">
                    <h3 class="text-xl font-medium text-white tracking-wide mb-6">{{ __('Warnings') }}</h3>
                    @if (empty($warnings))
                        <div class="p-6 rounded-2xl border border-white/5 bg-white/[0.02] flex items-center gap-4">
                            <span
                                class="w-8 h-8 rounded-full bg-emerald-500/10 text-emerald-500 flex items-center justify-center border border-emerald-500/20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </span>
                            <p class="text-sm font-medium text-emerald-400">{{ __('No warnings detected.') }}</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach ($warnings as $w)
                                <div
                                    class="p-6 rounded-2xl border border-amber-500/20 bg-amber-500/5 flex items-start gap-4">
                                    <span
                                        class="w-8 h-8 rounded-full bg-amber-500/10 text-amber-500 flex items-center justify-center border border-amber-500/20 shrink-0 mt-0.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                            </path>
                                        </svg>
                                    </span>
                                    <p class="text-sm font-medium text-amber-500/90 leading-relaxed">{{ $w }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Findings --}}
                <div>
                    <h3 class="text-xl font-medium text-white tracking-wide mb-6">
                        {{ __('Security Findings (Light Scan)') }}</h3>
                    <div class="bg-white/5 border border-white/10 rounded-2xl overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-[800px]">
                                <thead>
                                    <tr class="border-b border-white/10 bg-white/5">
                                        <th
                                            class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-32">
                                            {{ __('Severity') }}</th>
                                        <th
                                            class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-48">
                                            {{ __('Finding') }}</th>
                                        <th
                                            class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                            {{ __('File') }}</th>
                                        <th
                                            class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                            {{ __('Note') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @foreach ($findings as $f)
                                        @php
                                            $sev = $f['severity'] ?? 'INFO';
                                            $clsBg = 'bg-slate-500/10';
                                            $clsText = 'text-slate-400';
                                            $clsBorder = 'border-slate-500/20';

                                            if ($sev === 'HIGH') {
                                                $clsBg = 'bg-red-500/10';
                                                $clsText = 'text-red-500';
                                                $clsBorder = 'border-red-500/20';
                                            } elseif ($sev === 'WARN') {
                                                $clsBg = 'bg-amber-500/10';
                                                $clsText = 'text-amber-500';
                                                $clsBorder = 'border-amber-500/20';
                                            } elseif ($sev === 'OK') {
                                                $clsBg = 'bg-emerald-500/10';
                                                $clsText = 'text-emerald-500';
                                                $clsBorder = 'border-emerald-500/20';
                                            }
                                        @endphp
                                        <tr class="hover:bg-white/5 transition-colors">
                                            <td class="py-4 px-6">
                                                <span
                                                    class="inline-flex items-center justify-center px-3 py-1 rounded-full {{ $clsBg }} {{ $clsText }} text-[10px] font-bold uppercase tracking-widest border {{ $clsBorder }}">
                                                    {{ $sev }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-6 text-sm font-medium text-white truncate break-words">
                                                {{ $f['name'] ?? '' }}</td>
                                            <td class="py-4 px-6 text-xs text-slate-400 font-mono break-all">
                                                {{ $f['file'] ?? '' }}</td>
                                            <td class="py-4 px-6 text-xs text-slate-500">{{ $f['note'] ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
