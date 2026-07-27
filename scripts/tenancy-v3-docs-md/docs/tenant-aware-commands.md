# Tenant-aware commands

> Fuente: https://tenancyforlaravel.com/docs/v3/tenant-aware-commands
> Exportado: 2026-07-03 20:56 UTC

# Tenant-aware commands

Even though [`tenants:run`](https://tenancyforlaravel.com/docs/v3/console-commands#run) lets you run arbitrary artisan commands for tenants, you may want to have strictly tenant commands.

To make a command tenant-aware, utilize the `TenantAwareCommand` trait:

```
class MyCommand extends Command
{
    use TenantAwareCommand;
}
```

However, this trait requires you to implement a `getTenants()` method that returns an array of `Tenant` instances.

If you don't want to implement the options/arguments yourself, you may use one of these two traits:
- `HasATenantsOption` - accepts multiple tenant IDs, optional -- by default the command is executed for all tenants
- `HasATenantArgument` - accepts a single tenant ID, required argument

These traits implement the `getTenants()` method needed by `TenantAwareCommand`.

> Note: If you're using a custom constructor for your command, you need to add `$this->specifyParameters()` at the end for the option/argument traits to take effect.

So if you use these traits in combination with `TenantAwareCommand`, you won't have to change a thing in your command:

```
class FooCommand extends Command
{
    use TenantAwareCommand, HasATenantsOption;

    public function handle()
    {
        //
    }
}

class BarCommand extends Command
{
    use TenantAwareCommand, HasATenantArgument;

    public function handle()
    {
        //
    }
}
```

### Custom implementation

If you want more control, you may implement this functionality yourself by simply accepting a `tenant_id` argument and then inside `handle()` doing something like this:

```
tenancy()->find($this->argument('tenant_id'))->run(function () {
    // Your actual command code
});
```

[Edit on GitHub](https://github.com/stancl/tenancy-docs/edit/master/source/docs/v3/tenant-aware-commands.blade.md)
