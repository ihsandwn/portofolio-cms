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

    // Access Control
    Route::get('/users', App\Livewire\Admin\User\Index::class)->name('users.index');
    Route::get('/users/create', App\Livewire\Admin\User\Form::class)->name('users.create');
    Route::get('/users/{user}/edit', App\Livewire\Admin\User\Form::class)->name('users.edit');

    Route::get('/roles', App\Livewire\Admin\Role\Index::class)->name('roles.index');
    Route::get('/roles/create', App\Livewire\Admin\Role\Form::class)->name('roles.create');
    Route::get('/roles/{role}/edit', App\Livewire\Admin\Role\Form::class)->name('roles.edit');

    Route::get('/permissions', App\Livewire\Admin\Permission\Index::class)->name('permissions.index');
});

require __DIR__.'/auth.php';
