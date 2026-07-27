# Diglactic Laravel Breadcrumbs

## Purpose
Auth screens show a breadcrumb trail. Public/welcome/landing views omit it.

## Install
```bash
composer require diglactic/laravel-breadcrumbs
php artisan vendor:publish --provider="Diglactic\Breadcrumbs\ServiceProvider" --tag=breadcrumbs-config
php artisan vendor:publish --provider="Diglactic\Breadcrumbs\ServiceProvider" --tag=breadcrumbs-routes
```

Ya está en el proyecto (`config/breadcrumbs.php`, `routes/breadcrumbs.php`). Vista: `breadcrumbs::tailwind` + UI Flux vía `<x-breadcrumbs.trail />`.

## Layout
`<x-breadcrumbs.trail class="mb-4" />` en `resources/views/components/layouts/app.blade.php` (pantallas autenticadas).

## Defining trails
`routes/breadcrumbs.php`:
```php
Breadcrumbs::for('profile', function ($trail) {
    $trail->parent('dashboard');
    $trail->push(__('Profile'), route('profile'));
});
```

`make:crud` (Livewire/blade) **añade** las rutas `index/create/show/edit` automáticamente.

## Docs
https://github.com/diglactic/laravel-breadcrumbs
