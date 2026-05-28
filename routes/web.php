<?php

use App\Http\Controllers\Front\CapitalInstrumentController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\SectorController;
use App\Http\Controllers\Front\SelfTradingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('investment-plans', [HomeController::class, 'investmentPlans'])->name('investment-plans');
Route::name('sectors.')->prefix('sectors')->group(function () {
    $sectors = config('sectors');
    foreach ($sectors as $sector => $sector_data) {
        $formated_sector = str_replace(['_and_', '_'], ['-', '-'], $sector);
        Route::get($formated_sector, [SectorController::class, 'index'])->name($formated_sector);
    }
});

Route::get('about', [HomeController::class, 'aboutUs'])->name('about');
Route::get('license', [HomeController::class, 'license'])->name('license');

Route::get('lang/{locale}', function ($locale) {
    $supported_locales = config('languages');
    if (!array_key_exists($locale, $supported_locales)) {
        return redirect()->back();
    }
    session(['locale' => $locale]);
    // if the user is logged, update the 'lang"
    if (Auth::guard('admin')->check()) {
        Auth::guard('admin')->user()->lang = $locale;
        Auth::guard('admin')->user()->save();
    } elseif (Auth::check()) {
        Auth::user()->lang = $locale;
        Auth::user()->save();
    }
    return redirect()->back();
})->name('lang.switch');


Route::name('trading.')->prefix('trading')->group(function () {
    Route::get('futures/{ticker?}', [SelfTradingController::class, 'futuresTrading'])->name('futures');
    Route::get('margin/{ticker?}', [SelfTradingController::class, 'marginTrading'])->name('margin');
    Route::get('forex/{ticker?}/{mode?}', [SelfTradingController::class, 'forexTrading'])->name('forex');
    // Route::get('commodity', $placeholder)->name('commodity');
});

Route::name('capital-instruments.')->prefix('capital-instruments')->group(function () {
    Route::get('stocks', [CapitalInstrumentController::class, 'stocks'])->name('stocks');
    Route::get('bonds', [CapitalInstrumentController::class, 'bonds'])->name('bonds');
    Route::get('mutual-funds', [CapitalInstrumentController::class, 'mutualFunds'])->name('mutual-funds');
    Route::get('etfs', [CapitalInstrumentController::class, 'etfs'])->name('etfs');
});

Route::get('contact', [HomeController::class, 'contact'])->name('contact');
Route::post('contact', [HomeController::class, 'contactSend'])->name('contact.send');

Route::get('privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('terms-and-conditions', [HomeController::class, 'termsAndConditions'])->name('terms-and-conditions');
Route::get('risk-disclosure', [HomeController::class, 'riskDisclosure'])->name('risk-disclosure');


// Auth Routes
