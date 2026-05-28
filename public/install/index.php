<?php

/**
 * Standalone Installer for Lozand
 * Operating independently of Laravel to ensure requirement checks run first.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$current_step = $_GET['step'] ?? 'intro';
$storagePath = __DIR__ . '/../../storage';
$basePath = __DIR__ . '/../../';

// Basic safety: if installed.json exists, block installer
if (file_exists($storagePath . '/installed.json')) {
    header('Location: /');
    exit;
}

/**
 * Step Configuration & Validation
 */
$stepOrder = [
    'intro',
    'requirements',
    'server_config',
    'permissions',
    'files_functions',
    'license',
    'database',
    'admin'
];

$allSteps = [
    'intro' => 'Welcome',
    'requirements' => 'Requirements',
    'server_config' => 'Server Configuration',
    'permissions' => 'Permissions',
    'files_functions' => 'Files & Functions',
    'license' => 'License',
    'database' => 'Database Setup',
    'admin' => 'Admin Setup'
];

// Initialize progress if not set
if (!isset($_SESSION['completed_steps'])) {
    $_SESSION['completed_steps'] = ['intro'];
}

// Function to mark a step as completed
function completeStep($stepName)
{
    if (!in_array($stepName, $_SESSION['completed_steps'])) {
        $_SESSION['completed_steps'][] = $stepName;
    }
}

// Validation: Prevent skipping steps via URL
$currentIdx = array_search($current_step, $stepOrder);
if ($currentIdx === false) {
    header('Location: ?step=intro');
    exit;
}

// Find the furthest step the user is allowed to reach
$maxStepIdx = 0;
foreach ($stepOrder as $idx => $s) {
    if (in_array($s, $_SESSION['completed_steps'])) {
        $maxStepIdx = $idx;
    } else {
        break;
    }
}

// If user tries to access a step beyond what's completed + 1
if ($currentIdx > $maxStepIdx + 1) {
    $nextAllowedStep = $stepOrder[$maxStepIdx + ($maxStepIdx < count($stepOrder) - 1 ? 1 : 0)];
    header("Location: ?step=$nextAllowedStep");
    exit;
}

$step = $current_step;

/**
 * Helper to update .env
 */
function updateEnv(array $data, $basePath)
{
    $envPath = $basePath . '.env';
    if (file_exists($envPath)) {
        $content = file_get_contents($envPath);
        foreach ($data as $key => $value) {
            // Ensure value is quoted if it's not numeric or already quoted
            $quotedValue = $value;
            if (!is_numeric($value) && (strpos($value, '"') !== 0 || strrpos($value, '"') !== strlen($value) - 1)) {
                $quotedValue = '"' . str_replace('"', '\\"', $value) . '"';
            }

            if (preg_match("/^{$key}=/m", $content)) {
                $content = preg_replace("/^{$key}=.*/m", "{$key}={$quotedValue}", $content);
            } else {
                $content .= "\n{$key}={$quotedValue}";
            }
        }
        file_put_contents($envPath, $content);
    }
}

$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);

