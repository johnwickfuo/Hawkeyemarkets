<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\CapitalInstruments\BondController;
use App\Http\Controllers\Admin\CapitalInstruments\EtfController;
use App\Http\Controllers\Admin\CapitalInstruments\StockController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepositController;
use App\Http\Controllers\Admin\FileManager\CodeEditorController;
use App\Http\Controllers\Admin\FileManager\FileController;
use App\Http\Controllers\Admin\InvestmentController;
use App\Http\Controllers\Admin\KycController;
use App\Http\Controllers\Admin\Settings\ActivationController;
use App\Http\Controllers\Admin\Settings\BonusSystemController;
use App\Http\Controllers\Admin\Settings\CertificateController;
use App\Http\Controllers\Admin\Settings\CoreController;
use App\Http\Controllers\Admin\Settings\DepositController as DepositSettingController;
use App\Http\Controllers\Admin\Settings\LiveChatController;
use App\Http\Controllers\Admin\Settings\ModuleController;
use App\Http\Controllers\Admin\Settings\TeamController;
use App\Http\Controllers\Admin\Settings\ReviewController;
use App\Http\Controllers\Admin\Settings\WithdrawalController as WithdrawalSettingController;
use App\Http\Controllers\Admin\Settings\EmailController;
use App\Http\Controllers\Admin\Settings\FinancialController;
use App\Http\Controllers\Admin\Settings\MenuController;
use App\Http\Controllers\Admin\Settings\OveriewController;
use App\Http\Controllers\Admin\Settings\SecurityController;
use App\Http\Controllers\Admin\Settings\SeoController;
use App\Http\Controllers\Admin\Settings\UtilityController;
use App\Http\Controllers\Admin\Trading\ForexTradingController;
use App\Http\Controllers\Admin\Trading\FuturesTradingController;
use App\Http\Controllers\Admin\Trading\MarginTradingController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Controllers\Admin\ReferralNetworkController;
use App\Http\Controllers\Admin\Update\PrecheckController;
use App\Http\Controllers\Admin\Update\UpdateController;
use Illuminate\Support\Facades\Route;

// Guests only — logged-in admins cannot access these
Route::middleware(['guest:admin', 'sandbox'])->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'loginValidate'])->name('login.validate')->withoutMiddleware('sandbox');
    Route::get('/login/{provider}', [LoginController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/login/{provider}/callback', [LoginController::class, 'handleGoogleCallback'])->name('login.google.callback');
    Route::get('/forgot-password', [LoginController::class, 'forgotPassword'])->name('forgot-password');
    Route::post('/forgot-password', [LoginController::class, 'sendResetCode'])->name('forgot-password.send');
    Route::get('/forgot-password/otp', [LoginController::class, 'resetOtp'])->name('forgot-password.otp');
    Route::post('/forgot-password/otp', [LoginController::class, 'validateResetOtp'])->name('forgot-password.otp.validate');
    Route::get('/reset-password', [LoginController::class, 'resetPasswordForm'])->name('reset-password');
    Route::post('/reset-password', [LoginController::class, 'updatePassword'])->name('reset-password.update');
});


// authenticated but not otp verified
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth:admin');
Route::middleware(['auth:admin'])->prefix('login')->name('login.')->group(function () {
    Route::get('/verify/otp', [LoginController::class, 'otp'])->name('otp');
    Route::post('/verify/otp', [LoginController::class, 'validateOtp'])->name('otp.validate');
    Route::post('/verify/resend-otp', [LoginController::class, 'resendOtp'])->name('otp.resend');
});

