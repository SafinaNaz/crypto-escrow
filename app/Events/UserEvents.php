<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserEvents
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $guard;
    public $event_type;
    public $message;
    public $meta;
    public $receiver_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($guard, $event_type, $message,$meta,$receiver_id = null)
    {
        $this->guard = $guard;
        $this->event_type = $event_type;
        $this->message = $message;
        $this->meta = $meta;
        $this->receiver_id = $receiver_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
