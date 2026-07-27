# Laravel Horizon integration

> Fuente: https://tenancyforlaravel.com/docs/v3/integrations/horizon
> Exportado: 2026-07-03 20:56 UTC

# Laravel Horizon

> Note: **Horizon is only accessible on the central domain**. You can separate the jobs by [tagging them with tenant IDs](#tags).

Make sure your [queues](https://tenancyforlaravel.com/docs/v3/queues) are configured correctly before using this.

## Tags

You may add the current tenant's ID to your job tags by defining a `tags` method on the class:

```
/**
* Get the tags that should be assigned to the job.
*
* @return  array
*/
public function tags()
{
    return [
        'tenant:' . tenant('id'),
    ];
}
```

[Edit on GitHub](https://github.com/stancl/tenancy-docs/edit/master/source/docs/v3/integrations/horizon.blade.md)
