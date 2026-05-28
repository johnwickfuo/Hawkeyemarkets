<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Enforce OTP verification for authenticated user routes.
 *
 * When login_otp is enabled, the user must have completed OTP verification
 * (stored as 'user_otp_verified' in session) before accessing protected pages.
 * If not verified, they are redirected to the OTP page.
 */
class OtpVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        // If OTP is not enabled site-wide, skip this check entirely
        if (getSetting('login_otp') !== 'enabled') {
            return $next($request);
        }

        // if its coming from admin, return
        if (auth()->guard('admin')->check() && session()->has('admin_impersonation')) {
            return $next($request);
        }

        // OTP is enabled — user must have a valid OTP session
        if (!session()->has('user_otp_verified')) {
            //check if login_otp_code doesn't exist, send them code before redirecting
            if (!session()->has('login_otp_code')) {
                $otp_code = mt_rand(100000, 999999);
                session()->put('login_otp_code', $otp_code);
                session()->put('login_otp_expires_at', now()->addMinutes(15));

                $message = __('We detected a secure login attempt on your :site account. To continue, please use the verification code below:', ['site' => getSetting('site_name')]);
                $subject = __('Login verification OTP');
                sendOtpVerificationEmail(auth()->user()->first_name, auth()->user()->email, $otp_code, $request->ip(), $request->userAgent(), $message, $subject);
            }

            // we need to get the url being accessed so we can redirect to it after otp verification
            $request->session()->put('user_redirect_url', $request->fullUrl());
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('OTP verification required.'),
                    'redirect' => route('user.login.otp'),
                ], 403);
            }

            return redirect()->route('user.login.otp');
        }

        return $next($request);
    }
}
