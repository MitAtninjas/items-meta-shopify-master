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

class OrderFulillmentCheck
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The store instance.
     *
     * @var \App\Models\Store
     */
    public $store;

    /**
     * @var String
     */
    public $order_id;

    /**
     * @var String
     */
    public $eventType;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        Store $store,
        String $order_id,
        String $type
    )
    {
        $this->store = $store;
        $this->order_id = $order_id;
        $this->eventType = $type;
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
