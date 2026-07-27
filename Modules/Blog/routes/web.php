<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\BlogController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/blogs', \Modules\Blog\Livewire\Blogs\Index::class)->name('blogs.index');
    Route::get('/blogs/create', \Modules\Blog\Livewire\Blogs\Create::class)->name('blogs.create');
    Route::get('/blogs/show/{blog}', \Modules\Blog\Livewire\Blogs\Show::class)->name('blogs.show');
    Route::get('/blogs/update/{blog}', \Modules\Blog\Livewire\Blogs\Edit::class)->name('blogs.edit');
});

// CRUD: Blog (make:crud --module=Blog)
