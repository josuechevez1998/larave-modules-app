# Custom CRUD stubs (Services + Flux + modules)

Published stubs: `resources/stubs/crud/`. Config: `config/crud.php` → `stub_path` = `resource_path('stubs/')`.

## Conventions

1. After `php artisan make:crud {table} livewire`, also copy/generate `App\Services\{Model}Service` from `Service.stub` (ibex does not emit Services by default — create manually or copy stub).
2. Livewire Index uses `ListQueryBuilder` (sort / status_id / per_page).
3. Livewire Form / API Controller call `{Model}Service`.
4. Views under `views/livewire/12/` already use Flux inputs/buttons.
5. Prefer module routes when the feature belongs to a module.

## Command

```bash
./vendor/bin/sail artisan make:crud {table} livewire
```

Only for standard CRUDs. Hand-write special features.
