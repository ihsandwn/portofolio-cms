<?php

use Illuminate\Support\Facades\Route;

Route::get('/', App\Livewire\Home::class)->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
Route::middleware(['auth', 'verified', 'role:super-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', App\Livewire\Admin\Dashboard::class)->name('dashboard');
    
    // Portfolio
    Route::get('/portfolios', App\Livewire\Admin\Portfolio\Index::class)->name('portfolios.index');
    Route::get('/portfolios/create', App\Livewire\Admin\Portfolio\Form::class)->name('portfolios.create');
    Route::get('/portfolios/{portfolio}/edit', App\Livewire\Admin\Portfolio\Form::class)->name('portfolios.edit');
});

require __DIR__.'/auth.php';
