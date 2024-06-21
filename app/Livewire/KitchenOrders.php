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
            ->orderBy('created_at', 'desc')
            ->with(['orderItems' => function ($query) {
                $query->whereHas('item', function ($q) {
                    $q->where('type', 'food');
                });
            }, 'orderItems.item', 'orderItems.orderItemOptions.option'])
            ->get();
    }

    public function orderCreated(Order $order)
    {
        $this->orders->prepend($order);
        $this->emitTo('kitchen-orders', 'orderCreated', $order);
    }

    public function updateOrder($orderData)
    {
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

        $order = Order::with(['orderItems.item', 'orderItems.orderItemOptions.option'])->find($orderId);

        if ($order && $order->status == 'pending') {
            $this->orders->prepend($order);
            $this->orders = $this->orders->values();
        }
    }

    public function completeOrder($orderId)
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->status = 'completed';
            $order->save();

            broadcast(new OrderUpdated($order->toArray()));
            $this->dispatch('orderUpdated', $orderId);
            $this->loadOrders();
        }
    }

    public function render()
    {
        return view('livewire.kitchen-orders', ['orders' => $this->orders])->layout('layouts.kitchen');
    }
}
