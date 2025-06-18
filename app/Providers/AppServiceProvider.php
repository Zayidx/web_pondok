<?php

namespace App\Providers;

// TAMBAHKAN USE STATEMENT INI
use Illuminate\Support\Facades\URL;
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
        // TAMBAHKAN BLOK IF INI
        if ($this->app->environment('production') || $this->app->environment('development')) {
            URL::forceScheme('https');
        }
    }
}