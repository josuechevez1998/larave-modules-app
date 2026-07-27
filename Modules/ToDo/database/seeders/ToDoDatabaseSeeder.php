<?php

namespace Modules\ToDo\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\ToDo\Models\TodoEstado;

class ToDoDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Pendiente', 'En progreso', 'Completado', 'Cancelado'] as $nombre) {
            TodoEstado::query()->firstOrCreate(
                ['nombre' => $nombre],
                ['estado' => true]
            );
        }
    }
}
