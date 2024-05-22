<?php

namespace App\Livewire;

use Livewire\Component;

class ItemList extends Component
{
    public $categoryId;

    public function mount($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function render()
    {
        return view('livewire.item-list', [
            'items' => Item::where('category_id', $this->categoryId)->get(),
        ]);
    }
}
