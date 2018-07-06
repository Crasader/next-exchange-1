<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Disable2faAuth extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $resetToken;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $resetToken)
    {
        $this->user = $user;
        $this->resetToken = $resetToken;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.disable2faAuth');
    }
}
