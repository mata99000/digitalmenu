<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Events\OrderCreated;
use App\Events\OrderUpdated;
use Livewire\Attributes\On;

class BarOrders extends Component
{
    public $orders = [];

    public function mount()
    {
        $this->loadOrders();
    }

   
    #[On('order-created')]
    public function loadOrders()
{
    $this->orders = Order::where('status', 'pending')
    ->whereHas('orderItems.item', function($query) {
        $query->where('type', 'drink');
    })
    ->with(['orderItems' => function($query) {
        $query->whereHas('item', function($q) {
            $q->where('type', 'drink');
        });
    }, 'orderItems.item'])
    ->orderBy('created_at', 'desc')  // Sortiranje narudžbina prema datumu kreiranja
    ->get();
}
public function orderCreated(Order $order)
{
    $this->orders->prepend($order);
    $this->emitTo('bar-orders', 'orderCreated', $order);
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
        $order->status = 'completed_bar';
        $order->save();

        // Emitovanje eventa da obavesti ostale korisnike o promeni
        broadcast(new OrderUpdated($order->toArray()));
            $this->dispatch('orderUpdated', $orderId);                        // Ponovno učitavanje narudžbina koje nisu kompletirane
        $this->loadOrders();
    }
}


    
    public function render()
    {
        return view('livewire.bar-orders', ['orders' => $this->orders])->layout('layouts.bar');
    }
}
