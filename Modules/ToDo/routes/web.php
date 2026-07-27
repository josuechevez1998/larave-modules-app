<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function (): void {
    // CRUD table: todo_estado (make:crud --module=ToDo)
    Route::get('/todo-estados', \Modules\ToDo\Livewire\TodoEstados\Index::class)->name('todo-estados.index');
    Route::get('/todo-estados/create', \Modules\ToDo\Livewire\TodoEstados\Create::class)->name('todo-estados.create');
    Route::get('/todo-estados/show/{todoEstado}', \Modules\ToDo\Livewire\TodoEstados\Show::class)->name('todo-estados.show');
    Route::get('/todo-estados/update/{todoEstado}', \Modules\ToDo\Livewire\TodoEstados\Edit::class)->name('todo-estados.edit');

    // CRUD table: todos (make:crud --module=ToDo)
    Route::get('/todos', \Modules\ToDo\Livewire\Todos\Index::class)->name('todos.index');
    Route::get('/todos/create', \Modules\ToDo\Livewire\Todos\Create::class)->name('todos.create');
    Route::get('/todos/show/{todo}', \Modules\ToDo\Livewire\Todos\Show::class)->name('todos.show');
    Route::get('/todos/update/{todo}', \Modules\ToDo\Livewire\Todos\Edit::class)->name('todos.edit');
});
