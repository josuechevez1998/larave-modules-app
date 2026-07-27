# Testing

> Fuente: https://tenancyforlaravel.com/docs/v3/testing
> Exportado: 2026-07-03 20:56 UTC

# Testing

> If you're a sponsor, you can get an opinionated, but automatic testing setup on the site with exclusive content for sponsors: https://sponsors.tenancyforlaravel.com/frictionless-testing-setup

TODO: Review

## Events

Keep in mind that the package makes heavy use of events, so if you use `Event::fake()` anywhere in your tests, tenancy initialization and related processes might break.

For that reason, try to be selective about faking tests. Use for example:

```
Event::fake([MyEvent::class]);
```

rather than

```
Event::fake();
```

## Central app

To test your central app, just write normal Laravel tests.

## Tenant app

Note: If you're using multi-database tenancy & the automatic mode, it's not possible to use `:memory:` SQLite databases or the `RefreshDatabase` trait due to the switching of default database.

To test the tenant part of the application, create a tenant in the `setUp()` method and initialize tenancy.

You may also want to do something like this:

```
class TestCase // extends ...
{
    protected $tenancy = false;

    public function setUp(): void
    {
        parent::setUp();

        if ($this->tenancy) {
            $this->initializeTenancy();
        }
    }

    public function initializeTenancy()
    {
        $tenant = Tenant::create();

        tenancy()->initialize($tenant);
    }

    // ...
}
```

And in your individual test cases:

```
class FooTest extends TestCase
{
    protected $tenancy = true;

    /** @test    */
    public function some_test()
    {
        $this->assertTrue(...);
    }
}
```

Or you may want to create a separate TestCase class for tenant tests, for better organization.

[Edit on GitHub](https://github.com/stancl/tenancy-docs/edit/master/source/docs/v3/testing.blade.md)
