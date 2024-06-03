<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item; // Ako postoji model za stavke

class OrderForm extends Component
{
    public $items;
    public $selectedItems = [];

    public function mount()
    {
        $this->items = Item::all();
        $this->initSelectedItems();
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

    public function addOrder()
    {
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

        $this->reset('selectedItems');
        $this->initSelectedItems();
        session()->flash('message', 'Order successfully submitted!');
    }

    public function render()
    {
        return view('livewire.order-form')->layout('layouts.order');
    }
}