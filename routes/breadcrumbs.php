<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail): void {
    $trail->push(__('Dashboard'), route('dashboard'));
});

Breadcrumbs::for('settings.profile', function (BreadcrumbTrail $trail): void {
    $trail->parent('dashboard');
    $trail->push(__('app.settings'), route('settings.profile'));
    $trail->push(__('app.profile'), route('settings.profile'));
});

Breadcrumbs::for('settings.password', function (BreadcrumbTrail $trail): void {
    $trail->parent('dashboard');
    $trail->push(__('app.settings'), route('settings.profile'));
    $trail->push(__('app.password'), route('settings.password'));
});

Breadcrumbs::for('settings.appearance', function (BreadcrumbTrail $trail): void {
    $trail->parent('dashboard');
    $trail->push(__('app.settings'), route('settings.profile'));
    $trail->push(__('app.appearance'), route('settings.appearance'));
});

Breadcrumbs::for('settings.language', function (BreadcrumbTrail $trail): void {
    $trail->parent('dashboard');
    $trail->push(__('app.settings'), route('settings.profile'));
    $trail->push(__('app.locale'), route('settings.language'));
});

Breadcrumbs::for('settings.institution', function (BreadcrumbTrail $trail): void {
    $trail->parent('dashboard');
    $trail->push(__('app.settings'), route('settings.profile'));
    $trail->push(__('app.institution_identity'), route('settings.institution'));
});

// Backwards-compatible alias
Breadcrumbs::for('profile', function (BreadcrumbTrail $trail): void {
    $trail->parent('dashboard');
    $trail->push(__('app.profile'), route('settings.profile'));
});

// CRUD: Blog
Breadcrumbs::for('blogs.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('dashboard');
    $trail->push(__('Blogs'), route('blogs.index'));
});

Breadcrumbs::for('blogs.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('blogs.index');
    $trail->push(__('Nuevo'), route('blogs.create'));
});

Breadcrumbs::for('blogs.show', function (BreadcrumbTrail $trail, \Modules\Blog\Models\Blog $blog): void {
    $trail->parent('blogs.index');
    $trail->push((string) ($blog->nombre ?: __('Detalle')), route('blogs.show', $blog));
});

Breadcrumbs::for('blogs.edit', function (BreadcrumbTrail $trail, \Modules\Blog\Models\Blog $blog): void {
    $trail->parent('blogs.index');
    $trail->push(__('Editar'), route('blogs.edit', $blog));
});

// CRUD table: todo_estado
Breadcrumbs::for('todo-estados.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('dashboard');
    $trail->push(__('Estados ToDo'), route('todo-estados.index'));
});

Breadcrumbs::for('todo-estados.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('todo-estados.index');
    $trail->push(__('Nuevo'), route('todo-estados.create'));
});

Breadcrumbs::for('todo-estados.show', function (BreadcrumbTrail $trail, \Modules\ToDo\Models\TodoEstado $todoEstado): void {
    $trail->parent('todo-estados.index');
    $trail->push(__('Detalle'), route('todo-estados.show', $todoEstado));
});

Breadcrumbs::for('todo-estados.edit', function (BreadcrumbTrail $trail, \Modules\ToDo\Models\TodoEstado $todoEstado): void {
    $trail->parent('todo-estados.index');
    $trail->push(__('Editar'), route('todo-estados.edit', $todoEstado));
});

// CRUD table: todos
Breadcrumbs::for('todos.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('dashboard');
    $trail->push(__('ToDos'), route('todos.index'));
});

Breadcrumbs::for('todos.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('todos.index');
    $trail->push(__('Nuevo'), route('todos.create'));
});

Breadcrumbs::for('todos.show', function (BreadcrumbTrail $trail, \Modules\ToDo\Models\Todo $todo): void {
    $trail->parent('todos.index');
    $trail->push(__('Detalle'), route('todos.show', $todo));
});

Breadcrumbs::for('todos.edit', function (BreadcrumbTrail $trail, \Modules\ToDo\Models\Todo $todo): void {
    $trail->parent('todos.index');
    $trail->push(__('Editar'), route('todos.edit', $todo));
});
