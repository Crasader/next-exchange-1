<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WithdrawConfirmEmail extends Notification
{
    use Queueable;

    private $_amount;
    private $_address;
    private $_type;
    private $_coin;
    private $_token;

    /**
     * Create a new notification instance.
     *
     * @param $amount
     * @param $rx_id
     * @param $type
     * @param $coin
     */
    public function __construct( $amount, $address, $type, $coin , $link )
    {
        $this->_amount      = $amount;
        $this->_address     = $address;
        $this->_type        = $type;
        $this->_coin        = $coin;
        $this->_token       = $link;
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
            ->subject('Confirmation ' . strtoupper($this->_type) . ' Notification')
            ->greeting('Confirmation ' . strtoupper($this->_coin) . ' ' . $this->_type)
            ->line('Amount: ' . $this->_amount)
            ->line('To address: ' . $this->_address)
            ->action('Confirm', url('wallet', $this->_token));

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
