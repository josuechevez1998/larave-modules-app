# Ibex CRUD Generator

## Purpose
Scaffold standard CRUDs only. Special features are hand-written Services + Livewire.

## Install
```bash
composer require ibex/crud-generator --dev
php artisan vendor:publish --tag=crud
php artisan vendor:publish --tag=stubs-crud
```

## Usage
```bash
php artisan make:crud {table} livewire
```

## Custom stubs
Published under `resources/stubs/crud/`. Adapted for:
- `App\Services\{Model}Service`
- Livewire Flux views
- Thin API controllers
- Module routes when generating into a module

## Docs
https://github.com/awais-vteams/laravel-crud-generator
