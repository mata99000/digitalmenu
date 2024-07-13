<?php

namespace App\Livewire;

use Livewire\Component;

class CartComponent extends Component
{
    public $cart = [];
    public $totalPrice = 0;

    #[On('cartUpdated')]
    public function cartUpdated($item)
    {
        $this->cart[] = $item;
        $this->totalPrice += $item['price'];
        $this->dispatch('updateTotalPrice', totalPrice: $this->totalPrice);
    }

    public function render()
    {
        return view('livewire.cart-component', [
            'cart' => $this->cart,
            'totalPrice' => $this->totalPrice,
        ]);
    }
}
