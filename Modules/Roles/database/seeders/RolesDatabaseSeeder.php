<?php

namespace Modules\Roles\Database\Seeders;

use Illuminate\Database\Seeder;

class RolesDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            \Database\Seeders\RolesAndPermissionsSeeder::class,
        ]);
    }
}
