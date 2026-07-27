# Livewire integration

> Fuente: https://tenancyforlaravel.com/docs/v3/integrations/livewire
> Exportado: 2026-07-03 20:56 UTC

# Livewire

Open the `config/livewire.php` file and change this:

```
'middleware_group' => ['web'],
```

to this:

```
'middleware_group' => [
    'web',
    'universal',
    InitializeTenancyByDomain::class, // or whatever tenancy middleware you use
],
```

In Livewire 3, the configuration key `middleware_group` has been removed, so instead add the following in `TenancyServiceProvider` (or any other provider):

```
public function boot(): void
{
    // ...

    Livewire::setUpdateRoute(function ($handle) {
        return Route::post('/livewire/update', $handle)
            ->middleware(
                'web',
                'universal',
                InitializeTenancyByDomain::class, // or whatever tenancy middleware you use
            );
    });
}
```

To make file uploads work on Livewire 3, set the following in any service provider:

```
// specify the right identification middleware
FilePreviewController::$middleware = ['web', 'universal', InitializeTenancyByDomain::class];
```

And change `livewire.temporary_file_upload.middleware` to include the tenancy middleware as well:

```
// config/livewire.php

'livewire.temporary_file_upload.middleware' => ['throttle:60,1', 'universal', InitializeTenancyByDomain::class],
```

Now you can use Livewire both in the central app and the tenant app.

Also make sure to enable [universal routes](https://tenancyforlaravel.com/docs/v3/features/universal-routes).

And if you're using file uploads, read the [Real-time facades](https://tenancyforlaravel.com/docs/v3/realtime-facades) page of the documentation. Livewire uses real-time facades in the uploading logic.

[Edit on GitHub](https://github.com/stancl/tenancy-docs/edit/master/source/docs/v3/integrations/livewire.blade.md)
