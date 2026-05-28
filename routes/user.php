<?php

use App\Http\Controllers\User\KycController;
use App\Http\Controllers\User\Auth\LoginController;
use App\Http\Controllers\User\Auth\RegisterController;
use App\Http\Controllers\User\CapitalInstruments\BondsController;
use App\Http\Controllers\User\CapitalInstruments\CommercialPapersController;
use App\Http\Controllers\User\CapitalInstruments\EtfsController;
use App\Http\Controllers\User\CapitalInstruments\MutualFundsController;
use App\Http\Controllers\User\CapitalInstruments\StocksController;
use App\Http\Controllers\User\CapitalInstruments\TreasuryBillsController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\DepositController;
use App\Http\Controllers\User\ReferralController;
use App\Http\Controllers\User\Trading\ForexController;
use App\Http\Controllers\User\InvestmentController;
use App\Http\Controllers\User\Payments\NowpaymentController;
use App\Http\Controllers\User\SectorController;
use App\Http\Controllers\User\Trading\FuturesController;
use App\Http\Controllers\User\Trading\MarginController;
use App\Http\Controllers\User\Trading\TradingAccountController;
use App\Http\Controllers\User\TransactionController;
use App\Http\Controllers\User\WithdrawalController;
use App\Http\Controllers\User\Withdrawal\NowpaymentController as NowpaymentWithdrawalController;
use Illuminate\Support\Facades\Route;


// Only Authenticated Users can access these routes
Route::middleware(['guest'])->group(function () {
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'registerValidate'])->name('register-validate');
    Route::post('/email-verification', [RegisterController::class, 'emailVerification'])->name('email-verification');
    Route::post('/resend-verification', [RegisterController::class, 'resendVerification'])->name('resend-verification');
    Route::post('/register-cancel', [RegisterController::class, 'registerCancel'])->name('register-cancel');
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'loginValidate'])->name('login.validate');
    Route::get('/login/{provider}', [LoginController::class, 'redirectToSocial'])->name('login.social');
    Route::get('/login/{provider}/callback', [LoginController::class, 'handleSocialCallback'])->name('login.social.callback');

    // Forgot Password
    Route::get('/forgot-password', [LoginController::class, 'forgotPassword'])->name('forgot-password');
    Route::post('/forgot-password', [LoginController::class, 'sendResetCode'])->name('forgot-password.send');
    Route::get('/forgot-password/otp', [LoginController::class, 'resetOtp'])->name('forgot-password.otp');
    Route::post('/forgot-password/otp', [LoginController::class, 'validateResetOtp'])->name('forgot-password.otp.validate');
    Route::get('/reset-password', [LoginController::class, 'resetPasswordForm'])->name('reset-password');
    Route::post('/reset-password', [LoginController::class, 'updatePassword'])->name('reset-password.update');
});

// OTP routes — user is already authenticated but not yet OTP-verified
Route::middleware(['auth'])->prefix('login')->name('login.')->group(function () {
    Route::get('/verify/otp', [LoginController::class, 'otp'])->name('otp');
    Route::post('/verify/otp', [LoginController::class, 'validateOtp'])->name('otp.validate');
    Route::post('/verify/resend-otp', [LoginController::class, 'resendOtp'])->name('resend-otp');
});


