<?php namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class Coinupdate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    public $message;

    public function __construct($message)
    {
        $this->data = [
            'update' => '1',
        ];
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('test-channel');
    }
}
