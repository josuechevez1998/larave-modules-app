# Cached tenant lookup

> Fuente: https://tenancyforlaravel.com/docs/v3/cached-lookup
> Exportado: 2026-07-03 20:56 UTC

# Cached lookup

If you're using multiple databases, you may want to avoid making a query to the central database on **each tenant request** — for tenant identification. Even though the queries are very simple, the app has to establish a connection with the central database which is expensive.

To avoid this, you may enable caching on the tenant resolvers (all in the `Stancl\Tenancy\Resolvers` namespace):

- `DomainTenantResolver` (also used for subdomain identification)
- `PathTenantResolver`
- `RequestDataTenantResolver`

On each of these classes, you may set the following static properties:

```
// TenancyServiceProvider::boot()

use Stancl\Tenancy\Resolvers;

 // enable cache
DomainTenantResolver::$shouldCache = true;

// seconds, 3600 is the default value
DomainTenantResolver::$cacheTTL = 3600;

// specify some cache store
// null resolves to the default cache store
DomainTenantResolver::$cacheStore = 'redis';
```

## Cache invalidation

Updating and saving a Tenant model's attributes will cause the cached entry for this model to be invalidated when `DomainTenantResolver::$shouldCache` is set to `true`.

You may invalidate the cache by calling :

```
app(\Stancl\Tenancy\Resolvers\DomainTenantResolver::class)->invalidateCache($tenant);
```

> Note: When using the domain identification, the key of the cache contains the name of the domain. Make sure to invalidate the cache before making any changes if you intend to update the domain name.

[Edit on GitHub](https://github.com/stancl/tenancy-docs/edit/master/source/docs/v3/cached-lookup.blade.md)
