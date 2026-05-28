<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $helpersPath = app_path('Helpers');

        if (is_dir($helpersPath)) {
            foreach (glob($helpersPath . '/*.php') as $filename) {
                require_once $filename;
            }
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
