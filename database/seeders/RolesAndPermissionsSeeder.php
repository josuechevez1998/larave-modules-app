<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        setPermissionsTeamId(null);

        $permissionsByModule = [
            'Core' => [
                'admin.access',
                'users.manage',
                'roles.manage',
                'teams.manage',
                'settings.institution',
            ],
        ];

        $allPermissionNames = [];

        foreach ($permissionsByModule as $module => $names) {
            foreach ($names as $name) {
                Permission::query()->updateOrCreate(
                    ['name' => $name, 'guard_name' => 'web'],
                    ['module' => $module]
                );
                $allPermissionNames[] = $name;
            }
        }

        $superAdmin = Role::query()->firstOrCreate(
            [
                'name' => 'Super Admin',
                'guard_name' => 'web',
                'team_id' => null,
            ]
        );

        $superAdmin->syncPermissions($allPermissionNames);

        $user = User::query()->updateOrCreate(
            ['email' => 'admin@saas.test'],
            [
                'name' => 'Admin',
                'password' => 'admin123*',
                'email_verified_at' => now(),
            ]
        );

        $team = Team::query()->firstOrCreate(
            ['slug' => 'platform'],
            ['name' => 'Platform']
        );

        $user->teams()->syncWithoutDetaching([$team->id]);

        setPermissionsTeamId($team->id);
        $user->unsetRelation('roles')->unsetRelation('permissions');

        if (! $user->hasRole('Super Admin')) {
            $user->assignRole($superAdmin);
        }

        $user->forceFill(['current_team_id' => $team->id])->save();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
