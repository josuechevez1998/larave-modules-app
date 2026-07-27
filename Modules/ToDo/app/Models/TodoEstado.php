<?php

namespace Modules\ToDo\Models;

use App\Models\Concerns\HasUserAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TodoEstado extends Model
{
    use HasUserAudit;

    protected $table = 'todo_estado';

    protected $perPage = 20;

    protected $fillable = [
        'nombre',
        'estado',
        'creado_por',
        'actualizado_por',
    ];

    protected function casts(): array
    {
        return [
            'estado' => 'boolean',
        ];
    }

    public function todos(): HasMany
    {
        return $this->hasMany(Todo::class, 'todo_estado_id');
    }
}
