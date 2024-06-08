<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item; // Ako postoji model za stavke
use App\Events\OrderCreated;
class OrderForm extends Component
{
    public $items;
    public $selectedItems = [];

    public function mount()
    {
        $this->items = Item::all();
        $this->initSelectedItems();
    }
    public function calculateTotal()
    {
        $total = 0;
        foreach ($this->selectedItems as $item) {
            $total += $item['quantity'] * $item['price'];
        }
        return $total;
    }
    
    private function initSelectedItems()
    {
        foreach ($this->items as $item) {
            $this->selectedItems[$item->id] = [
                'id' => $item->id,
                'name' => $item->name,
                'quantity' => 0,
                'price' => $item->price
            ];
        }
    }

    public function addToOrder($itemId)
    {
        if ($this->selectedItems[$itemId]['quantity'] > 0) {
            $this->selectedItems[$itemId]['quantity']++;
        } else {
            $this->selectedItems[$itemId]['quantity'] = 1;
        }
    }
    public function removeItem($itemId)
    {
        if (isset($this->selectedItems[$itemId])) {
            unset($this->selectedItems[$itemId]);
        }
    }
    
    public function addOrder()
{
    // Proverava da li je order prazan 
    if (array_sum(array_column($this->selectedItems, 'quantity')) === 0) {
        session()->flash('error', 'Narudžbina ne može biti prazna!');
        return;
    }

    $order = new Order();
    $order->waiter_id = auth()->id();
    $order->total = array_sum(array_map(function ($item) {
        return $item['quantity'] * $item['price'];
    }, $this->selectedItems));
    $order->status = 'pending';
    $order->save();

    foreach ($this->selectedItems as $item) {
        if ($item['quantity'] > 0) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->item_id = $item['id'];
            $orderItem->quantity = $item['quantity'];
            $orderItem->price = $item['price'];
            $orderItem->save();
        }
    }

    $order->load('orderItems.item');

    $containsFood = $order->orderItems->contains(function ($orderItem) {
        return $orderItem->item->type == 'food';
    });

    if ($containsFood) {
        // Emitirajte događaj samo ako narudžba sadrži stavke tipa "food"
        event(new OrderCreated($order));
        $this->dispatch('play-sound'); // Emitovanje browser eventa koji će okinuti zvuk

    }

    $this->reset('selectedItems');
    $this->initSelectedItems();
    session()->flash('message', 'Order successfully submitted!');
}
    public function render()
    {
        return view('livewire.order-form')->layout('layouts.order');
    }
}