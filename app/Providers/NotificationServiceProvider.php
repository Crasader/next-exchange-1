<?php

namespace App\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\EloquentUserProvider;
use App\Notifications\FailedLoginNotification;
use App\Notifications\SuccessfulLoginNotification;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * The FQCN of the failed login event.
     *
     * @var string
     */
    protected $failedEvent = 'Illuminate\Auth\Events\Failed';

    /**
     * The FQCN of the successful login event.
     *
     * @var string
     */
    protected $successEvent = 'Illuminate\Auth\Events\Login';

    /**
     * Boot the notification provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['events']->listen($this->failedEvent, function ($event) {
            if (isset($event->user) && is_a($event->user, 'Illuminate\Database\Eloquent\Model')) {
                $event->user->notify(new FailedLoginNotification(
                    $this->app['request']->ip()
                ));
            }
        });

        $this->app['events']->listen($this->successEvent, function ($event) {
            if (isset($event->user) && is_a($event->user, 'Illuminate\Database\Eloquent\Model')) {
                $event->user->notify(new SuccessfulLoginNotification(
                    $this->app['request']->ip()
                ));
            }
        });
    }

    /**
     * Register for the notification provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}