<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Item;
use App\Events\OrderCreated;

class ItemPanel extends Component
{

    public function mount()
    {

    }

    public function render()
    {
        return view('livewire.item-panel')->layout('layouts.kitchen');
    }
}