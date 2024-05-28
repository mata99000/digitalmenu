<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\CategoryList;
use App\Livewire\ItemList;
use App\Livewire\CategoryItems;

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
