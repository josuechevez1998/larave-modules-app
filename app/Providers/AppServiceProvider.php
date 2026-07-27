<?php

namespace App\Providers;

use App\Console\Commands\MakeCrudCommand;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\TeamsPermission;
use App\Models\User;
use Illuminate\Console\Application as Artisan;
use Illuminate\Foundation\Http\Kernel;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Nwidart\Modules\Facades\Module;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Artisan::starting(function (Artisan $artisan): void {
            $artisan->resolveCommands([MakeCrudCommand::class]);
        });

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

        $this->registerModuleLivewireNamespaces();
    }

    /**
     * Livewire 4 only discovers App\Livewire by default; module class components
     * (full-page routes) need an explicit location/namespace per module.
     */
    protected function registerModuleLivewireNamespaces(): void
    {
        foreach (Module::allEnabled() as $module) {
            $name = $module->getName();

            Livewire::addNamespace(
                namespace: $module->getLowerName(),
                classNamespace: 'Modules\\'.$name.'\\Livewire',
                classPath: module_path($name, 'app/Livewire'),
                classViewPath: module_path($name, 'resources/views/livewire'),
            );
        }
    }
}
