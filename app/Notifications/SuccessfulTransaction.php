<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SuccessfulTransaction extends Notification
{
    use Queueable;

    private $_amount;
    private $_rx_id;
    private $_type;
    private $_coin;

    /**
     * Create a new notification instance.
     *
     * @param $amount
     * @param $rx_id
     * @param $type
     * @param $coin
     */
    public function __construct( $amount, $rx_id, $type, $coin )
    {
        $this->_amount      = $amount;
        $this->_rx_id       = $rx_id;
        $this->_type        = $type;
        $this->_coin        = $coin;
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
            ->subject('Successful ' . strtoupper($this->_type) . ' Notification')
            ->greeting('Successful ' . strtoupper($this->_coin) . ' ' . $this->_type)
            ->line('Amount: ' . $this->_amount)
            ->line('Transaction ID: ' . $this->_rx_id);

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