// Only Authenticated users can access these routes
Route::prefix('user')->middleware(['auth', 'otp.verified', 'user.status', 'user.kyc'])->group(function () {
    // logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->withoutMiddleware(['otp.verified', 'user.status', 'user.kyc']);
    //Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/notification-mark-as-read', [DashboardController::class, 'notificationMarkAsRead'])->name('notification-mark-as-read');
    Route::post('/onboarding', [DashboardController::class, 'onboarding'])->name('onboarding')->middleware('sandbox');
    // KYC
    Route::get('/kyc', [KycController::class, 'index'])->name('kyc');
    Route::post('/kyc', [KycController::class, 'submitKyc'])->name('kyc.submit')->middleware('sandbox');


    // Deposits
    Route::prefix('deposits')->name('deposits.')->group(function () {
        Route::get('/', [DepositController::class, 'index'])->name('index');
        Route::get('/approved', [DepositController::class, 'byScope'])->name('approved');
        Route::get('/pending', [DepositController::class, 'byScope'])->name('pending');
        Route::get('/failed', [DepositController::class, 'byScope'])->name('failed');
        Route::get('/new', [DepositController::class, 'newDeposit'])->name('new');
        Route::post('/new', [DepositController::class, 'startNewDeposit'])->name('new-validate');
        Route::get('/new/manual', [DepositController::class, 'manualPayment'])->name('new.manual');
        Route::post('/new/manual', [DepositController::class, 'manualPaymentValidate'])->name('new.manual-validate');
        Route::post('/new/manual/cancel', [DepositController::class, 'manualPaymentCancel'])->name('new.manual-cancel');
        Route::get("/view/{transaction_reference}", [DepositController::class, 'viewDeposit'])->name('view');
        Route::get('pay/{pay}/{transaction_reference}', [DepositController::class, 'payNow'])->name('pay');
        Route::get('receipt/{transaction_reference}', [DepositController::class, 'downloadReceipt'])->name('receipt');

        // Third Party payments
        Route::prefix('new')->name('new.')->group(function () {
            // Nowpayments
            Route::get('/nowpayments', [NowpaymentController::class, 'index'])->name('nowpayments');
            Route::post('/nowpayments', [NowpaymentController::class, 'nowpaymentsValidate'])->name('nowpayments-validate');
        });
    });

    // Investments
    Route::prefix('investments')->name('investments.')->group(function () {
        Route::get('/', [InvestmentController::class, 'index'])->name('index');
        Route::get('/new', [InvestmentController::class, 'newInvestment'])->name('new');
        Route::post('/new', [InvestmentController::class, 'newInvestmentValidation'])->name('new-validate');
        Route::get('/earnings', [InvestmentController::class, 'investmentEarnings'])->name('earnings');
    });

    // sections
    $sectors = array_keys(config('interests'));
    foreach ($sectors as $sector) {
        Route::get('/sectors/' . str_replace('_', '-', $sector), [SectorController::class, 'index'])->name('sectors.' . $sector);
    }


    // Trading
    Route::prefix('trading')->name('trading.')->group(function () {
        Route::get('/account', [TradingAccountController::class, 'index'])->name('account');
        Route::post('/account/store', [TradingAccountController::class, 'store'])->name('account.store');
        Route::post('/account/transfer', [TradingAccountController::class, 'transfer'])->name('account.transfer');
        Route::get('/futures/{ticker?}', [FuturesController::class, 'index'])->name('futures');
        Route::post('/futures/trade', [FuturesController::class, 'trade'])->name('futures.trade');
        Route::post('/futures/cancel-order', [FuturesController::class, 'cancelOrder'])->name('futures.cancel-order');
        Route::get('/margin/{ticker?}', [MarginController::class, 'index'])->name('margin');
        Route::post('/margin/trade', [MarginController::class, 'trade'])->name('margin.trade');
        Route::post('/margin/cancel-order', [MarginController::class, 'cancelOrder'])->name('margin.cancel-order');
        Route::get('/forex/live/{ticker?}', [ForexController::class, 'index'])->name('forex.live');
        Route::get('/forex/demo/{ticker?}', [ForexController::class, 'index'])->name('forex.demo');
        Route::post('/forex/trade', [ForexController::class, 'trade'])->name('forex.trade');
        Route::post('/forex/cancel-order', [ForexController::class, 'cancelOrder'])->name('forex.cancel-order');
        Route::post('/forex/close-position', [ForexController::class, 'closePosition'])->name('forex.close-position');
        // Route::get('/commodity', [CommodityController::class, 'index'])->name('commodity');
    });


    // Capital Instruments
    Route::prefix('capital-instruments')->name('capital-instruments.')->group(function () {
        // Stocks
        Route::get('/stocks', [StocksController::class, 'index'])->name('stocks');
        Route::get('/stocks/{ticker}', [StocksController::class, 'buyStock'])->name('stocks.buy');
        Route::post('/stocks/{ticker}', [StocksController::class, 'buyStockValidate'])->name('stocks.buy-validate');
        Route::post('/stocks/{ticker}/sell', [StocksController::class, 'sellStock'])->name('stocks.sell');
        // Commercial Papers
        Route::get('commercial-papers', [CommercialPapersController::class, 'index'])->name('commercial-papers');
        // Bonds
        Route::get('bonds', [BondsController::class, 'index'])->name('bonds');
        Route::get('bonds/{ticker}', [BondsController::class, 'buyBond'])->name('bonds.buy');
        Route::post('bonds/{ticker}', [BondsController::class, 'buyBondValidate'])->name('bonds.buy-validate');
        Route::post('bonds/{ticker}/sell', [BondsController::class, 'sellBond'])->name('bonds.sell');
        // ETFs
        Route::get('etfs', [EtfsController::class, 'index'])->name('etfs');
        Route::get('etfs/{ticker}', [EtfsController::class, 'buyEtfs'])->name('etfs.buy');
        Route::post('etfs/{ticker}', [EtfsController::class, 'buyEtfsValidate'])->name('etfs.buy-validate');
        Route::post('etfs/{ticker}/sell', [EtfsController::class, 'sellEtfs'])->name('etfs.sell');
        // Mutual Funds
        Route::get('mutual-funds', [MutualFundsController::class, 'index'])->name('mutual-funds');
        Route::get('mutual-funds/{ticker}', [MutualFundsController::class, 'buyMutualFunds'])->name('mutual-funds.buy');
        Route::post('mutual-funds/{ticker}', [MutualFundsController::class, 'buyMutualFundsValidate'])->name('mutual-funds.buy-validate');
        Route::post('mutual-funds/{ticker}/sell', [MutualFundsController::class, 'sellMutualFunds'])->name('mutual-funds.sell');
        // Treasury Bills
        Route::get('treasury-bills', [TreasuryBillsController::class, 'index'])->name('treasury-bills');
    });

    // Transactions
    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions');

    // referrals
    Route::get('referrals', [ReferralController::class, 'index'])->name('referrals');

    // Withdrawal
    Route::prefix('withdrawals')->name('withdrawals.')->group(function () {
        Route::get('/', [WithdrawalController::class, 'index'])->name('index');
        Route::get('/approved', [WithdrawalController::class, 'byScope'])->name('approved');
        Route::get('/pending', [WithdrawalController::class, 'byScope'])->name('pending');
        Route::get('/failed', [WithdrawalController::class, 'byScope'])->name('failed');
        Route::get('/partial', [WithdrawalController::class, 'byScope'])->name('partial');
        Route::get('/new', [WithdrawalController::class, 'newWithdrawal'])->name('new');
        Route::post('/new', [WithdrawalController::class, 'newWithdrawalValidate'])->name('new-validate');
        Route::get('/view/{transaction_reference}', [WithdrawalController::class, 'viewWithdrawal'])->name('view');
        //nowpayments
        Route::get('nowpayments', [NowpaymentWithdrawalController::class, 'index'])->name('nowpayments');
        Route::post('nowpayments', [NowpaymentWithdrawalController::class, 'withdraw'])->name('nowpayments.withdraw')->middleware('sandbox');
    });

    // Account Settings
    Route::prefix('account')->name('account.')->middleware('sandbox')->group(function () {
        Route::get('/profile', [\App\Http\Controllers\User\AccountController::class, 'profile'])->name('profile');
        Route::post('/profile', [\App\Http\Controllers\User\AccountController::class, 'profileUpdate'])->name('profile.update');
        Route::get('/security', [\App\Http\Controllers\User\AccountController::class, 'security'])->name('security');
        Route::post('/password', [\App\Http\Controllers\User\AccountController::class, 'passwordUpdate'])->name('password.update');
        Route::post('/sessions/logout-other', [\App\Http\Controllers\User\AccountController::class, 'logoutOtherDevices'])->name('sessions.logout-other');
    });
});
