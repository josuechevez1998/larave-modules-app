<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'code' => 'active',
                'group' => 'generic',
                'name' => ['es' => 'Activo', 'en' => 'Active'],
                'color' => 'green',
                'sort' => 1,
                'is_default' => true,
            ],
            [
                'code' => 'inactive',
                'group' => 'generic',
                'name' => ['es' => 'Inactivo', 'en' => 'Inactive'],
                'color' => 'zinc',
                'sort' => 2,
                'is_default' => false,
            ],
        ];

        foreach ($rows as $row) {
            Status::query()->updateOrCreate(
                ['code' => $row['code'], 'group' => $row['group']],
                $row,
            );
        }
    }
}
