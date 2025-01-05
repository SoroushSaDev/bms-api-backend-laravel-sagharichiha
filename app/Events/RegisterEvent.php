<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RegisterEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $register;

    /**
     * Create a new event instance.
     */
    public function __construct($register)
    {
        $this->register = $register;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('register.' . $this->register->id),
        ];
    }

    public function broadcastWith(): array
    {
        try {
            $register = $this->register;
            return [
                'id' => $register->id,
                'label' => $register->title,
                'key' => $register->key,
                'type' => $register->type,
            ];
        } catch (\Exception $e) {
            Log::error($e);
        }
    }
}
