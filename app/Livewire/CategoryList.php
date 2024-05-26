<?php

namespace App\Livewire;
use  App\Models\Category;
use Livewire\Component;

class CategoryList extends Component
{
    public $categories;

    public function mount()
    {
        $this->categories = Category::all();
    }
    public function render()
    {
        $categories = Category::all();
        return view('livewire.category-list')->layout('layouts.main');
    }
}
