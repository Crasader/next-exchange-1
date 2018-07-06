<?php

namespace App\Providers;

use URL;
use App\Cache\WalletUpdateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Contracts\Cache\Repository as Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(env('APP_ENV') == 'production') {
            URL::forceScheme('https');
        }

        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(WalletUpdateLimiter::class, function ($app) {
            return new WalletUpdateLimiter($app->make(Cache::class));
        });

    }
}
