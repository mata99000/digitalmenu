<?php

use App\Livewire\BarOrders;
use App\Livewire\KitchenOrders;
use Illuminate\Support\Facades\Route;
use App\Livewire\CategoryList;
use App\Livewire\ItemList;
use App\Livewire\CategoryItems;
use App\Livewire\Test;
use App\Livewire\OrderForm;
use App\Livewire\ItemPanel; // Pretpostavljam da je vaš Livewire komponent nazvan ItemPanel
use App\Livewire\UserOrder;
use App\Livewire\ItemsByCategory;

Route::get('/category/{categoryId}', ItemsByCategory::class);


Route::get('/kitchen', KitchenOrders::class)->name('kitchen.panel')->middleware('cook');
Route::get('/bar', BarOrders::class)->name('bar.panel')->middleware('waiter');

Route::get('/live-order', OrderForm::class)->name('live-order.form')->middleware('waiter');


Route::get('/', UserOrder::class)->name('user-order.form');


Route::get('/categories/{id}', function ($id) {
    return view('categories.show', ['id' => $id]);
});
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
