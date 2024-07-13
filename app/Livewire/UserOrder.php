<?php

namespace App\Livewire;

use Livewire\Component;

class UserOrder extends Component
{
    public $cartItems = [];

    protected $listeners = ['itemAdded' => 'addToCart'];

    public function addToCart($item)
    {
        $this->cartItems[] = $item;
    }
    public function render()
    {
        return view('livewire.user-order')->layout('layouts.user-order');
    }
}
