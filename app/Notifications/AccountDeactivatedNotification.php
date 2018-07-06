<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Traits\CaptureIpTrait;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AccountDeactivatedNotification extends Notification implements ShouldQueue
{
    use Queueable,
        CaptureIpTrait;

    public $token;

    /**
     * AccountDeactivatedNotification constructor.
     *
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->error()
                    ->subject('Account Deactivated Notification')
                    ->greeting('Too Many Login Attempts')
                    ->line('We detected fail login activity from your account.')
                    ->line('Your account has been deactivated.')
                    ->line('To activate your account please follow link below.')
                    ->action('Activate', route('authenticated.activate-disabled', ['token' => $this->token]));
    }
}
