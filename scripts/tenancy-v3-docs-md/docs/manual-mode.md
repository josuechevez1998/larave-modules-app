# Manual tenancy mode

> Fuente: https://tenancyforlaravel.com/docs/v3/manual-mode
> Exportado: 2026-07-03 20:56 UTC

# Manual mode

> See: [Automatic mode](https://tenancyforlaravel.com/docs/v3/automatic-mode)

If you wish to use the package only to keep track of the current tenant and make the application tenant-aware manually — without using the [Tenancy bootstrappers](https://tenancyforlaravel.com/docs/v3/tenancy-bootstrappers), you can absolutely do that.

You may use the `Stancl\Tenancy\Database\Concerns\CentralConnection` and `Stancl\Tenancy\Database\Concerns\TenantConnection` model traits to make models explicitly use the given connections.

To create the tenant connection, set up the `CreateTenantConnection` listener:

```
// app/Providers/TenancyServiceProvider.php

Events\TenancyInitialized::class => [
    Listeners\CreateTenantConnection::class,
],
```

This approach is generally discouraged, because you lose all of the benefits of the [automatic mode](https://tenancyforlaravel.com/docs/v3/automatic-mode), but **there won't be any issues with the package** if you decide to use the manual mode. You might not be able to integrate other packages as easily, but if for whatever reason it makes more sense for your project to use this approach, feel comfortable to do so.

[Edit on GitHub](https://github.com/stancl/tenancy-docs/edit/master/source/docs/v3/manual-mode.blade.md)
