<?php

use App\Mail\AccountBan;
use App\Mail\DepositEmail;
use App\Mail\EmailVerification;
use App\Mail\EtfEmail;
use App\Mail\InvestmentEmail;
use App\Mail\KycEmail;
use App\Mail\OtpVerificationEmail;
use App\Mail\ReferralEmail;
use App\Mail\RichTextEmail;
use App\Mail\StockEmail;
use App\Mail\TransactionEmail;
use App\Mail\WelcomeEmail;
use App\Mail\WithdrawalEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

// send verification email
if (!function_exists('sendVerificationEmail')) {
    function sendVerificationEmail($name, $email, $otp_code)
    {
        $email_notification = json_decode(getSetting('email_notification'), true);
        if ($email_notification['notifications']['email_verification']['status'] == 'disabled') {
            return;
        }

        if (config('app.env') === 'sandbox') {
            return;
        }
        try {

            $locale = Session::get('locale') ?? config('app.locale');

            if (getSetting('email_queue') == 'enabled') {
                Mail::to($email)->locale($locale)->queue(new EmailVerification($name, $email, $otp_code));
            } else {
                Mail::to($email)->locale($locale)->send(new EmailVerification($name, $email, $otp_code));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send verification email: ' . $e->getMessage());
        }
    }
}



// send welcome email
if (!function_exists('sendWelcomeEmail')) {
    function sendWelcomeEmail($user)
    {
        $email_notification = json_decode(getSetting('email_notification'), true);
        if ($email_notification['notifications']['welcome']['status'] == 'disabled') {
            return;
        }

        if (config('app.env') === 'sandbox') {
            return;
        }
        try {
            $locale = $user->lang;
            if (getSetting('email_queue') == 'enabled') {
                Mail::to($user->email)->locale($locale)->queue(new WelcomeEmail($user));
            } else {
                Mail::to($user->email)->locale($locale)->send(new WelcomeEmail($user));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send welcome email: ' . $e->getMessage());
        }
    }
}


// SEND OTP VERIFICATION EMAIL
if (!function_exists('sendOtpVerificationEmail')) {
    function sendOtpVerificationEmail($name, $email, $otp_code, $ip, $user_agent, $message, $subject)
    {
        $email_notification = json_decode(getSetting('email_notification'), true);
        if ($email_notification['notifications']['otp_verification']['status'] == 'disabled') {
            return;
        }

        if (config('app.env') === 'sandbox') {
            return;
        }
        try {
            $locale = Session::get('locale') ?? config('app.locale');
            // otp mails are excluded from queue
            Mail::to($email)->locale($locale)->send(new OtpVerificationEmail($name, $email, $otp_code, $ip, $user_agent, $message, $subject));
        } catch (\Exception $e) {
            Log::error('Failed to send otp verification email: ' . $e->getMessage());
        }
    }
}


// send new transaction email
if (!function_exists('sendNewTransactionEmail')) {
    function sendNewTransactionEmail($transaction)
    {
        $email_notification = json_decode(getSetting('email_notification'), true);
        if ($email_notification['notifications']['transaction']['status'] == 'disabled') {
            return;
        }

        if (config('app.env') === 'sandbox') {
            return;
        }
        try {
            $locale = $transaction->user->lang;
            if (getSetting('email_queue') == 'enabled') {
                Mail::to($transaction->user->email)->locale($locale)->queue(new TransactionEmail($transaction));
            } else {
                Mail::to($transaction->user->email)->locale($locale)->send(new TransactionEmail($transaction));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send new transaction email: ' . $e->getMessage());
        }
    }
}

// send kyc email
if (!function_exists('sendKycEmail')) {
    function sendKycEmail($subject, $kyc_record)
    {
        $email_notification = json_decode(getSetting('email_notification'), true);
        if ($email_notification['notifications']['kyc']['status'] == 'disabled') {
            return;
        }

        if (config('app.env') === 'sandbox') {
            return;
        }
        try {
            $locale = $kyc_record->user->lang;
            if (getSetting('email_queue') == 'enabled') {
                Mail::to($kyc_record->user->email)->locale($locale)->queue(new KycEmail($subject, $kyc_record));
            } else {
                Mail::to($kyc_record->user->email)->locale($locale)->send(new KycEmail($subject, $kyc_record));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send kyc email: ' . $e->getMessage());
        }
    }
}


// send new referral email
if (!function_exists('sendNewReferralEmail')) {
    function sendNewReferralEmail($referral, $referrer)
    {
        $email_notification = json_decode(getSetting('email_notification'), true);
        if ($email_notification['notifications']['referral']['status'] == 'disabled') {
            return;
        }
        if (config('app.env') === 'sandbox') {
            return;
        }
        try {
            $locale = $referrer->lang;
            if (getSetting('email_queue') == 'enabled') {
                Mail::to($referrer->email)->locale($locale)->queue(new ReferralEmail($referral, $referrer));
            } else {
                Mail::to($referrer->email)->locale($locale)->send(new ReferralEmail($referral, $referrer));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send new referral email: ' . $e->getMessage());
        }
    }
}


// send deposit email
if (!function_exists('sendDepositEmail')) {
    function sendDepositEmail($custom_subject, $custom_message, $deposit)
    {
        $email_notification = json_decode(getSetting('email_notification'), true);
        if ($email_notification['notifications']['deposit']['status'] == 'disabled') {
            return;
        }

        if (config('app.env') === 'sandbox') {
            return;
        }
        try {
            $locale = $deposit->user->lang;
            if (getSetting('email_queue') == 'enabled') {
                Mail::to($deposit->user->email)->locale($locale)->queue(new DepositEmail($custom_subject, $custom_message, $deposit));
            } else {
                Mail::to($deposit->user->email)->locale($locale)->send(new DepositEmail($custom_subject, $custom_message, $deposit));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send deposit email: ' . $e->getMessage());
        }
    }
}

// send withdrawal email
if (!function_exists('sendWithdrawalEmail')) {
    function sendWithdrawalEmail($custom_subject, $custom_message, $withdrawal)
    {
        $email_notification = json_decode(getSetting('email_notification'), true);
        if ($email_notification['notifications']['withdrawal']['status'] == 'disabled') {
            return;
        }

        if (config('app.env') === 'sandbox') {
            return;
        }
        try {
            $locale = $withdrawal->user->lang;
            if (getSetting('email_queue') == 'enabled') {
                Mail::to($withdrawal->user->email)->locale($locale)->queue(new WithdrawalEmail($custom_subject, $custom_message, $withdrawal));
            } else {
                Mail::to($withdrawal->user->email)->locale($locale)->send(new WithdrawalEmail($custom_subject, $custom_message, $withdrawal));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send withdrawal email: ' . $e->getMessage());
        }
    }
}

// send investment email
if (!function_exists('sendInvestmentEmail')) {
    function sendInvestmentEmail($custom_subject, $custom_message, $investment)
    {
        $email_notification = json_decode(getSetting('email_notification'), true);
        if ($email_notification['notifications']['investment']['status'] == 'disabled') {
            return;
        }

        if (config('app.env') === 'sandbox') {
            return;
        }
        try {
            $locale = $investment->user->lang;
            if (getSetting('email_queue') == 'enabled') {
                Mail::to($investment->user->email)->locale($locale)->queue(new InvestmentEmail($custom_subject, $custom_message, $investment));
            } else {
                Mail::to($investment->user->email)->locale($locale)->send(new InvestmentEmail($custom_subject, $custom_message, $investment));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send investment email: ' . $e->getMessage());
        }
    }
}

// send stock email
if (!function_exists('sendStockEmail')) {
    function sendStockEmail($custom_subject, $custom_message, $holding_history)
    {
        $email_notification = json_decode(getSetting('email_notification'), true);
        if ($email_notification['notifications']['stock']['status'] == 'disabled') {
            return;
        }

        if (config('app.env') === 'sandbox') {
            return;
        }
        try {
            $locale = $holding_history->user->lang;
            if (getSetting('email_queue') == 'enabled') {
                Mail::to($holding_history->user->email)->locale($locale)->queue(new StockEmail($holding_history, $custom_subject, $custom_message));
            } else {
                Mail::to($holding_history->user->email)->locale($locale)->send(new StockEmail($holding_history, $custom_subject, $custom_message));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send stock email: ' . $e->getMessage());
        }
    }
}

// send etf email
if (!function_exists('sendEtfEmail')) {
    function sendEtfEmail($custom_subject, $custom_message, $holding_history)
    {
        $email_notification = json_decode(getSetting('email_notification'), true);
        if ($email_notification['notifications']['etf']['status'] == 'disabled') {
            return;
        }

        if (config('app.env') === 'sandbox') {
            return;
        }
        try {
            $locale = $holding_history->user->lang;
            if (getSetting('email_queue') == 'enabled') {
                Mail::to($holding_history->user->email)->locale($locale)->queue(new EtfEmail($holding_history, $custom_subject, $custom_message));
            } else {
                Mail::to($holding_history->user->email)->locale($locale)->send(new EtfEmail($holding_history, $custom_subject, $custom_message));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send etf email: ' . $e->getMessage());
        }
    }
}


// send rich text email
if (!function_exists('sendRichTextEmail')) {
    function sendRichTextEmail($custom_subject, $custom_message, $user)
    {
        // $email_notification = json_decode(getSetting('email_notification'), true);
        // if ($email_notification['notifications']['rich_text']['status'] == 'disabled') {
        //     return;
        // }
        if (config('app.env') === 'sandbox') {
            return;
        }
        try {
            $locale = $user->lang;
            if (getSetting('email_queue') == 'enabled') {
                Mail::to($user->email)->locale($locale)->queue(new RichTextEmail($user, $custom_message, $custom_subject));
            } else {
                Mail::to($user->email)->locale($locale)->send(new RichTextEmail($user, $custom_message, $custom_subject));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send rich text email: ' . $e->getMessage());
        }
    }
}

// send account ban email
if (!function_exists('sendAccountBanEmail')) {
    function sendAccountBanEmail($user, $action)
    {
        if (config('app.env') === 'sandbox') {
            return;
        }
        $email_notification = json_decode(getSetting('email_notification'), true);
        if ($email_notification['notifications']['account_ban']['status'] == 'disabled') {
            return;
        }
        try {
            $locale = $user->lang;
            if (getSetting('email_queue') == 'enabled') {
                Mail::to($user->email)->locale($locale)->queue(new AccountBan($user, $action));
            } else {
                Mail::to($user->email)->locale($locale)->send(new AccountBan($user, $action));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send account ban email: ' . $e->getMessage());
        }
    }
}
