<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item;
use App\Events\OrderUpdated;
use Livewire\Attributes\On;
class KitchenOrders extends Component
{
    public $orders = [];
    protected $listeners = [
        'echo:orders,OrderCreated' => 'addOrder',
        'echo:orders,OrderUpdated' => 'loadOrders',
        'orderUpdated' => 'removeOrder'
    ];


    #[On('order-updated')]
    public function removeOrder($orderId)
    {
        $this->orders = $this->orders->filter(function ($order) use ($orderId) {
            return $order->id !== $orderId;
        });
    }
    public function mount()
    {
        $this->loadOrders();
    }
    
    public function loadOrders()
{
    // Učitava narudžbe koje imaju 'pending' status i sadrže stavke tipa 'food'
    $this->orders = Order::where('status', 'pending')
    ->whereHas('orderItems.item', function($query) {
        $query->where('type', 'food');
    })
    ->with(['orderItems' => function($query) {
        $query->whereHas('item', function($q) {
            $q->where('type', 'food');
        });
    }, 'orderItems.item'])
    ->get();
}
public function updateOrder($orderData)
{
    // Filtrirajte narudžbe kako biste uklonili one koje su označene kao dovršene
    $this->orders = $this->orders->filter(function ($order) use ($orderData) {
        return $order['id'] !== $orderData['id'];
    });
}
public function addOrder($orderData)
    {
        if (!isset($orderData['order_items']) || !is_array($orderData['order_items'])) {
            return;
        }

        $order = new Order($orderData);
        $order->orderItems = collect($orderData['order_items'])->map(function ($item) {
            $orderItem = new OrderItem($item);
            $orderItem->item = new Item($item['item']);
            return $orderItem;
        });

        $this->orders->push($order);
    }
    public function completeOrder($orderId)
{
    $order = Order::find($orderId);
    if ($order) {
        $order->status = 'completed';
        $order->save();

        // Emitovanje eventa da obavesti ostale korisnike o promeni
        broadcast(new OrderUpdated($order->toArray()));
            $this->dispatch('orderUpdated', $orderId);                        // Ponovno učitavanje narudžbina koje nisu kompletirane
        $this->loadOrders();
    }
}


    
    public function render()
    {
        return view('livewire.kitchen-orders', ['orders' => $this->orders])->layout('layouts.kitchen');
    }
}
