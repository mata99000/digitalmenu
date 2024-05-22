<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\CategoryList;
use App\Livewire\ItemList;

Route::get('/categories', CategoryList::class)->name('categories');
Route::get('/categories/{categoryId}/items', ItemList::class)->name('category.items');
Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
