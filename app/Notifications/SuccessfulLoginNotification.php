<?php

namespace App\Notifications;

use App\Traits\CaptureIpTrait;
use Carbon\Carbon;
use Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SuccessfulLoginNotification extends Notification implements ShouldQueue
{
    use Queueable, CaptureIpTrait;

    /**
     * The request IP address.
     *
     * @var string
     */
    public $ip;

    /**
     * The request IP address.
     *
     * @var Carbon\Carbon
     */
    public $time;

    /**
     * Create a new notification instance.
     *
     * @param  string  $ip
     * @return void
     */
    public function __construct($ip)
    {
        $this->ip   = $this->getClientIp();
        $this->time = Carbon::now();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the database representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toArray($notifiable)
    {

        return [
            'ip' => $this->ip,
            'time' => $this->time,
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $user = Auth::user();

        return (new MailMessage)
            ->subject('Successful Login Notification')
            ->greeting('Successful Account Login')
            ->line('A successful login was detected for your account.')
            ->line('This request originated from ' . $this->ip . ' (' . @gethostbyaddr($this->ip) . ') at ' . $this->time)
            ->line('Not you? Click below to deactivate your account for temporary basis.')
            ->action('Deactivate', url("/deactivate/{$user->remember_token}"));

    }

}