// Handle Post Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'next_step') {
        $step_to_complete = $_POST['current_step'] ?? '';
        completeStep($step_to_complete);

        $currentIdx = array_search($step_to_complete, $stepOrder);
        $nextStep = $stepOrder[$currentIdx + 1] ?? $step_to_complete;

        header("Location: ?step=$nextStep");
        exit;
    }

    if ($action === 'activate_license') {
        $product_key = $_POST['purchase_code'] ?? '';
        $domain = $_SERVER['HTTP_HOST'];

        // try {
        //     $url = 'https://lozand.com/api/v1/license/activate';

        //     // Standard PHP cURL for standalone compatibility
        //     $ch = curl_init($url);
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //     curl_setopt($ch, CURLOPT_POST, true);
        //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        //         'license_key' => $product_key,
        //         'domain' => $domain,
        //     ]));
        //     curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        //     $response = curl_exec($ch);
        //     $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //     curl_close($ch);

        //     $response_json = json_decode($response, true);

        //     if ($httpCode == 200 || $httpCode == 201) {
        //         // Success
        //         // Save plain key to temp file for post-Artisan encryption
        //         file_put_contents($storagePath . '/temp-key.txt', $product_key);

        //         completeStep('license'); // Unlock database step

        //         header('Location: ?step=database');
        //         exit;
        //     } else {
        //         $_SESSION['error'] = $response_json['message'] ?? 'Invalid license key or activation failed.';
        //         header('Location: ?step=license');
        //         exit;
        //     }
        // } catch (Exception $e) {
        //     $_SESSION['error'] = "Activation Error: " . $e->getMessage();
        //     header('Location: ?step=license');
        //     exit;
        // }

        // Success
        // Save plain key to temp file for post-Artisan encryption
        file_put_contents($storagePath . '/temp-key.txt', $product_key);

        completeStep('license'); // Unlock database step

        header('Location: ?step=database');
        exit;
    }

    if ($action === 'database_setup') {
        $db_host = $_POST['db_host'];
        $db_port = $_POST['db_port'];
        $db_name = $_POST['db_name'];
        $db_user = $_POST['db_user'];
        $db_pass = $_POST['db_password'] ?? '';
        $force_reset = isset($_POST['force_reset']) && $_POST['force_reset'] == '1';

        try {
            // First update env so Laravel can use these credentials
            updateEnv([
                'DB_HOST' => $db_host,
                'DB_PORT' => $db_port,
                'DB_DATABASE' => $db_name,
                'DB_USERNAME' => $db_user,
                'DB_PASSWORD' => $db_pass,
                'APP_ENV' => 'local',
                'APP_DEBUG' => 'true'
            ], $basePath);

            // Bootstrap Laravel
            if (!file_exists($basePath . 'vendor/autoload.php')) {
                throw new Exception("Vendor folder not found. Please run 'composer install'.");
            }
            require $basePath . 'vendor/autoload.php';
            $app = require_once $basePath . 'bootstrap/app.php';
            $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

            // Dynamic config sync: Update Laravel's runtime config with the user-provided credentials
            config([
                'database.connections.mysql.host' => $db_host,
                'database.connections.mysql.port' => $db_port,
                'database.connections.mysql.database' => $db_name,
                'database.connections.mysql.username' => $db_user,
                'database.connections.mysql.password' => $db_pass,
            ]);
            \Illuminate\Support\Facades\DB::purge('mysql');

            // at this point the newly stored db config is not available in request so all database query/action will fail

            // Check if ANY tables already exist
            $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
            if (count($tables) > 0 && !$force_reset) {
                $_SESSION['db_collision'] = true;
                $_SESSION['db_params'] = $_POST;
                $_SESSION['error'] = "The database already contains existing tables.";
                header('Location: ?step=database');
                exit;
            }

            // Force Reset: Drop all tables
            if ($force_reset) {
                $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
                $dbNameKey = "Tables_in_" . $db_name;

                \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
                foreach ($tables as $table) {
                    $tableName = $table->$dbNameKey;
                    \Illuminate\Support\Facades\Schema::dropIfExists($tableName);
                }
                \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            }

            // Import SQL if exists using DB facade
            $sqlFile = __DIR__ . '/database.sql';
            if (file_exists($sqlFile)) {
                $sql = file_get_contents($sqlFile);
                \Illuminate\Support\Facades\DB::unprepared($sql);
            }

            completeStep('database'); // Unlock admin step

            header('Location: ?step=admin');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = "Database Setup Failed: " . $e->getMessage();
            header('Location: ?step=database');
            exit;
        }
    }

    if ($action === 'admin_setup') {
        $name = $_POST['name'];
        $user = $_POST['username'];
        $email = $_POST['email'];
        $pass = $_POST['password'];

        // Now we can try to load Laravel to use Hash and Artisan
        try {
            if (!file_exists($basePath . 'vendor/autoload.php')) {
                throw new Exception("Vendor folder not found. Please run 'composer install'.");
            }
            require $basePath . 'vendor/autoload.php';
            $app = require_once $basePath . 'bootstrap/app.php';
            $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

            // Run migrations and other tasks
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);

            // Clean up old storage folder if it exists (prevents storage:link from being skipped)
            $publicStoragePath = $basePath . 'public/storage';
            if (file_exists($publicStoragePath)) {
                \Illuminate\Support\Facades\File::deleteDirectory($publicStoragePath);
            }

            \Illuminate\Support\Facades\Artisan::call('storage:link', ['--force' => true]);
            \Illuminate\Support\Facades\Artisan::call('optimize:clear');
            \Illuminate\Support\Facades\Artisan::call('key:generate', ['--force' => true]);

            // Now handle the Product Key encryption with the NEW APP_KEY
            $tempKeyFile = $storagePath . '/temp-key.txt';
            if (file_exists($tempKeyFile)) {
                $plainKey = file_get_contents($tempKeyFile);

                // Read the freshly generated key from .env
                $envContent = file_get_contents($basePath . '.env');
                if (preg_match('/^APP_KEY=["\']?(.*?)["\']?$/m', $envContent, $matches)) {
                    $newAppKey = trim($matches[1]);

                    // Manually instantiate encrypter as the facade might be stale
                    $encrypter = new \Illuminate\Encryption\Encrypter(
                        base64_decode(substr($newAppKey, 7)), // remove base64:
                        config('app.cipher') ?? 'AES-256-CBC'
                    );

                    $encrypted = $encrypter->encrypt($plainKey);
                    updateEnv(['PRODUCT_KEY' => $encrypted], $basePath);
                }
                @unlink($tempKeyFile);
            }

            \Illuminate\Support\Facades\DB::table('admins')->truncate();
            \Illuminate\Support\Facades\DB::table('admins')->insert([
                'name' => $name,
                'username' => $user,
                'email' => $email,
                'password' => \Illuminate\Support\Facades\Hash::make($pass),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            updateEnv([
                'APP_ENV' => 'production',
                'APP_DEBUG' => 'false',
                'APP_URL' => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST']
            ], $basePath);

            file_put_contents($storagePath . '/installed.json', json_encode([
                'installation_date' => date('Y-m-d H:i:s'),
                'version' => '1.0.0'
            ]));

            $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
            $host = $_SERVER['HTTP_HOST'];
            // dirname($_SERVER['SCRIPT_NAME']) is /folder/install, we need /folder
            $baseUrl = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/\\');
            header("Location: $protocol://$host$baseUrl/admin/login?installed=true");
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = "Final Setup Failed: " . $e->getMessage();
            header('Location: ?step=admin');
            exit;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lozand | System Installation</title>
    <link rel="icon" type="image/png" href="/assets/images/favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --bg-dark: #020617;
            --card-bg: rgba(15, 23, 42, 0.6);
            --card-border: rgba(255, 255, 255, 0.05);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent-glow: rgba(99, 102, 241, 0.15);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            overflow-x: hidden;
        }

        .glass-panel {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--card-border);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .step-pill {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .step-pill.active {
            background: rgba(99, 102, 241, 0.1);
            border-color: rgba(99, 102, 241, 0.3);
            color: #a5b4fc;
        }

        .step-pill.active::after {
            content: '';
            position: absolute;
            left: 0;
            top: 25%;
            height: 50%;
            width: 3px;
            background: var(--primary);
            border-radius: 0 4px 4px 0;
            box-shadow: 0 0 10px var(--primary);
        }

        .step-pill.completed {
            background: rgba(16, 185, 129, 0.05);
            border-color: rgba(16, 185, 129, 0.2);
            color: #6ee7b7;
        }

        .premium-btn {
            background: var(--primary);
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .premium-btn:hover:not(:disabled) {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.3);
        }

        .premium-btn:active:not(:disabled) {
            transform: translateY(0);
        }

        .premium-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            grayscale: 1;
        }

        .input-field {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s ease;
        }

        .input-field:focus {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(99, 102, 241, 0.4);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            outline: none;
        }

        .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 0.6s cubic-bezier(0.5, 0.1, 0.5, 0.9) infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .loading .spinner {
            display: block;
        }

        .loading .btn-text {
            display: none;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6 bg-[#020617]">
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div
            class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-indigo-600/20 rounded-full blur-[120px] animate-pulse">
        </div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-purple-600/20 rounded-full blur-[120px] animate-pulse"
            style="animation-delay: 2s;"></div>
    </div>

    <div class="w-full max-w-5xl relative z-10 transition-all duration-700 ease-out">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Sidebar -->
            <div class="lg:col-span-3 space-y-4">
                <div class="mb-10 px-2 text-center lg:text-left">
                    <img src="/assets/images/logo-rectangle.png" alt="Lozand Logo"
                        class="h-10 w-auto mb-3 mx-auto lg:mx-0 drop-shadow-2xl">
                    <div class="flex items-center gap-2 justify-center lg:justify-start">
                        <span class="h-px w-8 bg-indigo-500/30"></span>
                        <p class="text-[10px] text-indigo-400 uppercase tracking-[0.2em] font-bold">Secure Installer</p>
                    </div>
                </div>

                <nav class="space-y-2">
                    <?php
                    $foundCurrent = false;
                    $idx = 1;
                    foreach ($allSteps as $key => $name):
                        $isActive = ($step === $key);
                        if ($isActive)
                            $foundCurrent = true;
                        $isCompleted = !$foundCurrent && !$isActive;
                    ?>
                        <div
                            class="step-pill group px-4 py-3.5 rounded-2xl border border-white/5 flex items-center gap-4 cursor-default
                            <?= $isActive ? 'active shadow-lg shadow-indigo-500/10' : ($isCompleted ? 'completed' : 'text-slate-500 opacity-40') ?>">
                            <span class="w-6 h-6 rounded-lg border border-current flex items-center justify-center text-[10px] font-bold transition-all duration-300
                                <?= $isActive ? 'bg-indigo-500 text-white border-transparent' : '' ?>">
                                <?= $isCompleted ? '✓' : $idx++ ?>
                            </span>
                            <span class="text-sm font-semibold tracking-tight"><?= $name ?></span>
                        </div>
                    <?php endforeach; ?>
                </nav>
            </div>

            <!-- Main Content Container -->
            <div class="lg:col-span-9">
                <div class="glass-panel p-10 rounded-[32px] min-h-[600px] flex flex-col relative overflow-hidden">
                    <!-- Subtle Interior Glow -->
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl"></div>

                    <?php if ($error): ?>
                        <div
                            class="mb-8 p-5 rounded-2xl bg-red-500/5 border border-red-500/20 flex gap-4 items-center animate-shake">
                            <div
                                class="w-10 h-10 rounded-full bg-red-500/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <p class="text-sm text-red-200/80 font-medium"><?= $error ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="flex-1 relative z-10">
                        <?php if ($step === 'intro'): ?>
                            <div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
                                <div class="flex items-center gap-6 mb-10">
                                    <div
                                        class="w-16 h-16 rounded-[24px] bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 shadow-inner">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04M12 20.13V22m0-18V2m0 18a9 9 0 000-18m0 18a9 9 0 010-18" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-3xl font-extrabold text-white tracking-tight mb-1">Licensing &
                                            Disclosure</h2>
                                        <p class="text-slate-400 font-medium">Review the terms of service to proceed with
                                            your premium setup.</p>
                                    </div>
                                </div>

                                <div class="space-y-8">
                                    <div
                                        class="p-8 rounded-[28px] bg-black/40 border border-white/5 text-sm text-slate-400 leading-relaxed max-h-[300px] overflow-y-auto custom-scrollbar-styling">
                                        <div class="grid gap-8">
                                            <div class="space-y-3">
                                                <h3 class="text-base font-bold text-white flex items-center gap-2">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                                    1. Software License
                                                </h3>
                                                <p class="pl-3.5 border-l border-white/5">This software is granted under a
                                                    single-domain license. Usage is restricted to one (1) production
                                                    environment per license key.</p>
                                            </div>
                                            <div class="space-y-3">
                                                <h3 class="text-base font-bold text-white flex items-center gap-2">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                                    2. Restrictions
                                                </h3>
                                                <p class="pl-3.5 border-l border-white/5">Redistribution, resale, or
                                                    sub-licensing of the source code is strictly prohibited. Bypassing
                                                    license validation will result in immediate termination.</p>
                                            </div>
                                            <div class="space-y-3">
                                                <h3 class="text-base font-bold text-white flex items-center gap-2">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                                    3. Risk Disclosure
                                                </h3>
                                                <p class="pl-3.5 border-l border-white/5">The user assumes all risks
                                                    associated with software deployment. We are not liable for financial
                                                    loss or security breaches resulting from improper usage.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="group flex items-start gap-4 p-6 rounded-2xl bg-white/5 border border-white/5 hover:border-indigo-500/30 transition-all duration-300">
                                        <div class="pt-1">
                                            <input type="checkbox" id="agree-check"
                                                class="w-5 h-5 rounded-lg border-white/10 bg-black/40 text-indigo-500 focus:ring-indigo-500/20 focus:ring-offset-0 transition-all cursor-pointer">
                                        </div>
                                        <label for="agree-check"
                                            class="text-sm text-slate-300 cursor-pointer select-none leading-relaxed">
                                            I have read and agree to the <strong class="text-white">Licensing Terms</strong>
                                            and <strong class="text-white">Risk Disclosures</strong>. I understand that
                                            proceeding signifies legal acceptance.
                                        </label>
                                    </div>

                                    <div class="pt-2">
                                        <button onclick="proceedToRequirements()" id="start-btn" disabled
                                            class="premium-btn w-full py-5 rounded-2xl font-bold text-white shadow-2xl opacity-50 cursor-not-allowed transition-all flex items-center justify-center gap-3 group">
                                            <span class="btn-text flex items-center gap-2">
                                                Accept & Start Installation
                                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                </svg>
                                            </span>
                                            <div class="spinner"></div>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <style>
                                .custom-scrollbar-styling::-webkit-scrollbar {
                                    width: 4px;
                                }

                                .custom-scrollbar-styling::-webkit-scrollbar-thumb {
                                    background: rgba(255, 255, 255, 0.05);
                                    border-radius: 10px;
                                }

                                .custom-scrollbar-styling::-webkit-scrollbar-thumb:hover {
                                    background: rgba(255, 255, 255, 0.1);
                                }
                            </style>

                            <script>
                                const agreeCheck = document.getElementById('agree-check');
                                const startBtn = document.getElementById('start-btn');

                                agreeCheck.addEventListener('change', () => {
                                    if (agreeCheck.checked) {
                                        startBtn.disabled = false;
                                        startBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                                    } else {
                                        startBtn.disabled = true;
                                        startBtn.classList.add('opacity-50', 'cursor-not-allowed');
                                    }
                                });

                                function proceedToRequirements() {
                                    if (!startBtn.disabled) {
                                        startBtn.disabled = true;
                                        startBtn.classList.add('loading');
                                        const form = document.createElement('form');
                                        form.method = 'POST';
                                        form.innerHTML = `
                                        <input type="hidden" name="action" value="next_step">
                                        <input type="hidden" name="current_step" value="intro">
                                    `;
                                        document.body.appendChild(form);
                                        form.submit();
                                    }
                                }
                            </script>

                        <?php elseif ($step === 'requirements'): ?>
                            <?php
                            $phpVer = phpversion();
                            $reqExts = ['ctype', 'curl', 'dom', 'fileinfo', 'gd', 'json', 'mbstring', 'openssl', 'pcre', 'pdo', 'tokenizer', 'xml', 'zip'];
                            $allMet = version_compare($phpVer, '8.3.0', '>=');
                            ?>
                            <div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
                                <div class="flex items-center gap-6 mb-10">
                                    <div
                                        class="w-16 h-16 rounded-[24px] bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-3xl font-extrabold text-white tracking-tight mb-1">System
                                            Requirements</h2>
                                        <p class="text-slate-400 font-medium">Verifying core dependencies and PHP
                                            environment.</p>
                                    </div>
                                </div>

                                <div class="space-y-6 mb-10">
                                    <div
                                        class="flex items-center justify-between p-6 rounded-2xl bg-white/[0.02] border border-white/5">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-8 h-8 rounded-lg bg-indigo-500/20 flex items-center justify-center text-indigo-400 text-xs font-bold">
                                                PHP</div>
                                            <span class="font-bold text-slate-200">PHP Version (>= 8.3.0)</span>
                                        </div>
                                        <span
                                            class="text-sm font-mono <?= version_compare($phpVer, '8.3.0', '>=') ? 'text-emerald-400' : 'text-rose-400' ?> font-bold bg-white/5 px-3 py-1 rounded-lg border border-white/5"><?= $phpVer ?></span>
                                    </div>

                                    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                                        <?php foreach ($reqExts as $ext):
                                            $status = extension_loaded($ext);
                                            if (!$status)
                                                $allMet = false; ?>
                                            <div
                                                class="flex items-center justify-between p-4 rounded-xl bg-white/[0.015] border border-white/5 hover:bg-white/5 transition-colors">
                                                <span class="text-xs font-bold text-slate-400"><?= strtoupper($ext) ?></span>
                                                <div
                                                    class="w-5 h-5 rounded-full flex items-center justify-center <?= $status ? 'bg-emerald-400/20 text-emerald-400' : 'bg-rose-400/20 text-rose-400' ?>">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4"
                                                            d="<?= $status ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12' ?>" />
                                                    </svg>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <div class="pt-6 border-t border-white/5 flex gap-4">
                                    <button
                                        onclick="this.disabled=true; this.classList.add('loading'); window.location.reload()"
                                        class="flex-1 bg-white/5 border border-white/10 text-white rounded-2xl py-4 font-bold flex items-center justify-center gap-2 hover:bg-white/10 transition-all group">
                                        <span class="btn-text flex items-center gap-2">
                                            <svg class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            Recheck
                                        </span>
                                        <div class="spinner"></div>
                                    </button>

                                    <?php if ($allMet): ?>
                                        <form method="POST" class="flex-1">
                                            <input type="hidden" name="action" value="next_step">
                                            <input type="hidden" name="current_step" value="requirements">
                                            <button type="submit"
                                                class="premium-btn w-full py-4 rounded-2xl font-bold text-white shadow-xl flex items-center justify-center gap-2 group">
                                                <span class="btn-text flex items-center gap-2">
                                                    Continue
                                                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                    </svg>
                                                </span>
                                                <div class="spinner"></div>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>

                        <?php elseif ($step === 'server_config'): ?>
                            <?php
                            $parse_size = function ($size) {
                                if (!$size || $size == -1)
                                    return PHP_INT_MAX;
                                $unit = preg_replace('/[^bkmgtp]/i', '', $size);
                                $size = preg_replace('/[^0-9\.]/', '', $size);
                                if ($unit)
                                    return round($size * pow(1024, stripos('bkmgtp', $unit[0])));
                                return round($size);
                            };

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

                            $all_config_met = true;
                            foreach ($server_config as $key => &$config) {
                                if ($key == 'max_execution_time' || $key == 'max_input_time') {
                                    $config['status'] = (int) $config['current'] >= (int) $config['recommended'] || (int) $config['current'] == 0;
                                } else {
                                    $config['status'] = $parse_size($config['current']) >= $parse_size($config['recommended']);
                                }
                                if (!$config['status']) {
                                    $all_config_met = true;
                                }
                            }
                            ?>
                            <div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
                                <div class="flex items-center gap-6 mb-10">
                                    <div
                                        class="w-16 h-16 rounded-[24px] bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-3xl font-extrabold text-white tracking-tight mb-1">Server Config
                                        </h2>
                                        <p class="text-slate-400 font-medium">Reviewing your PHP runtime configuration
                                            against recommendations.</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10">
                                    <?php foreach ($server_config as $key => $config): ?>
                                        <div
                                            class="flex items-center justify-between p-6 rounded-2xl bg-white/[0.02] border border-white/5 hover:bg-white/[0.04] transition-all">
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-[10px] text-indigo-400 uppercase tracking-widest font-bold mb-1"><?= str_replace('_', ' ', $key) ?></span>
                                                <span
                                                    class="text-sm font-bold text-slate-200"><?= ucwords(str_replace('_', ' ', $key)) ?></span>
                                                <span class="text-[10px] text-slate-500 mt-1">Recommended:
                                                    <?= $config['recommended'] ?></span>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <span
                                                    class="text-sm font-mono <?= $config['status'] ? 'text-white' : 'text-rose-400' ?> bg-white/5 px-3 py-1.5 rounded-lg border border-white/5"><?= $config['current'] ?></span>
                                                <div
                                                    class="w-5 h-5 rounded-full flex items-center justify-center <?= $config['status'] ? 'bg-emerald-400/20 text-emerald-400' : 'bg-rose-400/20 text-rose-400' ?>">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4"
                                                            d="<?= $config['status'] ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12' ?>" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="pt-6 border-t border-white/5 flex gap-4">
                                    <button
                                        onclick="this.disabled=true; this.classList.add('loading'); window.location.reload()"
                                        class="w-20 bg-white/5 border border-white/10 text-white rounded-2xl py-4 font-bold flex items-center justify-center hover:bg-white/10 transition-all group">
                                        <svg class="w-5 h-5 group-hover:rotate-180 transition-transform duration-500"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>
                                    <?php if ($all_config_met): ?>
                                        <form method="POST" class="flex-1">
                                            <input type="hidden" name="action" value="next_step">
                                            <input type="hidden" name="current_step" value="server_config">
                                            <button type="submit"
                                                class="premium-btn w-full py-4 rounded-2xl font-bold text-white shadow-xl flex items-center justify-center gap-2 group">
                                                <span class="btn-text flex items-center gap-2">
                                                    Continue
                                                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                    </svg>
                                                </span>
                                                <div class="spinner"></div>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <div
                                            class="flex-1 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-2xl flex items-center justify-center text-xs font-bold uppercase tracking-widest">
                                            Requirements Not Met
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                        <?php elseif ($step === 'permissions'): ?>
                            <?php
                            $dirs = [
                                'storage' => $storagePath,
                                'storage/framework' => $storagePath . '/framework',
                                'storage/app' => $storagePath . '/app',
                                'storage/debugbar' => $storagePath . '/debugbar',
                                'storage/untranslated' => $storagePath . '/untranslated',
                                'storage/logs' => $storagePath . '/logs',
                                'bootstrap/cache' => $basePath . 'bootstrap/cache'
                            ];
                            $permissions = [];
                            foreach ($dirs as $name => $path) {
                                $exists = file_exists($path);
                                $perms = $exists ? substr(sprintf('%o', fileperms($path)), -3) : 'N/A';
                                $permissions[$name] = [
                                    'path' => $path,
                                    'status' => $exists && (int) $perms >= 775,
                                    'recommended' => '775',
                                    'current' => $perms,
                                    'exists' => $exists,
                                ];
                            }
                            $permissions_met = !in_array(false, array_column($permissions, 'status'));
                            ?>
                            <div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
                                <div class="flex items-center gap-6 mb-10">
                                    <div
                                        class="w-16 h-16 rounded-[24px] bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-3xl font-extrabold text-white tracking-tight mb-1">File Permissions
                                        </h2>
                                        <p class="text-slate-400 font-medium">Configuring write access for core system
                                            directories.</p>
                                    </div>
                                </div>

                                <div class="space-y-4 mb-10">
                                    <?php foreach ($permissions as $name => $data): ?>
                                        <div
                                            class="group flex items-center justify-between p-5 rounded-2xl bg-white/[0.02] border border-white/5 hover:bg-white/[0.04] transition-all duration-300">
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center <?= $data['status'] ? 'text-emerald-400' : 'text-rose-400' ?>">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                                    </svg>
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="font-bold text-slate-200"><?= $name ?></span>
                                                    <span class="text-[10px] text-slate-500 mt-1">Recommended:
                                                        <?= $data['recommended'] ?></span>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-4">
                                                <div class="flex flex-col items-end mr-2">
                                                    <span
                                                        class="text-[10px] text-slate-500 uppercase tracking-widest font-bold mb-1">Current</span>
                                                    <span
                                                        class="text-sm font-mono <?= $data['status'] ? 'text-white' : 'text-rose-400' ?> bg-white/5 px-2 py-1 rounded-lg border border-white/5"><?= $data['current'] ?></span>
                                                </div>

                                                <?php if ($data['status']): ?>
                                                    <div
                                                        class="flex items-center gap-2 text-emerald-400 font-bold text-xs uppercase tracking-widest bg-emerald-400/10 px-4 py-1.5 rounded-full border border-emerald-400/20">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        Ready
                                                    </div>
                                                <?php else: ?>
                                                    <div
                                                        class="flex items-center gap-2 text-rose-400 font-bold text-xs uppercase tracking-widest bg-rose-400/10 px-4 py-1.5 rounded-full border border-rose-400/20">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                                d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        Fix Required
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="pt-6 border-t border-white/5 flex gap-4">
                                    <button
                                        onclick="this.disabled=true; this.classList.add('loading'); window.location.reload()"
                                        class="flex-1 bg-white/5 border border-white/10 text-white rounded-2xl py-4 font-bold flex items-center justify-center gap-2 hover:bg-white/10 transition-all group">
                                        <span class="btn-text flex items-center gap-2">
                                            <svg class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            Recheck
                                        </span>
                                        <div class="spinner"></div>
                                    </button>

                                    <?php if ($permissions_met): ?>
                                        <form method="POST" class="flex-1">
                                            <input type="hidden" name="action" value="next_step">
                                            <input type="hidden" name="current_step" value="permissions">
                                            <button type="submit"
                                                class="premium-btn w-full py-4 rounded-2xl font-bold text-white shadow-xl flex items-center justify-center gap-2 group">
                                                <span class="btn-text flex items-center gap-2">
                                                    Next Step
                                                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                    </svg>
                                                </span>
                                                <div class="spinner"></div>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <button disabled
                                            class="flex-1 bg-rose-500/10 text-rose-400 rounded-2xl py-4 font-bold cursor-not-allowed uppercase tracking-wider text-xs border border-rose-500/20">Awaiting
                                            Fixes</button>
                                    <?php endif; ?>
                                </div>
                            </div>

                        <?php elseif ($step === 'files_functions'): ?>
                            <?php
                            $files = [
                                '.env' => $basePath . '.env',
                                'public/.htaccess' => __DIR__ . '/../.htaccess',
                                '.htaccess' => $basePath . '.htaccess',
                                'public/install/database.sql' => __DIR__ . '/../install/database.sql',
                                'storage/app/public' => $storagePath . '/app/public'
                            ];
                            $funcs = ['symlink', 'proc_open', 'popen', 'putenv', 'chmod', 'fsockopen'];
                            $allMet = true;
                            ?>
                            <div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
                                <div class="flex items-center gap-6 mb-10">
                                    <div
                                        class="w-16 h-16 rounded-[24px] bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-3xl font-extrabold text-white tracking-tight mb-1">Files & Functions
                                        </h2>
                                        <p class="text-slate-400 font-medium">Validating essential system utilities and core
                                            files.</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                                    <div class="space-y-4">
                                        <h3 class="text-xs font-bold text-indigo-400 uppercase tracking-widest pl-2">System
                                            Files</h3>
                                        <?php foreach ($files as $name => $path):
                                            $exists = file_exists($path);
                                            if (!$exists)
                                                $allMet = false; ?>
                                            <div
                                                class="flex items-center justify-between p-4 rounded-2xl bg-white/[0.02] border border-white/5">
                                                <span class="text-sm font-medium text-slate-300"><?= $name ?></span>
                                                <div
                                                    class="w-2 h-2 rounded-full <?= $exists ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]' : 'bg-rose-500 animate-pulse shadow-[0_0_8px_rgba(244,63,94,0.5)]' ?>">
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="space-y-4">
                                        <h3 class="text-xs font-bold text-indigo-400 uppercase tracking-widest pl-2">Server
                                            Functions</h3>
                                        <?php foreach ($funcs as $f):
                                            $exists = function_exists($f);
                                            if (!$exists)
                                                $allMet = false; ?>
                                            <div
                                                class="flex items-center justify-between p-4 rounded-2xl bg-white/[0.02] border border-white/5">
                                                <span class="text-sm font-mono text-slate-300"><?= $f ?>()</span>
                                                <span
                                                    class="text-[10px] font-bold <?= $exists ? 'text-emerald-400' : 'text-rose-400' ?>"><?= $exists ? 'READY' : 'DISABLED' ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <div class="pt-6 border-t border-white/5 flex gap-4">
                                    <button
                                        onclick="this.disabled=true; this.classList.add('loading'); window.location.reload()"
                                        class="w-32 bg-white/5 border border-white/10 text-white rounded-2xl py-4 font-bold flex items-center justify-center gap-2 hover:bg-white/10 transition-all group">
                                        <svg class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Recheck
                                    </button>
                                    <?php if ($allMet): ?>
                                        <form method="POST" class="flex-1">
                                            <input type="hidden" name="action" value="next_step">
                                            <input type="hidden" name="current_step" value="files_functions">
                                            <button type="submit"
                                                class="premium-btn w-full py-4 rounded-2xl font-bold text-white shadow-xl flex items-center justify-center gap-2 group">
                                                <span class="btn-text flex items-center gap-2">
                                                    Continue
                                                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                    </svg>
                                                </span>
                                                <div class="spinner"></div>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <div
                                            class="flex-1 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-2xl flex items-center justify-center text-xs font-bold uppercase tracking-widest">
                                            Awaiting Fixes
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                        <?php elseif ($step === 'license'): ?>
                            <div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
                                <div class="flex items-center gap-6 mb-10">
                                    <div
                                        class="w-16 h-16 rounded-[24px] bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04M12 20.13V22m0-18V2m0 18a9 9 0 000-18m0 18a9 9 0 010-18" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-3xl font-extrabold text-white tracking-tight mb-1">License
                                            Activation</h2>
                                        <p class="text-slate-400 font-medium">Enter your purchase code to verify your
                                            license.</p>
                                    </div>
                                </div>

                                <div class="p-6 rounded-2xl bg-indigo-500/5 border border-indigo-500/10 mb-8">
                                    <h3
                                        class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Activation Guide
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div class="space-y-1">
                                            <span class="text-[10px] font-bold text-slate-500 uppercase">Step 01</span>
                                            <p class="text-sm text-slate-300 leading-tight">Login to <a
                                                    href="https://lozand.com" target="_blank"
                                                    class="text-indigo-400 hover:text-indigo-300 font-bold transition-colors underline decoration-indigo-500/30">lozand.com</a>
                                            </p>
                                        </div>
                                        <div class="space-y-1">
                                            <span class="text-[10px] font-bold text-slate-500 uppercase">Step 02</span>
                                            <p class="text-sm text-slate-300 leading-tight">Locate your <span
                                                    class="text-white font-bold">Purchase Code</span></p>
                                        </div>
                                        <div class="space-y-1">
                                            <span class="text-[10px] font-bold text-slate-500 uppercase">Step 03</span>
                                            <p class="text-sm text-slate-300 leading-tight">Paste and click <span
                                                    class="text-white font-bold">Activate</span></p>
                                        </div>
                                    </div>


                                </div>

                                <div class="w-full p-3 rounded-2xl bg-red-500/10">
                                    <p>Nulled by PlaceHolder Dev</p>
                                </div>

                                <form method="POST" class="space-y-8">
                                    <input type="hidden" name="action" value="activate_license">
                                    <div class="space-y-2">
                                        <label
                                            class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Purchase
                                            Code</label>
                                        <div class="relative group">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-slate-500 group-focus-within:text-indigo-400 transition-colors"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                                </svg>
                                            </div>
                                            <input type="text" name="purchase_code" placeholder="XXXX-XXXX-XXXX-XXXX"
                                                required
                                                value="69aaa164-e9d0-8333-b4c7-43d457cf069d"
                                                class="w-full bg-white/[0.03] border border-white/10 rounded-2xl pl-14 pr-5 py-5 text-white text-lg font-mono tracking-widest focus:outline-none focus:border-indigo-500/50 focus:bg-white/[0.05] transition-all placeholder:text-slate-600">
                                        </div>
                                    </div>
                                    <div class="pt-4">
                                        <button type="submit"
                                            class="premium-btn w-full py-5 rounded-2xl font-bold text-white shadow-2xl flex items-center justify-center gap-3 group">
                                            <span class="btn-text flex items-center gap-2">
                                                Verify & Activate License
                                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                </svg>
                                            </span>
                                            <div class="spinner"></div>
                                        </button>
                                    </div>
                                </form>
                            </div>

                        <?php elseif ($step === 'database'): ?>
                            <?php
                            $collision = $_SESSION['db_collision'] ?? false;
                            $params = $_SESSION['db_params'] ?? [];
                            ?>
                            <div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
                                <div class="flex items-center gap-6 mb-10">
                                    <div
                                        class="w-16 h-16 rounded-[24px] bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-3xl font-extrabold text-white tracking-tight mb-1">Database Setup
                                        </h2>
                                        <p class="text-slate-400 font-medium">Finalizing the data connection for your
                                            platform.</p>
                                    </div>
                                </div>

                                <?php if ($collision): ?>
                                    <div
                                        class="mb-10 p-6 rounded-[28px] bg-amber-500/10 border border-amber-500/20 animate-shake">
                                        <div class="flex items-start gap-4">
                                            <div
                                                class="w-10 h-10 rounded-full bg-amber-500/20 flex items-center justify-center text-amber-500 flex-shrink-0">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-base font-bold text-amber-400 mb-1">Attention: Existing Tables
                                                    Found</h3>
                                                <p class="text-sm text-amber-200/60 leading-relaxed mb-4">
                                                    The database already contains system tables. Proceeding with <span
                                                        class="text-amber-400 font-bold">"Force Reset"</span> will permanently
                                                    delete all existing data in this database.
                                                </p>
                                                <div class="flex items-center gap-3">
                                                    <form method="POST">
                                                        <input type="hidden" name="action" value="database_setup">
                                                        <input type="hidden" name="force_reset" value="1">
                                                        <?php foreach ($params as $k => $v): ?>
                                                            <input type="hidden" name="<?= $k ?>"
                                                                value="<?= htmlspecialchars($v) ?>">
                                                        <?php endforeach; ?>
                                                        <button type="submit"
                                                            class="bg-amber-500 hover:bg-amber-600 text-black px-5 py-2 rounded-xl text-xs font-bold transition-all shadow-lg shadow-amber-500/20 flex items-center justify-center gap-2 group">
                                                            <span class="btn-text">Force Reset & Install</span>
                                                            <div class="spinner !border-black/10 !border-t-black"></div>
                                                        </button>
                                                    </form>
                                                    <a href="?step=database" onclick="<?php unset($_SESSION['db_collision']);
                                                                                        unset($_SESSION['db_params']); ?>"
                                                        class="text-xs font-bold text-slate-400 hover:text-white transition-colors pl-2">
                                                        Cancel & Re-enter
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <form method="POST"
                                    class="space-y-6 <?= $collision ? 'opacity-30 pointer-events-none transition-opacity' : '' ?>">
                                    <input type="hidden" name="action" value="database_setup">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label
                                                class="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1">Database
                                                Host</label>
                                            <input type="text" name="db_host"
                                                value="<?= htmlspecialchars($params['db_host'] ?? '127.0.0.1') ?>" required
                                                class="w-full bg-white/[0.03] border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:border-indigo-500/50 transition-all font-mono text-sm">
                                        </div>
                                        <div class="space-y-2">
                                            <label
                                                class="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1">Port</label>
                                            <input type="text" name="db_port"
                                                value="<?= htmlspecialchars($params['db_port'] ?? '3306') ?>" required
                                                class="w-full bg-white/[0.03] border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:border-indigo-500/50 transition-all font-mono text-sm">
                                        </div>
                                        <div class="space-y-2 md:col-span-2">
                                            <label
                                                class="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1">Database
                                                Name</label>
                                            <input type="text" name="db_name"
                                                value="<?= htmlspecialchars($params['db_name'] ?? '') ?>" required
                                                placeholder="lozand_production"
                                                class="w-full bg-white/[0.03] border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:border-indigo-500/50 transition-all font-mono text-sm">
                                        </div>
                                        <div class="space-y-2">
                                            <label
                                                class="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1">Username</label>
                                            <input type="text" name="db_user"
                                                value="<?= htmlspecialchars($params['db_user'] ?? '') ?>" required
                                                placeholder="root"
                                                class="w-full bg-white/[0.03] border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:border-indigo-500/50 transition-all font-mono text-sm">
                                        </div>
                                        <div class="space-y-2">
                                            <label
                                                class="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1">Password</label>
                                            <input type="password" name="db_password"
                                                value="<?= htmlspecialchars($params['db_password'] ?? '') ?>"
                                                placeholder="••••••••"
                                                class="w-full bg-white/[0.03] border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:border-indigo-500/50 transition-all font-mono text-sm">
                                        </div>
                                    </div>

                                    <div class="pt-8 border-t border-white/5">
                                        <button type="submit"
                                            class="premium-btn w-full py-5 rounded-2xl font-bold text-white shadow-2xl flex items-center justify-center gap-3 group">
                                            <span class="btn-text flex items-center gap-2">
                                                Test Connectivity & Setup
                                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                </svg>
                                            </span>
                                            <div class="spinner"></div>
                                        </button>
                                    </div>
                                </form>
                            </div>

                        <?php elseif ($step === 'admin'): ?>
                            <div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
                                <div class="flex items-center gap-6 mb-10">
                                    <div
                                        class="w-16 h-16 rounded-[24px] bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-3xl font-extrabold text-white tracking-tight mb-1">Master Account
                                        </h2>
                                        <p class="text-slate-400 font-medium">Create your primary administrator credentials.
                                        </p>
                                    </div>
                                </div>

                                <form method="POST" class="space-y-6">
                                    <input type="hidden" name="action" value="admin_setup">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label
                                                class="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1">Full
                                                Name</label>
                                            <input type="text" name="name" required placeholder="John Doe"
                                                class="w-full bg-white/[0.03] border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:border-indigo-500/50 transition-all">
                                        </div>
                                        <div class="space-y-2">
                                            <label
                                                class="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1">Username</label>
                                            <input type="text" name="username" required placeholder="admin"
                                                class="w-full bg-white/[0.03] border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:border-indigo-500/50 transition-all">
                                        </div>
                                        <div class="space-y-2 md:col-span-2">
                                            <label
                                                class="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1">Email
                                                Address</label>
                                            <input type="email" name="email" required placeholder="admin@example.com"
                                                class="w-full bg-white/[0.03] border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:border-indigo-500/50 transition-all">
                                        </div>
                                        <div class="space-y-2 md:col-span-2">
                                            <label
                                                class="text-[10px] font-bold text-slate-500 uppercase tracking-widest ml-1">Secure
                                                Password</label>
                                            <div class="relative group">
                                                <input type="password" name="password" id="admin-password" required
                                                    minlength="8" placeholder="Minimum 8 characters"
                                                    class="w-full bg-white/[0.03] border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:border-indigo-500/50 transition-all">
                                                <button type="button"
                                                    onclick="togglePasswordVisibility('admin-password', this)"
                                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-indigo-400 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path class="eye-open" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path class="eye-open" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        <path class="eye-closed hidden" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2"
                                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 014.13-5.555M9.9 4.244A10.05 10.05 0 0112 4c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.012 2.345M19.071 19.071L4.929 4.929M12 12l0 0" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pt-8 border-t border-white/5">
                                        <button type="submit"
                                            class="premium-btn w-full py-5 rounded-2xl font-bold text-white shadow-2xl flex items-center justify-center gap-3 group">
                                            <span class="btn-text flex items-center gap-2">
                                                Finalize Installation
                                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </span>
                                            <div class="spinner"></div>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (this.checkValidity()) {
                        const btn = this.querySelector('button[type="submit"]');
                        if (btn) {
                            btn.disabled = true;
                            btn.classList.add('loading');
                        }
                    }
                });
            });

            function togglePasswordVisibility(inputId, btn) {
                const input = document.getElementById(inputId);
                const open = btn.querySelector('.eye-open');
                const closed = btn.querySelector('.eye-closed');

                if (input.type === 'password') {
                    input.type = 'text';
                    open.querySelectorAll('path').forEach(p => p.classList.add('hidden'));
                    closed.classList.remove('hidden');
                } else {
                    input.type = 'password';
                    open.querySelectorAll('path').forEach(p => p.classList.remove('hidden'));
                    closed.classList.add('hidden');
                }
            }
        </script>
</body>

</html>