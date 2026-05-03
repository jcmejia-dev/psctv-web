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
        // Remove or comment out this Vite customization
        // $this->app->singleton(Vite::class, function ($app) {
        //     return new Vite('build', 'manifest.json');
        // });
    }
}