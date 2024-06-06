<?php

namespace App\Livewire;

use Livewire\Component;

class BarOrders extends Component
{
    public $orders = [];

    protected $listeners = ['echo:bar,OrderCreated' => 'addOrder'];

    public function addOrder($order)
    {
        if ($this->isDrink($order)) {
            $this->orders[] = $order;
        }
    }

    private function isDrink($order)
    {
        return collect($order['items'])->contains('type', 'drink');
    }

    public function render()
    {
        return view('livewire.bar-orders');
    }
}