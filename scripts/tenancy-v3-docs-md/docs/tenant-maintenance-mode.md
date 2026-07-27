# Tenant maintenance mode

> Fuente: https://tenancyforlaravel.com/docs/v3/tenant-maintenance-mode
> Exportado: 2026-07-03 20:56 UTC

# Tenant maintenance mode

You may put specific tenants into maintenance mode using the `MaintenanceMode` trait.

Apply it on your [Tenant model](https://tenancyforlaravel.com/docs/v3/tenants):

```
use Stancl\Tenancy\Database\Concerns\MaintenanceMode;

class Tenant extends BaseTenant
{
    use MaintenanceMode;
}
```

This will let you use the following method on each tenant object:

```
$tenant->putDownForMaintenance();
```

To remove specific tenant from maintenance mode:

```
$tenant->update(['maintenance_mode' => null]);
```

## Middleware

You will also need to use the `Stancl\Tenancy\Middleware\CheckTenantForMaintenanceMode` middleware on your tenant routes.

[Edit on GitHub](https://github.com/stancl/tenancy-docs/edit/master/source/docs/v3/tenant-maintenance-mode.blade.md)
