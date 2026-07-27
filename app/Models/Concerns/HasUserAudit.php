<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin Model
 */
trait HasUserAudit
{
    public static function bootHasUserAudit(): void
    {
        static::creating(function (Model $model): void {
            if (! auth()->check()) {
                return;
            }

            $userId = auth()->id();

            if (static::modelHasColumn($model, 'creado_por') && blank($model->getAttribute('creado_por'))) {
                $model->setAttribute('creado_por', $userId);
            }

            if (static::modelHasColumn($model, 'actualizado_por') && blank($model->getAttribute('actualizado_por'))) {
                $model->setAttribute('actualizado_por', $userId);
            }
        });

        static::updating(function (Model $model): void {
            if (! auth()->check()) {
                return;
            }

            if (static::modelHasColumn($model, 'actualizado_por')) {
                $model->setAttribute('actualizado_por', auth()->id());
            }
        });
    }

    public function creadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function actualizadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actualizado_por');
    }

    protected static function modelHasColumn(Model $model, string $column): bool
    {
        return $model->getConnection()
            ->getSchemaBuilder()
            ->hasColumn($model->getTable(), $column);
    }
}
