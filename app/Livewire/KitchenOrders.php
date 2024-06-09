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

    public function mount()
    {
        $this->loadOrders();
    }
    
    #[On('order-updated')]
    public function removeOrder($orderId)
    {
        $this->orders = $this->orders->filter(function ($order) use ($orderId) {
            return $order->id !== $orderId;
        });
    }
    
    #[On('order-created')]
    public function loadOrders()
    {
        $this->orders = Order::where('status', 'pending')

       
        ->orderBy('created_at', 'desc')  // Sortiranje narudžbina prema datumu kreiranja
        ->get();
        // Učitava narudžbe koje imaju 'pending' ili 'completed_bar' status
        $this->orders = Order::whereIn('status', ['pending', 'completed_bar'])
        ->whereHas('orderItems.item', function($query) {
            $query->where('type', 'food');
        })
        ->with(['orderItems' => function($query) {
            $query->whereHas('item', function($q) {
                $q->where('type', 'food');
            });
        }, 'orderItems.item'])
                             ->orderBy('created_at', 'desc')  // Pretpostavljam da želite sortiranje po datumu kreiranja narudžbine
                             ->get();
    }
    

public function orderCreated(Order $order)
{
    $this->orders->prepend($order);
    $this->emitTo('kitchen-orders', 'orderCreated', $order);
}

public function updateOrder($orderData)
{
    // Filtrirajte narudžbe kako biste uklonili one koje su označene kao dovršene
    $this->orders = $this->orders->filter(function ($order) use ($orderData) {
        return $order['id'] !== $orderData['id'];
    });
}

public function addOrder($data)
{
    $orderId = $data['order']['id'] ?? null;
    if (!$orderId) {
        logger('Order ID not set or invalid:', $data);
        return;
    }

    $order = Order::with('orderItems.item') // Učitajte order sa svim potrebnim relationships
                  ->find($orderId);

    if ($order && $order->status == 'pending') {
        $this->orders->prepend($order);
        $this->orders = $this->orders->values(); // Ažurirajte indekse ako je $this->orders kolekcija
    }
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
