<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Store;

class StoreUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The store instance.
     *
     * @var \App\Models\Store
     */
    public $store;

    public $originalStore;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Store $store, $originalStore)
    {
        $this->store = $store;
        $this->originalStore = $originalStore;
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
