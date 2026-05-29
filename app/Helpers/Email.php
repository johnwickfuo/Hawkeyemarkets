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

if (!function_exists('mailNotificationEnabled')) {
    /**
     * Safely check whether a specific email notification is enabled in the
     * admin's email_notification setting. Defaults to TRUE (enabled) when the
     * setting is missing or malformed so we don't silently break the mail
     * pipeline just because the JSON blob got cleared in the database.
     */
    function mailNotificationEnabled(string $key): bool
    {
        $raw = getSetting('email_notification', '[]');
        $decoded = is_string($raw) ? json_decode($raw, true) : null;

        if (!is_array($decoded) || !isset($decoded['notifications'][$key]['status'])) {
            return true;
        }

        return $decoded['notifications'][$key]['status'] !== 'disabled';
    }
}

if (!function_exists('logMailFailure')) {
    /**
     * Centralised failure logger so every helper records exception type +
     * message + stack trace under a recognisable prefix.
     */
    function logMailFailure(string $type, \Throwable $e): void
    {
        Log::error('[mail] ' . $type . ' failed: ' . get_class($e) . ': ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
        ]);
    }
}

// send verification email
if (!function_exists('sendVerificationEmail')) {
    function sendVerificationEmail($name, $email, $otp_code)
    {
        if (!mailNotificationEnabled('email_verification')) {
            return;
        }

        try {
            $locale = Session::get('locale') ?? config('app.locale');
            // Signup verification is time-critical and the user is waiting on
            // the next screen — never queue it, even when email_queue is on.
            Mail::to($email)->locale($locale)->send(new EmailVerification($name, $email, $otp_code));
        } catch (\Throwable $e) {
            logMailFailure('verification', $e);
        }
    }
}



// send welcome email
if (!function_exists('sendWelcomeEmail')) {
    function sendWelcomeEmail($user)
    {
        if (!mailNotificationEnabled('welcome')) {
            return;
        }

        try {
            $locale = $user->lang;
            // Welcome mail fires immediately after signup completes; if the
            // queue worker isn't running it would never arrive. Always sync.
            Mail::to($user->email)->locale($locale)->send(new WelcomeEmail($user));
        } catch (\Throwable $e) {
            logMailFailure('welcome', $e);
        }
    }
}


// SEND OTP VERIFICATION EMAIL
if (!function_exists('sendOtpVerificationEmail')) {
    function sendOtpVerificationEmail($name, $email, $otp_code, $ip, $user_agent, $message, $subject)
    {
        if (!mailNotificationEnabled('otp_verification')) {
            return;
        }

        try {
            $locale = Session::get('locale') ?? config('app.locale');
            // otp mails are excluded from queue
            Mail::to($email)->locale($locale)->send(new OtpVerificationEmail($name, $email, $otp_code, $ip, $user_agent, $message, $subject));
        } catch (\Throwable $e) {
            logMailFailure('otp_verification', $e);
        }
    }
}


// send new transaction email
if (!function_exists('sendNewTransactionEmail')) {
    function sendNewTransactionEmail($transaction)
    {
        if (!mailNotificationEnabled('transaction')) {
            return;
        }

        try {
            $locale = $transaction->user->lang;
            if (getSetting('email_queue') == 'enabled') {
                Mail::to($transaction->user->email)->locale($locale)->queue(new TransactionEmail($transaction));
            } else {
                Mail::to($transaction->user->email)->locale($locale)->send(new TransactionEmail($transaction));
            }
        } catch (\Throwable $e) {
            logMailFailure('transaction', $e);
        }
    }
}

// send kyc email
if (!function_exists('sendKycEmail')) {
    function sendKycEmail($subject, $kyc_record)
    {
        if (!mailNotificationEnabled('kyc')) {
            return;
        }

        try {
            $locale = $kyc_record->user->lang;
            // KYC approval/rejection is a one-off admin action and the user
            // is expecting an immediate notification — never queue it.
            Mail::to($kyc_record->user->email)->locale($locale)->send(new KycEmail($subject, $kyc_record));
        } catch (\Throwable $e) {
            logMailFailure('kyc', $e);
        }
    }
}


// send new referral email
if (!function_exists('sendNewReferralEmail')) {
    function sendNewReferralEmail($referral, $referrer)
    {
        if (!mailNotificationEnabled('referral')) {
            return;
        }

        try {
            $locale = $referrer->lang;
            if (getSetting('email_queue') == 'enabled') {
                Mail::to($referrer->email)->locale($locale)->queue(new ReferralEmail($referral, $referrer));
            } else {
                Mail::to($referrer->email)->locale($locale)->send(new ReferralEmail($referral, $referrer));
            }
        } catch (\Throwable $e) {
            logMailFailure('referral', $e);
        }
    }
}