Route::middleware(['auth:admin', 'admin.otp.verified', 'sandbox'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/bulk-action', [UserController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/view/{id}', [UserController::class, 'detail'])->name('detail');
        Route::post('/credit-debit/{id}', [UserController::class, 'creditDebit'])->name('credit-debit')->withoutMiddleware('sandbox');
        Route::post('/login-as/{id}', [UserController::class, 'loginAs'])->name('login-as')->withoutMiddleware('sandbox');
        Route::post('/send-email/{id}', [UserController::class, 'sendEmail'])->name('send-email');
        Route::get('/bulk-email', [UserController::class, 'bulkEmail'])->name('bulk-email');
        Route::post('/send-bulk-email', [UserController::class, 'sendBulkEmail'])->name('send-bulk-email');
    });


    // Investments
    Route::prefix('investments')->name('investments.')->group(function () {
        Route::get('/', [InvestmentController::class, 'index'])->name('index');
        Route::get('edit/{id}', [InvestmentController::class, 'edit'])->name('edit');
        Route::post('edit/{id}', [InvestmentController::class, 'update'])->name('update');
        Route::post('delete/{id}', [InvestmentController::class, 'delete'])->name('delete');
        // Plans
        Route::get('plans', [InvestmentController::class, 'plans'])->name('plans.index');
        Route::get('plans/create', [InvestmentController::class, 'createPlan'])->name('plans.create');
        Route::post('plans/create', [InvestmentController::class, 'storePlan'])->name('plans.store');
        Route::get('plans/edit/{id}', [InvestmentController::class, 'editPlan'])->name('plans.edit');
        Route::post('plans/edit/{id}', [InvestmentController::class, 'updatePlan'])->name('plans.update');
        Route::post('plans/delete/{id}', [InvestmentController::class, 'deletePlan'])->name('plans.delete');

        Route::get('earnings', [InvestmentController::class, 'earnings'])->name('earnings');
        Route::post('earnings/delete/{id}', [InvestmentController::class, 'deleteEarnings'])->name('earnings.delete');
    });

    // Deposits
    Route::prefix('deposits')->name('deposits.')->group(function () {
        Route::get('/', [DepositController::class, 'index'])->name('index');
        Route::get('view/{id}', [DepositController::class, 'viewDeposit'])->name('view');
        Route::post('edit/{id}', [DepositController::class, 'update'])->name('update')->withoutMiddleware('sandbox');
        Route::post('delete/{id}', [DepositController::class, 'delete'])->name('delete');
    });

    // Withdrawals
    Route::prefix('withdrawals')->name('withdrawals.')->group(function () {
        Route::get('/', [WithdrawalController::class, 'index'])->name('index');
        Route::get('view/{id}', [WithdrawalController::class, 'viewWithdrawal'])->name('view');
        Route::post('edit/{id}', [WithdrawalController::class, 'update'])->name('update')->withoutMiddleware('sandbox');
        Route::post('delete/{id}', [WithdrawalController::class, 'delete'])->name('delete');
    });

    // Kyc
    Route::prefix('kyc')->name('kyc.')->group(function () {
        Route::get('/', [KycController::class, 'index'])->name('index');
        Route::get('view/{id}', [KycController::class, 'viewKyc'])->name('view');
        Route::post('edit/{id}', [KycController::class, 'update'])->name('update');
        Route::post('delete/{id}', [KycController::class, 'delete'])->name('delete');
    });

    // Transactions
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::post('/delete/{id}', [TransactionController::class, 'delete'])->name('delete');
        Route::post('/bulk-delete', [TransactionController::class, 'bulkDelete'])->name('bulk-delete');
    });

    // Referral Network
    Route::prefix('referrals')->name('referrals.')->group(function () {
        Route::get('/', [ReferralNetworkController::class, 'index'])->name('index');
    });

    // Stocks
    Route::prefix('stocks')->name('stocks.')->group(function () {
        Route::get('/', [StockController::class, 'index'])->name('index');
        Route::post('delete', [StockController::class, 'delete'])->name('delete');
        Route::get('history', [StockController::class, 'history'])->name('history');
        Route::post('history/delete', [StockController::class, 'deleteHistory'])->name('history.delete');
    });

    // ETFs
    Route::prefix('etfs')->name('etfs.')->group(function () {
        Route::get('/', [EtfController::class, 'index'])->name('index');
        Route::post('delete', [EtfController::class, 'delete'])->name('delete');
        Route::get('history', [EtfController::class, 'history'])->name('history');
        Route::post('history/delete', [EtfController::class, 'deleteHistory'])->name('history.delete');
    });

    //bonds
    Route::prefix('bonds')->name('bonds.')->group(function () {
        Route::get('/', [BondController::class, 'index'])->name('index');
        Route::post('delete', [BondController::class, 'delete'])->name('delete');
        Route::get('history', [BondController::class, 'history'])->name('history');
        Route::post('history/delete', [BondController::class, 'deleteHistory'])->name('history.delete');
    });

    //Futures Trading
    Route::prefix('futures-trading')->name('futures-trading.')->group(function () {
        // trading accounts
        Route::prefix('accounts')->name('accounts.')->group(function () {
            Route::get('/', [FuturesTradingController::class, 'tradingAccounts'])->name('index');
            Route::post('credit-debit', [FuturesTradingController::class, 'creditDebit'])->name('credit-debit')->withoutMiddleware('sandbox');
            Route::post('update-status', [FuturesTradingController::class, 'updateStatus'])->name('update-status');
            Route::post('delete', [FuturesTradingController::class, 'deleteTradingAccount'])->name('delete');
        });

        //Positions
        Route::prefix('positions')->name('positions.')->group(function () {
            Route::get('/', [FuturesTradingController::class, 'positions'])->name('index');
            Route::post('close', [FuturesTradingController::class, 'closePosition'])->name('close');
            Route::post('delete', [FuturesTradingController::class, 'deletePosition'])->name('delete');
        });

        // Orders
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [FuturesTradingController::class, 'orders'])->name('index');
            Route::post('cancel', [FuturesTradingController::class, 'cancelOrder'])->name('cancel');
            Route::post('delete', [FuturesTradingController::class, 'deleteOrder'])->name('delete');
        });
    });


    // Margin Trading
    Route::prefix('margin-trading')->name('margin-trading.')->group(function () {
        // trading accounts
        Route::prefix('accounts')->name('accounts.')->group(function () {
            Route::get('/', [MarginTradingController::class, 'tradingAccounts'])->name('index');
            Route::post('credit-debit', [MarginTradingController::class, 'creditDebit'])->name('credit-debit')->withoutMiddleware('sandbox');
            Route::post('update-status', [MarginTradingController::class, 'updateStatus'])->name('update-status');
            Route::post('delete', [MarginTradingController::class, 'deleteTradingAccount'])->name('delete');
        });

        //Positions
        Route::prefix('positions')->name('positions.')->group(function () {
            Route::get('/', [MarginTradingController::class, 'positions'])->name('index');
            Route::post('close', [MarginTradingController::class, 'closePosition'])->name('close');
            Route::post('delete', [MarginTradingController::class, 'deletePosition'])->name('delete');
        });

        // Orders
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [MarginTradingController::class, 'orders'])->name('index');
            Route::post('cancel', [MarginTradingController::class, 'cancelOrder'])->name('cancel');
            Route::post('delete', [MarginTradingController::class, 'deleteOrder'])->name('delete');
        });
    });

    //Forex Trading
    Route::prefix('forex-trading')->name('forex-trading.')->group(function () {
        // trading accounts
        Route::prefix('accounts')->name('accounts.')->group(function () {
            Route::get('/', [ForexTradingController::class, 'tradingAccounts'])->name('index');
            Route::post('credit-debit', [ForexTradingController::class, 'creditDebit'])->name('credit-debit')->withoutMiddleware('sandbox');
            Route::post('update-status', [ForexTradingController::class, 'updateStatus'])->name('update-status');
            Route::post('delete', [ForexTradingController::class, 'deleteTradingAccount'])->name('delete');
        });

        //Positions
        Route::prefix('positions')->name('positions.')->group(function () {
            Route::get('/', [ForexTradingController::class, 'positions'])->name('index');
            Route::post('close', [ForexTradingController::class, 'closePosition'])->name('close');
            Route::post('delete', [ForexTradingController::class, 'deletePosition'])->name('delete');
        });

        // Orders
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [ForexTradingController::class, 'orders'])->name('index');
            Route::post('cancel', [ForexTradingController::class, 'cancelOrder'])->name('cancel');
            Route::post('delete', [ForexTradingController::class, 'deleteOrder'])->name('delete');
        });
    });






    // settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [OveriewController::class, 'index'])->name('index');
        Route::get('legal', [OveriewController::class, 'legal'])->name('legal');
        // activation
        Route::get('activation', [ActivationController::class, 'index'])->name('activation');
        Route::post('activation', [ActivationController::class, 'update'])->name('activation.update');
        // system
        Route::get('system', [OveriewController::class, 'system'])->name('system');
        Route::post('system/clear-cache', [OveriewController::class, 'clearCache'])->name('system.clear-cache');
        Route::post('system/update-env', [OveriewController::class, 'updateEnvSetting'])->name('system.update-env');
        //Audit
        Route::get('audit', [OveriewController::class, 'audit'])->name('audit');
        Route::get('audit/pdf', [OveriewController::class, 'auditPdf'])->name('audit.pdf');
        //core
        Route::get('core', [CoreController::class, 'index'])->name('core');
        Route::post('core', [CoreController::class, 'update'])->name('core.update');
        //email
        Route::get('email', [EmailController::class, 'index'])->name('email');
        Route::post('email', [EmailController::class, 'update'])->name('email.update');
        Route::post('email/test', [EmailController::class, 'test'])->name('email.test');
        // cronjob
        Route::get('cronjob', [OveriewController::class, 'cronJob'])->name('cronjob');

        // financial
        Route::get('financial', [FinancialController::class, 'index'])->name('financial');
        Route::post('financial', [FinancialController::class, 'update'])->name('financial.update');

        // security
        Route::get('security', [SecurityController::class, 'index'])->name('security');
        Route::post('security', [SecurityController::class, 'update'])->name('security.update');

        // bonus system
        Route::get('bonus-system', [BonusSystemController::class, 'index'])->name('bonus-system');
        Route::post('bonus-system', [BonusSystemController::class, 'update'])->name('bonus-system.update');

        // certificate
        Route::get('certificate', [CertificateController::class, 'index'])->name('certificate');
        Route::post('certificate', [CertificateController::class, 'update'])->name('certificate.update');

        // seo
        Route::get('seo', [SeoController::class, 'index'])->name('seo');
        Route::post('seo', [SeoController::class, 'update'])->name('seo.update');

        // utility
        Route::get('utility', [UtilityController::class, 'index'])->name('utility');
        Route::post('utility', [UtilityController::class, 'update'])->name('utility.update');

        // livechat
        Route::get('livechat', [LiveChatController::class, 'index'])->name('livechat');
        Route::post('livechat', [LiveChatController::class, 'update'])->name('livechat.update');

        // login methods
        Route::get('login-method', [\App\Http\Controllers\Admin\Settings\LoginMethodController::class, 'index'])->name('login-method');
        Route::post('login-method', [\App\Http\Controllers\Admin\Settings\LoginMethodController::class, 'update'])->name('login-method.update');

        // menu
        Route::get('menu', [MenuController::class, 'index'])->name('menu');
        Route::post('menu', [MenuController::class, 'update'])->name('menu.update');
        Route::post('menu/create', [MenuController::class, 'create'])->name('menu.create');
        Route::post('menu/reorder', [MenuController::class, 'reorder'])->name('menu.reorder');

        // deposit
        Route::prefix('deposit')->name('deposit.')->group(function () {
            Route::get('/', [DepositSettingController::class, 'index'])->name('index');
            Route::post('settings', [DepositSettingController::class, 'updateSettings'])->name('settings.update');
            Route::post('nowpayment', [DepositSettingController::class, 'updateNowpayment'])->name('nowpayment.update');
            Route::post('toggle-status', [DepositSettingController::class, 'toggleStatus'])->name('toggle-status');
            Route::get('create', [DepositSettingController::class, 'create'])->name('create');
            Route::post('store', [DepositSettingController::class, 'store'])->name('store');
            Route::get('edit/{id}', [DepositSettingController::class, 'edit'])->name('edit');
            Route::post('update/{id}', [DepositSettingController::class, 'update'])->name('update');
            Route::post('delete/{id}', [DepositSettingController::class, 'delete'])->name('delete');
        });

        // withdrawal
        Route::prefix('withdrawal')->name('withdrawal.')->group(function () {
            Route::get('/', [WithdrawalSettingController::class, 'index'])->name('index');
            Route::post('settings', [WithdrawalSettingController::class, 'updateSettings'])->name('settings.update');
            Route::post('nowpayment', [WithdrawalSettingController::class, 'updateNowpayment'])->name('nowpayment.update');
            Route::post('toggle-status', [WithdrawalSettingController::class, 'toggleStatus'])->name('toggle-status');
            Route::get('create', [WithdrawalSettingController::class, 'create'])->name('create');
            Route::post('store', [WithdrawalSettingController::class, 'store'])->name('store');
            Route::get('edit/{id}', [WithdrawalSettingController::class, 'edit'])->name('edit');
            Route::post('update/{id}', [WithdrawalSettingController::class, 'update'])->name('update');
            Route::post('delete/{id}', [WithdrawalSettingController::class, 'delete'])->name('delete');
        });


        // Modules
        Route::prefix('modules')->name('modules.')->group(function () {
            Route::get('/', [ModuleController::class, 'index'])->name('index');
            Route::post('update', [ModuleController::class, 'update'])->name('update');
        });

        //management team
        Route::prefix('management-team')->name('management-team.')->group(function () {
            Route::get('/', [TeamController::class, 'index'])->name('index');
            Route::post('update/{id}', [TeamController::class, 'update'])->name('update');
            Route::post('delete/{id}', [TeamController::class, 'delete'])->name('delete');
            Route::post('create', [TeamController::class, 'create'])->name('create');
        });

        //client reviews
        Route::prefix('reviews')->name('reviews.')->group(function () {
            Route::get('/', [ReviewController::class, 'index'])->name('index');
            Route::post('update/{id}', [ReviewController::class, 'update'])->name('update');
            Route::post('delete/{id}', [ReviewController::class, 'delete'])->name('delete');
            Route::post('create', [ReviewController::class, 'create'])->name('create');
        });


    });

    // Account Settings
    Route::prefix('account')->name('account.')->middleware('sandbox')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('index');
        Route::get('profile', [AccountController::class, 'profile'])->name('profile');
        Route::post('profile', [AccountController::class, 'profileUpdate'])->name('profile.update');
        Route::get('security', [AccountController::class, 'security'])->name('security');
        Route::post('password', [AccountController::class, 'passwordUpdate'])->name('password.update');
        Route::post('sessions/logout-other', [AccountController::class, 'logoutOtherDevices'])->name('sessions.logout-other');
    });

    // code editor
    Route::prefix('file-manager')->name('file-manager.')->middleware('sandbox')->group(function () {
        Route::get('/', [FileController::class, 'index'])->name('index');
        Route::get('download', [FileController::class, 'download'])->name('download');
        Route::get('view', [FileController::class, 'view'])->name('view');
        Route::post('delete', [FileController::class, 'delete'])->name('delete');
        Route::post('upload', [FileController::class, 'upload'])->name('upload');
        Route::post('create', [FileController::class, 'create'])->name('create');
        Route::post('rename', [FileController::class, 'rename'])->name('rename');
        Route::post('move', [FileController::class, 'move'])->name('move');
        Route::post('copy', [FileController::class, 'copy'])->name('copy');
        Route::post('permission', [FileController::class, 'permission'])->name('permission');
        Route::post('bulk-delete', [FileController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('bulk-move', [FileController::class, 'bulkMove'])->name('bulk-move');
        Route::post('bulk-copy', [FileController::class, 'bulkCopy'])->name('bulk-copy');
        Route::post('bulk-zip', [FileController::class, 'bulkZip'])->name('bulk-zip');
        Route::get('/code-editor', [CodeEditorController::class, 'index'])->name('code-editor');
        Route::post('/code-editor', [CodeEditorController::class, 'update'])->name('code-editor.update');
    });


    // update
    Route::prefix('update')->name('update.')->middleware('sandbox')->group(function () {
        Route::get('/', [PrecheckController::class, 'index'])->name('index');
        Route::post('/verify-requirements', [PrecheckController::class, 'verifyRequirements'])->name('verify-requirements');
        Route::post('/download-updater', [PrecheckController::class, 'updateUpdater'])->name('download-updater');

        Route::prefix('process')->name('process.')->group(function () {
            Route::get('/', [UpdateController::class, 'index'])->name('index');
            Route::post('/init-cleanup', [UpdateController::class, 'initCleanup'])->name('init-cleanup');
            Route::post('/download', [UpdateController::class, 'download'])->name('download');
            Route::post('/extract', [UpdateController::class, 'extract'])->name('extract');
            Route::post('/sanitize', [UpdateController::class, 'sanitize'])->name('sanitize');
            Route::post('/replace', [UpdateController::class, 'replace'])->name('replace');
            Route::post('/cleanup', [UpdateController::class, 'cleanup'])->name('cleanup');
        });
    });

});
