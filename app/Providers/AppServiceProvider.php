<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Only force HTTPS if the APP_URL is set to https (Smart switching for Ngrok vs Localhost)
        if (\Illuminate\Support\Str::startsWith(config('app.url'), 'https://')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $cms = \Cache::remember('cms_contents', 3600, function () {
                return \App\Models\CmsContent::pluck('value', 'key');
            });
            $view->with('cms', $cms);
        });
    }
}