// send deposit email
if (!function_exists('sendDepositEmail')) {
    function sendDepositEmail($custom_subject, $custom_message, $deposit)
    {
        if (!mailNotificationEnabled('deposit')) {
            return;
        }

        try {
            $locale = $deposit->user->lang;
            if (getSetting('email_queue') == 'enabled') {
                Mail::to($deposit->user->email)->locale($locale)->queue(new DepositEmail($custom_subject, $custom_message, $deposit));
            } else {
                Mail::to($deposit->user->email)->locale($locale)->send(new DepositEmail($custom_subject, $custom_message, $deposit));
            }
        } catch (\Throwable $e) {
            logMailFailure('deposit', $e);
        }
    }
}

// send withdrawal email
if (!function_exists('sendWithdrawalEmail')) {
    function sendWithdrawalEmail($custom_subject, $custom_message, $withdrawal)
    {
        if (!mailNotificationEnabled('withdrawal')) {
            return;
        }

        try {
            $locale = $withdrawal->user->lang;
            if (getSetting('email_queue') == 'enabled') {
                Mail::to($withdrawal->user->email)->locale($locale)->queue(new WithdrawalEmail($custom_subject, $custom_message, $withdrawal));
            } else {
                Mail::to($withdrawal->user->email)->locale($locale)->send(new WithdrawalEmail($custom_subject, $custom_message, $withdrawal));
            }
        } catch (\Throwable $e) {
            logMailFailure('withdrawal', $e);
        }
    }
}

// send investment email
if (!function_exists('sendInvestmentEmail')) {
    function sendInvestmentEmail($custom_subject, $custom_message, $investment)
    {
        if (!mailNotificationEnabled('investment')) {
            return;
        }

        try {
            $locale = $investment->user->lang;
            if (getSetting('email_queue') == 'enabled') {
                Mail::to($investment->user->email)->locale($locale)->queue(new InvestmentEmail($custom_subject, $custom_message, $investment));
            } else {
                Mail::to($investment->user->email)->locale($locale)->send(new InvestmentEmail($custom_subject, $custom_message, $investment));
            }
        } catch (\Throwable $e) {
            logMailFailure('investment', $e);
        }
    }
}

// send stock email
if (!function_exists('sendStockEmail')) {
    function sendStockEmail($custom_subject, $custom_message, $holding_history)
    {
        if (!mailNotificationEnabled('stock')) {
            return;
        }

        try {
            $locale = $holding_history->user->lang;
            if (getSetting('email_queue') == 'enabled') {
                Mail::to($holding_history->user->email)->locale($locale)->queue(new StockEmail($holding_history, $custom_subject, $custom_message));
            } else {
                Mail::to($holding_history->user->email)->locale($locale)->send(new StockEmail($holding_history, $custom_subject, $custom_message));
            }
        } catch (\Throwable $e) {
            logMailFailure('stock', $e);
        }
    }
}

// send etf email
if (!function_exists('sendEtfEmail')) {
    function sendEtfEmail($custom_subject, $custom_message, $holding_history)
    {
        if (!mailNotificationEnabled('etf')) {
            return;
        }

        try {
            $locale = $holding_history->user->lang;
            if (getSetting('email_queue') == 'enabled') {
                Mail::to($holding_history->user->email)->locale($locale)->queue(new EtfEmail($holding_history, $custom_subject, $custom_message));
            } else {
                Mail::to($holding_history->user->email)->locale($locale)->send(new EtfEmail($holding_history, $custom_subject, $custom_message));
            }
        } catch (\Throwable $e) {
            logMailFailure('etf', $e);
        }
    }
}


// send rich text email
if (!function_exists('sendRichTextEmail')) {
    function sendRichTextEmail($custom_subject, $custom_message, $user)
    {
        // rich text emails are admin broadcasts — always allowed, no toggle
        try {
            $locale = $user->lang;
            if (getSetting('email_queue') == 'enabled') {
                Mail::to($user->email)->locale($locale)->queue(new RichTextEmail($user, $custom_message, $custom_subject));
            } else {
                Mail::to($user->email)->locale($locale)->send(new RichTextEmail($user, $custom_message, $custom_subject));
            }
        } catch (\Throwable $e) {
            logMailFailure('rich_text', $e);
        }
    }
}

// send account ban email
if (!function_exists('sendAccountBanEmail')) {
    function sendAccountBanEmail($user, $action)
    {
        if (!mailNotificationEnabled('account_ban')) {
            return;
        }

        try {
            $locale = $user->lang;
            if (getSetting('email_queue') == 'enabled') {
                Mail::to($user->email)->locale($locale)->queue(new AccountBan($user, $action));
            } else {
                Mail::to($user->email)->locale($locale)->send(new AccountBan($user, $action));
            }
        } catch (\Throwable $e) {
            logMailFailure('account_ban', $e);
        }
    }
}
