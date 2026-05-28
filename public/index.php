<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Check if the application is installed
if (!file_exists(__DIR__ . '/../storage/installed.json') && !str_contains($_SERVER['REQUEST_URI'], '/install')) {
    // $installUrl = str_replace(basename($_SERVER['SCRIPT_NAME']), 'install/index.php', $_SERVER['SCRIPT_NAME']);
    $url = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $url .= $_SERVER['HTTP_HOST'] . '/install/index.php';
    header("Location: $url");
    exit;
}

// Register the Composer autoloader...
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->handleRequest(Request::capture());
