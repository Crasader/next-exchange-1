<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class HandleErrorNotification extends Notification
{
    use Queueable;

    private $_message;
    private $_data;

    /**
     * Create a new notification instance.
     *
     * @param $message
     * @param $data
     */
    public function __construct($message, $data)
    {
        $this->_message = $message;
        $this->_data    = $data;
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
            ->from('debug@next.exchange', 'Manual Debug')
            ->greeting($this->_message)
            ->subject('Error Notification from ' . env('APP_ENV'))
            ->line($this->_data);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
