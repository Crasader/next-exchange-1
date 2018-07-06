<?php

namespace App\Providers;

use ReCaptcha\ReCaptcha;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Validator::extend('re_captcha', function ($attribute, $value, $parameters, $validator) {
            //$reCaptchaEnabled = Config::get('settings.reCaptchStatus');
            //if (!$reCaptchaEnabled) return true;

            $remoteIp = $_SERVER['REMOTE_ADDR'];
            $secret   = env('RE_CAP_SECRET');

            $response = (new ReCaptcha($secret))
                ->verify($value, $remoteIp);

            return (bool) $response->isSuccess();
        });
    }
}
