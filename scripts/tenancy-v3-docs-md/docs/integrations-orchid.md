# Laravel Orchid integration

> Fuente: https://tenancyforlaravel.com/docs/v3/integrations/orchid
> Exportado: 2026-07-03 20:56 UTC

# Laravel Orchid

First, set up tenancy following the [quickstart guide](https://tenancyforlaravel.com/docs/v3/quickstart), and install [Laravel Orchid](https://orchid.software/en/docs/installation/).

To use Orchid both in the central and the tenant app:

– Copy the user and Orchid migrations to `migrations\tenant`

- Enable [universal routes](https://tenancyforlaravel.com/docs/v3/features/universal-routes)
- Add your tenant identification middleware to `config\platform.php` (feel free to use a different identification middleware):

  ```
  'middleware' => [
      'public'  => ['web', 'universal', InitializeTenancyByDomain::class], // Don't forget to import the middleware
      'private' => ['web', 'platform', 'universal', InitializeTenancyByDomain::class],
  ],
  ```
- Add a route to `routes\platform.php`:

  ```
  Route::screen('/', PlatformScreen::class)
      ->name('platform.index')
      ->breadcrumbs(function (Trail $trail) {
          return $trail->push(__('Home'), route('platform.index'));
      });
  ```
- Add 'platform' middleware to your tenant routes (`routes\tenant.php`):

  ```
  Route::middleware([
      'web',
      'platform',
      InitializeTenancyByDomain::class,
      PreventAccessFromCentralDomains::class,
  ]);
  ```
- If listing users in the admin panel throws an exception, change line 55 in the UserListLayout class to `return $user->updated_at?->toDateTimeString()` (add null-safe operator)

[Edit on GitHub](https://github.com/stancl/tenancy-docs/edit/master/source/docs/v3/integrations/orchid.blade.md)
