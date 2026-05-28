<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Enforce OTP verification for authenticated admin routes.
 *
 * When login_otp is enabled, the admin must have completed OTP verification
 * (stored as 'admin_otp_verified' in session) before accessing protected pages.
 */
class AdminOtpVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        // If OTP is not enabled site-wide, skip this check entirely
        if (getSetting('login_otp') !== 'enabled') {
            return $next($request);
        }

        // OTP is enabled — admin must have a valid OTP session
        if (!session()->has('admin_otp_verified')) {

            // Prevent redirect loop if already on the OTP page
            if ($request->routeIs('admin.login.otp')) {
                return $next($request);
            }

            // check if admin_login_otp_code doesn't exist, send them code before redirecting
            if (!session()->has('admin_login_otp_code')) {
                $otp_code = mt_rand(100000, 999999);
                session()->put('admin_login_otp_code', $otp_code);
                session()->put('admin_login_otp_expires_at', now()->addMinutes(15));

                $admin = auth()->guard('admin')->user();
                $message = __('We detected a secure login attempt on your :site account. To continue, please use the verification code below:', ['site' => getSetting('site_name')]);
                $subject = __('Admin Login verification OTP');
                sendOtpVerificationEmail($admin->name, $admin->email, $otp_code, $request->ip(), $request->userAgent(), $message, $subject);
            }

            // we need to get the url being accessed so we can redirect to it after otp verification
            $request->session()->put('admin_redirect_url', $request->fullUrl());
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('OTP verification required.'),
                    'redirect' => route('admin.login.otp'),
                ], 403);
            }

            return redirect()->route('admin.login.otp');
        }

        return $next($request);
    }
}
