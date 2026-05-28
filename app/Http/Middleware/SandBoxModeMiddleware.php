<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SandBoxModeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // allow only safe methods (GET, HEAD, OPTIONS) in sandbox mode
        if (config('app.env') === 'sandbox' && !$request->isMethod('GET') && !$request->isMethod('HEAD') && !$request->isMethod('OPTIONS')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'status' => 'error',
                    'message' => __('You cannot perform this action in sandbox mode.')
                ], 400);
            }

            return back()->with('error', __('You cannot perform this action in sandbox mode.'));
        }

        return $next($request);
    }
}
