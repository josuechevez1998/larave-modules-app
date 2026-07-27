---
name: tenancy-v3-docs
description: >-
  Documentación local de Stancl Tenancy v3 exportada desde tenancyforlaravel.com.
  Usar cuando trabajes con stancl/tenancy, multi-tenancy, tenants, domains,
  identificación de tenants, colas, migraciones tenant o bootstrappers.
---

# Stancl Tenancy v3 — documentación local

Exportada el **2026-07-03 20:56 UTC** desde [https://tenancyforlaravel.com/docs/v3](https://tenancyforlaravel.com/docs/v3).

## Cuándo usar este skill

- Configurar o depurar `stancl/tenancy` v3 en Laravel.
- Crear tenants, dominios, pipelines `TenantCreated`, colas o migraciones tenant.
- Comparar modos automatic/manual, single-db vs multi-db.
- Integraciones (Livewire, Sanctum, Sail, Spatie, etc.).

## Cómo actualizar

Desde la raíz del skill:

```bash
bash run.sh
```

Opcional: actualizar slugs desde GitHub (si tu red lo permite):

```bash
TENANCY_DOCS_FETCH_NAV=1 bash run.sh
```

## Índice de páginas

- [Upgrading from 2.x](docs/upgrading.md) — `upgrading`
- [Introduction](docs/introduction.md) — `introduction`
- [Quickstart Tutorial](docs/quickstart.md) — `quickstart`
- [Installation](docs/installation.md) — `installation`
- [Configuration](docs/configuration.md) — `configuration`
- [Compared to other packages](docs/package-comparison.md) — `package-comparison`
- [The two applications](docs/the-two-applications.md) — `the-two-applications`
- [Tenants](docs/tenants.md) — `tenants`
- [Domains](docs/domains.md) — `domains`
- [Event system](docs/event-system.md) — `event-system`
- [Routes](docs/routes.md) — `routes`
- [Tenancy bootstrappers](docs/tenancy-bootstrappers.md) — `tenancy-bootstrappers`
- [Optional features](docs/optional-features.md) — `optional-features`
- [User impersonation](docs/features-user-impersonation.md) — `features/user-impersonation`
- [Telescope tags](docs/features-telescope-tags.md) — `features/telescope-tags`
- [Tenant-specific config](docs/features-tenant-config.md) — `features/tenant-config`
- [Cross-domain redirect](docs/features-cross-domain-redirect.md) — `features/cross-domain-redirect`
- [Universal routes](docs/features-universal-routes.md) — `features/universal-routes`
- [Vite bundler](docs/features-vite-bundler.md) — `features/vite-bundler`
- [Automatic tenancy mode](docs/automatic-mode.md) — `automatic-mode`
- [Manual tenancy mode](docs/manual-mode.md) — `manual-mode`
- [Single-database tenancy](docs/single-database-tenancy.md) — `single-database-tenancy`
- [Tenant identification](docs/tenant-identification.md) — `tenant-identification`
- [Early identification](docs/early-identification.md) — `early-identification`
- [Multi-database tenancy](docs/multi-database-tenancy.md) — `multi-database-tenancy`
- [Migrations](docs/migrations.md) — `migrations`
- [Customizing tenant databases](docs/customizing-databases.md) — `customizing-databases`
- [Synced resources between tenants](docs/synced-resources-between-tenants.md) — `synced-resources-between-tenants`
- [Session scoping](docs/session-scoping.md) — `session-scoping`
- [Queues](docs/queues.md) — `queues`
- [Manual tenancy initialization](docs/manual-initialization.md) — `manual-initialization`
- [Testing](docs/testing.md) — `testing`
- [Integration with other packages](docs/integrating.md) — `integrating`
- [Integration with Spatie packages](docs/integrations-spatie.md) — `integrations/spatie`
- [Laravel Horizon integration](docs/integrations-horizon.md) — `integrations/horizon`
- [Laravel Passport integration](docs/integrations-passport.md) — `integrations/passport`
- [Laravel Nova integration](docs/integrations-nova.md) — `integrations/nova`
- [Laravel Telescope integration](docs/integrations-telescope.md) — `integrations/telescope`
- [Livewire integration](docs/integrations-livewire.md) — `integrations/livewire`
- [Laravel Orchid integration](docs/integrations-orchid.md) — `integrations/orchid`
- [Laravel Sanctum integration](docs/integrations-sanctum.md) — `integrations/sanctum`
- [Laravel Sail integration](docs/integrations-sail.md) — `integrations/sail`
- [Console commands](docs/console-commands.md) — `console-commands`
- [Tenant-aware commands](docs/tenant-aware-commands.md) — `tenant-aware-commands`
- [Tenant attribute encryption](docs/tenant-attribute-encryption.md) — `tenant-attribute-encryption`
- [Cached tenant lookup](docs/cached-lookup.md) — `cached-lookup`
- [Real-time facades](docs/realtime-facades.md) — `realtime-facades`
- [Tenant maintenance mode](docs/tenant-maintenance-mode.md) — `tenant-maintenance-mode`

## Páginas no exportadas

- Ninguna.
