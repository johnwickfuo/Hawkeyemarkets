<?php

namespace App\Providers;

use App\Models\MenuItem;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Event;
use Illuminate\Mail\Events\MessageSending;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('website_settings', function () {
            return Cache::rememberForever('core_site_settings', function () {
                return (object) Setting::pluck('value', 'key')->toArray();
            });
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        //Support for  older MySQL / MariaDB
        Schema::defaultStringLength(191);

        // Sharing variables only to the user layouts
        View::composer('templates.*.blades.layouts.user', function ($view) {
            // share unread messages to the layout
            $view->with('unread_notification_messages', Auth::check()
                ? Auth::user()->notificationMessages()->where('status', 'unread')->latest()->get()
                : collect());

            // share user menu items to the user layout
            $view->with('user_menu_items', Cache::remember('user_menu_items', 60 * 60, function () {
                return MenuItem::with('children')->where('type', 'user')
                    ->where('is_active', true)
                    ->where('parent_id', null)
                    ->orderBy('sort_order', 'asc')
                    ->get();
            }));
        });

        // Sharing variables only to the admin layout
        View::composer('templates.*.blades.admin.layouts.admin', function ($view) {
            // share admin menu items to the admin layout
            $view->with('admin_menu_items', Cache::remember('admin_menu_items', 60 * 60, function () {
                return MenuItem::with('children')->where('type', 'admin')
                    ->where('is_active', true)
                    ->where('parent_id', null)
                    ->orderBy('sort_order', 'asc')
                    ->get();
            }));
        });


        // Append date to outgoing emails

        // Register missing translation handler
        app('translator')->handleMissingKeysUsing(new \App\Listeners\LogMissingTranslation);

        if (config('site.append_date_to_emails') == 'enabled') {
            Event::listen(MessageSending::class, function (MessageSending $event) {
                $message = $event->message;

                $subject = $message->getSubject();

                if ($subject) {
                    $timestamp = now()->format('Y-m-d H:i:s');
                    $message->subject($subject . ' - ' . $timestamp);
                }
            });
        }

        //inject encrypted services for social login
        $services = ['google', 'github', 'facebook', 'twitter', 'linkedin', 'gitlab', 'bitbucket'];
        foreach ($services as $service) {

            $clientId = config("services.$service.client_id");
            $clientSecret = config("services.$service.client_secret");

            if ($clientId) {
                config()->set("services.$service.client_id", safeDecrypt($clientId));
            }

            if ($clientSecret) {
                config()->set("services.$service.client_secret", safeDecrypt($clientSecret));
            }
        }
    }
}
