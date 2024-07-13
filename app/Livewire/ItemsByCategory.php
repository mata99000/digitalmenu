<?php

namespace App\Livewire;
use Livewire\Component;
use App\Models\Category;

class ItemsByCategory extends Component
{
    public $category;
    public $subcategories;

    protected $listeners = ['categorySelected' => 'loadItems', 'addItemToCart'];

    public function loadItems($categoryId)
    {
        $this->dispatch('loadingItems'); // Emituj događaj za prikazivanje preloadera

        $this->category = Category::with('subcategories.items')->find($categoryId);

        if ($this->category) {
            $this->subcategories = $this->category->subcategories;
            $this->dispatch('itemsLoaded'); // Emituj događaj za sakrivanje preloadera i inicijalizaciju dugmadi
        } else {
            $this->subcategories = collect();
            $this->dispatch('itemsLoaded'); // Emituj događaj za sakrivanje preloadera i inicijalizaciju dugmadi
        }
    }

    public function addItemToCart($item)
    {
        $this->dispatch('cartUpdated', item: $item);
    }

    public function render()
    {
        return view('livewire.items-by-category', [
            'subcategories' => $this->subcategories,
        ]);
    }
}
