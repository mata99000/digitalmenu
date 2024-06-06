<?php

use App\Livewire\KitchenOrders;
use Illuminate\Support\Facades\Route;
use App\Livewire\CategoryList;
use App\Livewire\ItemList;
use App\Livewire\CategoryItems;
use App\Livewire\Test;
use App\Livewire\OrderForm;
use App\Livewire\ItemPanel; // Pretpostavljam da je vaš Livewire komponent nazvan ItemPanel

Route::get('/kitchen', KitchenOrders::class)->name('kitchen.panel');

Route::get('/order', OrderForm::class)->name('order.form')->middleware('waiter');
Route::get('/test', Test::class);

Route::get('/', function () {
    return view('categories.index');
});

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
