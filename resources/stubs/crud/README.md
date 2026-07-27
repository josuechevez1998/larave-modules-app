# Custom CRUD stubs (Services + Flux + modules)

Published stubs: `resources/stubs/crud/`. Config: `config/crud.php` → `stub_path` = `resource_path('stubs/')`.

## Conventions

1. `MakeCrudCommand` también genera `{Model}Service` desde `service.stub`.
2. Livewire Index usa `ListQueryBuilder` (sort / `per_page` / filtros por columna).
3. Filtros por columna en Index: texto (`LIKE`) si no hay FK; `flux:select` de catálogo si la columna es FK (`*_id` / foreign key). `clearFilters` + `<x-ui.clear-filters />`.
4. Formularios: columnas FK → select de catálogo (`{relation}Options()`); el resto → input texto. Reglas `exists:tabla,id`.
5. Livewire Form / API Controller llaman a `{Model}Service`.
6. Vistas `views/livewire/12/` → Flux + clases `ui-*` (claro/oscuro). Includes con `{{modelViewFull}}.form` (`livewire.blog.form` o `blog::livewire.blog.form` según `--module=`).
7. DI del Service en acciones del componente Livewire (`save(Service $s)` → `$this->form->store($s)`). Los `Livewire\Form` **no** resuelven type-hints al llamar `$this->form->store()`.
8. Breadcrumbs Diglactic: `make:crud` append a `routes/breadcrumbs.php`; trail en layout app.
9. SweetAlert: toasts con `livewire_swal_toast()` / `flash_swal_toast()`; **Eliminar** siempre vía `confirmDelete` → `livewire_swal_confirm_delete()` (nunca `wire:confirm` nativo).
10. Loading panel (no login): `<x-ui.livewire-busy />` en layout app (barra superior con delay); forms con `<x-ui.action-button />`; Index atenúa tabla (`ui-table-busy`) al filtrar/paginar; Eliminar/Limpiar con loading en el botón.
11. Preferir `--module=` cuando el feature vive en un módulo.

## Command

```bash
./vendor/bin/sail artisan make:crud {table} livewire
```

Only for standard CRUDs. Hand-write special features.
