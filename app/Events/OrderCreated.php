<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderCreated implements ShouldBroadcastNow

{
    use Dispatchable, InteractsWithSockets, SerializesModels;
  /* public $order;
    public $items; // Sadrži detalje o svakom item-u narudžbine

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->items = $order->items()->with('item')->get(); // Pretpostavlja se da postoji relacija `items` koja povezuje sa `Item` modelom
           // Filter and assign items to property
           $this->items->each(function($orderItem) {
            $orderItem->type = $orderItem->item->type; // Assuming 'type' is a column in 'items' table
        });
    }   

    public function broadcastOn()
    {
        $hasFood = $this->items->contains(fn($item) => $item->type === 'food');
        $hasDrink = $this->items->contains(fn($item) => $item->type === 'drink');

       $channels = [];
        if ($hasFood) {
            $channels[] = new Channel('kitchen');
        }
        if ($hasDrink) {
            $channels[] = new Channel('bar');
        }

        return $channels; // Emituje na jedan ili oba kanala zavisno od sadržaja narudžbine
    }
} */ 
public $order;

public function __construct(Order $order)
{
    $this->order = $order;
    //$this->order = $order->load('orderItems.item')->toArray();
}

public function broadcastOn()
{
    return new Channel('orders');
}

public function broadcastWith()
    {
        return ['order' => $this->order->load('orderItems.item')->toArray()];
    }
}