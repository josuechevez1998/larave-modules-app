# Vite bundler

> Fuente: https://tenancyforlaravel.com/docs/v3/features/vite-bundler
> Exportado: 2026-07-03 20:56 UTC

# Vite bundler

Enabling the `ViteBundler` feature makes Vite generate correct asset paths by using the `global_asset()` helper instead of the default `asset()` helper.

To enable the feature, uncomment `Stancl\Tenancy\Features\ViteBundler::class` in the `features` section of the tenancy config:

```
'features' => [
    // [...]
    Stancl\Tenancy\Features\ViteBundler::class,
],
```

[Edit on GitHub](https://github.com/stancl/tenancy-docs/edit/master/source/docs/v3/features/vite-bundler.blade.md)
