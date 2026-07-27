<?php

namespace App\Providers;

use App\Http\Middleware\SetLocale;
use App\Http\Middleware\TeamsPermission;
use App\Models\User;
use Illuminate\Foundation\Http\Kernel;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::before(function (User $user, string $ability): ?bool {
            return $user->hasRole('Super Admin') ? true : null;
        });

        Gate::define('viewApiDocs', function (?User $user): bool {
            if (app()->environment('local')) {
                return true;
            }

            return $user?->hasRole('Super Admin') ?? false;
        });

        Gate::define('viewLogViewer', function (?User $user): bool {
            if (app()->environment('local')) {
                return true;
            }

            return $user?->hasRole('Super Admin') ?? false;
        });

        /** @var Kernel $kernel */
        $kernel = app()->make(Kernel::class);
        $kernel->addToMiddlewarePriorityBefore(
            TeamsPermission::class,
            SubstituteBindings::class,
        );

        Livewire::addPersistentMiddleware([
            TeamsPermission::class,
            SetLocale::class,
        ]);
    }
}
