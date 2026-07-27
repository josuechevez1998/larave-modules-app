# Cross-domain redirect

> Fuente: https://tenancyforlaravel.com/docs/v3/features/cross-domain-redirect
> Exportado: 2026-07-03 20:56 UTC

# Cross-domain redirect

To enable this feature, uncomment the `Stancl\Tenancy\Features\CrossDomainRedirect::class` line in your `tenancy.features` config.

Sometimes you may want to redirect the user to a specific route on a different domain (than the current one). Let's say you want to redirect a tenant to the `home` path on their domain after they sign up:

```
return redirect()->route('home')->domain($domain);
```

You can also use the `tenant_route()` helper to redirect users to another domain.

```
return redirect(tenant_route($domain, 'home'));
```

[Edit on GitHub](https://github.com/stancl/tenancy-docs/edit/master/source/docs/v3/features/cross-domain-redirect.blade.md)
