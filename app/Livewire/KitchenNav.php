<?php

namespace App\Livewire;

use Livewire\Component;

class KitchenNav extends Component
{
    public $currentTime;

    public function mount()
    {
        $this->currentTime = now()->format('Y-m-d H:i:s');
    }

    public function render()
    {
        return view('livewire.kitchen-nav', [
            'user' => auth()->user(),
            'currentTime' => $this->currentTime,
        ]);
    }
}