<?php

use Illuminate\Support\Facades\Route;

Route::get('/', App\Livewire\Home::class)->name('home');
Route::get('/p/{slug}', App\Livewire\Page::class)->name('page.show');



Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
Route::middleware(['auth', 'verified', 'role:super-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', App\Livewire\Admin\Dashboard::class)->name('dashboard');
    
    // Portfolio
    Route::get('/portfolios', App\Livewire\Admin\Portfolio\Index::class)->name('portfolios.index');
    Route::get('/portfolios/create', App\Livewire\Admin\Portfolio\Form::class)->name('portfolios.create');
    Route::get('/portfolios/{portfolio}/edit', App\Livewire\Admin\Portfolio\Form::class)->name('portfolios.edit');

    // Services
    Route::get('/services', App\Livewire\Admin\Service\Index::class)->name('services.index');
    Route::get('/services/create', App\Livewire\Admin\Service\Form::class)->name('services.create');
    Route::get('/services/{service}/edit', App\Livewire\Admin\Service\Form::class)->name('services.edit');

    // Settings
    Route::get('/settings', App\Livewire\Admin\Setting\Index::class)->name('settings.index');

    // Menus
    Route::get('/menus', App\Livewire\Admin\Menu\Index::class)->name('menus.index');
    Route::get('/menus/{menu}/builder', App\Livewire\Admin\Menu\Builder::class)->name('menus.builder');

    // Pages
    Route::get('/pages', App\Livewire\Admin\Page\Index::class)->name('pages.index');
    Route::get('/pages/create', App\Livewire\Admin\Page\Form::class)->name('pages.create');
    Route::get('/pages/{page}/edit', App\Livewire\Admin\Page\Form::class)->name('pages.edit');

    // Access Control
    Route::get('/users', App\Livewire\Admin\User\Index::class)->name('users.index');
    Route::get('/users/create', App\Livewire\Admin\User\Form::class)->name('users.create');
    Route::get('/users/{user}/edit', App\Livewire\Admin\User\Form::class)->name('users.edit');

    Route::get('/roles', App\Livewire\Admin\Role\Index::class)->name('roles.index');
    Route::get('/roles/create', App\Livewire\Admin\Role\Form::class)->name('roles.create');
    Route::get('/roles/{role}/edit', App\Livewire\Admin\Role\Form::class)->name('roles.edit');

    Route::get('/permissions', App\Livewire\Admin\Permission\Index::class)->name('permissions.index');

    // Blog
    Route::get('/posts', App\Livewire\Admin\Blog\Index::class)->name('blog.index');
    Route::get('/posts/create', App\Livewire\Admin\Blog\CreateEdit::class)->name('blog.create');
    Route::get('/posts/{id}/edit', App\Livewire\Admin\Blog\CreateEdit::class)->name('blog.edit');
});

Route::get('/blog', App\Livewire\Public\Blog\Index::class)->name('blog.index');
Route::get('/blog/{slug}', App\Livewire\Public\Blog\Show::class)->name('blog.show');

// New Portal Pages
Route::get('/about', App\Livewire\Public\About::class)->name('about');
Route::get('/services', App\Livewire\Public\Services\Index::class)->name('services.index');

Route::get('/portfolio', App\Livewire\Public\Portfolio\Index::class)->name('portfolio.index');
Route::get('/portfolio/{slug}', App\Livewire\Public\Portfolio\Show::class)->name('portfolio.show');

Route::get('/ai-lab', App\Livewire\Public\AiLab\Index::class)->name('ai-lab.index');
Route::get('/ai-lab/{slug}', App\Livewire\Public\AiLab\Show::class)->name('ai-lab.show');

// Fallback for Shared Hosting (CPanel) where symlink() is disabled
Route::get('storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);

    if (!file_exists($filePath)) {
        abort(404);
    }

    return response()->file($filePath);
})->where('path', '.*');

require __DIR__.'/auth.php';
