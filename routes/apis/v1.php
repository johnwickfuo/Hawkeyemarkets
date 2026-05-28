<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\Payments\NowpaymentController;
use App\Http\Controllers\User\Withdrawal\NowpaymentController as WithdrawalNowpaymentController;

Route::prefix('webhooks')->name('webhooks.')->group(function () {
    Route::post('nowpayments/deposit/{transaction_reference}', [NowpaymentController::class, 'nowpaymentIpnHandler'])->name('nowpayments.deposit');
    Route::post('nowpayments/withdrawal/{transaction_reference}', [WithdrawalNowpaymentController::class, 'nowpaymentIpnHandler'])->name('nowpayments.withdrawal');
});