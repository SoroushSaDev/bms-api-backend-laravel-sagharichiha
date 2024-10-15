<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MqttMessageReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $topic;
    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct($topic, $message)
    {
        $this->topic = $topic;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn(): Channel
    {
        return new Channel('mqtt-channel');
    }
}
