<?php

namespace Modules\Roles\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;

class RolesServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Roles';

    protected string $nameLower = 'roles';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];
}
