<?php

namespace App\Providers;

use App\Services\TwoFactorAuth;
use Illuminate\Support\ServiceProvider;

class TwoFactorAuthServiceProvider extends ServiceProvider
{
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['app.auth.twofactor'];
    }

    /**
     * Registers TwoFactorAuth singleton
     */
    public function register()
    {
        $this->app->singleton('app.auth.twofactor', function($app) {
            return new TwoFactorAuth();
        });
    }
}