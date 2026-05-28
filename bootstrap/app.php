<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->name('user.')
                ->group(base_path('routes/user.php'));

            Route::middleware('web')
                ->prefix('utils')
                ->name('utils.')
                ->group(base_path('routes/utils.php'));

            Route::middleware('api')
                ->prefix('api/v1')
                ->name('api.v1.')
                ->group(base_path('routes/apis/v1.php'));

            Route::middleware('web')
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        $middleware->alias([
            'otp.verified' => \App\Http\Middleware\OtpVerified::class,
            'admin.otp.verified' => \App\Http\Middleware\AdminOtpVerified::class,
            'user.status' => \App\Http\Middleware\CheckUserStatus::class,
            'user.kyc' => \App\Http\Middleware\KycMiddleware::class,
            'sandbox' => \App\Http\Middleware\SandBoxModeMiddleware::class,
        ]);

        $middleware->redirectGuestsTo(function ($request) {
            // Admin routes → admin login; everything else → user login
            return $request->is('admin/*') || $request->is('admin')
                ? route('admin.login')
                : route('user.login');
        });

        $middleware->redirectUsersTo(function ($request) {
            $isAdminRoute = $request->is('admin/*') || $request->is('admin');

            // On admin routes, send authenticated admins to the admin dashboard
            if ($isAdminRoute && auth()->guard('admin')->check()) {
                return route('admin.dashboard');
            }

            // On user routes (or any other route), send authenticated users to user dashboard.
            // Admins visiting user routes are NOT redirected here — they aren't authenticated
            // via the web guard and the guest middleware won't be triggered for them.
            return route('user.dashboard');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, $request) {
            if (!$request->expectsJson() || $request->isMethod('get')) {

                if (str_contains($e->getMessage(), 'Call to undefined method') || str_contains($e->getMessage(), 'does not exist')) {
                    // Extract controller and method if possible
                    preg_match('/method\s+([^\s:]+)::([^\s(]+)/', $e->getMessage(), $matches) ||
                        preg_match('/Method\s+\[([^\s\]]+)\]\s+does not exist/', $e->getMessage(), $matches);

                    $controller_method = $matches[1] ?? null;
                    $method = $matches[2] ?? null;

                    if (!$method && $controller_method) {
                        $parts = explode('::', $controller_method);
                        $controller = $parts[0] ?? null;
                        $method = $parts[1] ?? null;
                    } else {
                        $controller = $controller_method;
                    }

                    return response()->view('templates.bento.coming-soon', [
                        'message' => $e->getMessage(),
                        'controller' => $controller,
                        'method' => $method,
                        'is_local' => app()->environment('local', 'staging'),
                    ], 500);
                }
            }
        });
    })
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule) {
        // $schedule->command('lozand:delete-notification-message')->daily()->runInBackground();
        // $schedule->command('lozand:investment-cron-job')->everyMinute()->runInBackground();
        // $schedule->command('queue:work --stop-when-empty')->everyThirtySeconds()->withoutOverlapping()->runInBackground();
        // $schedule->command('lozand:update-expired-deposit-deposit')->everyMinute()->runInBackground();
        // $schedule->command('lozand:update-stock-pnl')->everyMinute()->runInBackground();
    })
    ->create();
