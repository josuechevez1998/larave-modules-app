<?php

namespace Modules\ToDo\Models;

use App\Models\Concerns\HasUserAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Todo extends Model
{
    use HasUserAudit;

    protected $perPage = 20;

    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'todo_estado_id',
        'creado_por',
        'actualizado_por',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
        ];
    }

    public function todoEstado(): BelongsTo
    {
        return $this->belongsTo(TodoEstado::class, 'todo_estado_id');
    }
}
