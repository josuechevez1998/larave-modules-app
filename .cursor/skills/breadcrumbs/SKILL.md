# Diglactic Laravel Breadcrumbs

## Purpose
Auth screens show a breadcrumb trail. Public/welcome/landing views omit it.

## Install
```bash
composer require diglactic/laravel-breadcrumbs
php artisan vendor:publish --provider="Diglactic\Breadcrumbs\ServiceProvider" --tag=breadcrumbs-config
php artisan vendor:publish --provider="Diglactic\Breadcrumbs\ServiceProvider" --tag=breadcrumbs-routes
```

## Layout
`<x-breadcrumbs.trail />` in `resources/views/layouts/app.blade.php` only.

## Defining trails
`routes/breadcrumbs.php`:
```php
Breadcrumbs::for('profile', function ($trail) {
    $trail->parent('dashboard');
    $trail->push(__('Profile'), route('profile'));
});
```

## Docs
https://github.com/diglactic/laravel-breadcrumbs
