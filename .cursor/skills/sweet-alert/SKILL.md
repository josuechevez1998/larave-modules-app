# SweetAlert2 (realrashid/sweet-alert)

## Purpose
Toasts/modales de confirmación en pantallas autenticadas, con look stone/teal (Tailwind) sin CDN de temas (CSP).

## Install
```bash
./vendor/bin/sail composer require realrashid/sweet-alert
./vendor/bin/sail artisan sweetalert:publish
```

Publica: `config/sweetalert.php`, `resources/views/vendor/sweetalert/`, `public/vendor/sweetalert/`.

## Layout
`@include('sweetalert::alert')` en `resources/views/components/layouts/app/sidebar.blade.php`.

Middleware `RealRashid\SweetAlert\ToSweetAlert` en `bootstrap/app.php` (web).

## Estilos
- `SWEET_ALERT_THEME=default` (no jsDelivr — bloqueado por CSP).
- Clases `swal-app*` en `config/sweetalert.php` + CSS en `resources/css/app.css`.
- `alwaysLoadJS=true` para toasts desde Livewire (`$this->js(livewire_swal_toast(...))`).

## Uso
```php
// Tras create/update/delete + redirect (recomendado; dura 30s)
flash_swal_toast(__('Registro guardado.'), 'success');
return $this->redirectRoute('blogs.index'); // sin navigate: true; toast ~12s

// Controllers clásicos
toast(__('Guardado.'), 'success');

// Solo in-page (sin redirect)
$this->js(livewire_swal_toast(__('Listo.')));

// Confirm → delete
public function confirmDelete(int $id): void
{
    $this->js(livewire_swal_confirm_delete($id));
}
```

## Docs
https://github.com/realrashid/sweet-alert
