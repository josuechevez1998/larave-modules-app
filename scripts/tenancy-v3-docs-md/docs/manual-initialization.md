# Manual tenancy initialization

> Fuente: https://tenancyforlaravel.com/docs/v3/manual-initialization
> Exportado: 2026-07-03 20:56 UTC

# Manual initialization

Sometimes you may want to initialize tenancy manually — that is, not using web middleware, command traits, queue tenancy etc. A common use case for this is if you need to use `artisan tinker` for a specific tenant.

For that, use the `initialize()` method on `Stancl\Tenancy\Tenancy`. You can resolve the `Tenancy` instance out of the container using the `tenancy()` helper.

```
$tenant = Tenant::find('some-id');

tenancy()->initialize($tenant);
```

[Edit on GitHub](https://github.com/stancl/tenancy-docs/edit/master/source/docs/v3/manual-initialization.md)
