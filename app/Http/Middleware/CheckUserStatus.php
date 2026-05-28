<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Enforce user account status on every authenticated request.
 *
 * If the authenticated user's status is anything other than 'active'
 * (i.e. 'banned' or 'suspended'), they are immediately logged out and
 * redirected to the login page with an appropriate error message.
 * AJAX/JSON requests receive a 403 JSON response instead.
 */
class CheckUserStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Only apply to authenticated web users
        if (!$user) {
            return $next($request);
        }

        // if its coming from admin, return
        if (auth()->guard('admin')->check() && session()->has('admin_impersonation')) {
            return $next($request);
        }

        if ($user->status !== 'active') {
            // Build a status-specific message
            $message = match ($user->status) {
                'banned' => __('Your account has been banned. Please contact support.'),
                'suspended' => __('Your account has been suspended. Please contact support.'),
                default => __('Your account is not active. Please contact support.'),
            };

            // Log the user out and clear their session
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // AJAX / JSON requests
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $message,
                    'redirect' => route('user.login'),
                ], 403);
            }

            return redirect()->route('user.login')->with('error', $message);
        }

        return $next($request);
    }
}
