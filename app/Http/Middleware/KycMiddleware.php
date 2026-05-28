<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KycMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // bypass on sandbox mode
        if (config('app.env') == 'sandbox') {
            return $next($request);
        }
        // Bypass if it's admin impersonating a user
        if (auth()->guard('admin')->check() && session()->has('admin_impersonation')) {
            return $next($request);
        }

        // check if kyc module is loaded
        if (!moduleEnabled('kyc_module')) {
            return $next($request);
        }

        $kyc_settings = json_decode(getSetting('kyc'), true) ?? [];

        // Check if any enabled kyc_setting matches the current request
        foreach ($kyc_settings as $setting) {
            if (isset($setting['status']) && $setting['status'] === 'enabled') {
                $wildcard = $setting['route_wildcard'];

                if ($request->routeIs($wildcard)) {
                    // check user status
                    $user = auth()->user();
                    $latestKyc = $user->kyc()->latest()->first();

                    if (!$latestKyc || $latestKyc->status !== 'approved') {
                        if ($request->expectsJson()) {
                            return response()->json([
                                'message' => __('KYC verification is required to access this feature.')
                            ], 403);
                        }

                        return redirect()->route('user.kyc')->with('error', __('Identity verification is required to access this feature. Please complete your KYC.'));
                    }
                }
            }
        }

        return $next($request);
    }
}
