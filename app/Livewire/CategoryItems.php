<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Subcategory;
class CategoryItems extends Component
{
    public $category;
    public $subcategories = [];
    public $items = [];

    public function mount($id)
    {
        $this->category = Category::with('subcategories.items')->find($id);
        if ($this->category) {
            $this->subcategories = $this->category->subcategories;
            // Skupljanje svih itema iz svih subkategorija
            foreach ($this->subcategories as $subcategory) {
                foreach ($subcategory->items as $item) {
                    $this->items[] = $item;
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.category-items')->layout('layouts.main');
    }
}
