<?php

use App\Models\NotificationMessage;
use App\Models\Setting;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// clear cache
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    // avoid redirect loop
    $previous_url = url()->previous();
    if ($previous_url == url()->current()) {
        return redirect()->route('home')->with('success', __('Cache cleared successfully'));
    }
    return redirect()->back()->with('success', __('Cache cleared successfully'));
})->name('clear-cache');



// create storage link forcefully
Route::get('create-storage-link', function () {
    Artisan::call('storage:link', [
        '--force' => true,
    ]);
    return response()->json([
        'status' => 'success',
        'message' => 'Storage link created successfully',
    ]);
})->name('create-storage-link');



// start schedule
Route::get('cronjob', function () {
    Artisan::call('lozand:start-schedule');
    return response()->json([
        'status' => 'success',
        'message' => 'Cron job started successfully',
    ]);
})->name('cronjob');


