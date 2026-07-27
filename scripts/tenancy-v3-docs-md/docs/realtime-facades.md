# Real-time facades

> Fuente: https://tenancyforlaravel.com/docs/v3/realtime-facades
> Exportado: 2026-07-03 20:56 UTC

# Real-time facades

When using `storage_path()` suffixing (for local filesystem tenancy), each tenant gets a separate subdirectory in `storage/`.

This means that storage paths look like this:

```
storage/tenant123/app/foo.png
```

**not** like this:

```
storage/app/tenant123/foo.png
```

This means that the other directories in `storage/` are also tenant-scoped. Importantly, the `framework` directory.

## The issue with real-time facades

When real-time facades are used, Laravel creates a PHP file with facade-like code, stores it in `storage_path/framework/cache` and autoloads it.

## Creating framework directories for tenants

To solve this, you need to create these directories for tenants. But note that you only need this if:

1. you're using `storage_path()` suffixing (enabled in `tenancy` config)
2. **and** you're using real-time facades

You can create these directories by using the [event system](https://tenancyforlaravel.com/docs/v3/event-system).

Use a job pipeline, because you need to initialize tenancy to run this code & you need tenancy initialization to even be possible (you can't initialize tenancy before the tenant's database is created, for example).

Add a job like this to your `TenantCreated` job pipeline:

```
<?php

namespace App\Jobs;

use Stancl\Tenancy\Contracts\Tenant;

class CreateFrameworkDirectoriesForTenant
{
    protected $tenant;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    public function handle()
    {
        $this->tenant->run(function ($tenant) {
            $storage_path = storage_path();

            mkdir("$storage_path/framework/cache", 0777, true);
        });
    }
}
```

[Edit on GitHub](https://github.com/stancl/tenancy-docs/edit/master/source/docs/v3/realtime-facades.blade.md)
