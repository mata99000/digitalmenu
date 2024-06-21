<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Item;
class CategoryProducts extends Component
{
    public $categories;
    public $selectedCategory = null;
    public $items = [];

    public function mount()
    {
        $this->categories = Category::all();
    }
    public function addItemToOrder($itemId)
{
    // Emituj događaj s ID-om itema koji treba dodati
    $this->dispatch('addItem', $itemId);
}


    public function selectCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->items = Item::where('category_id', $categoryId)->get();
    }
    public function render()
    {
        return view('livewire.category-products');
    }
}
