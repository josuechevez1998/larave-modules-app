<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    protected $fillable = [
        'code',
        'group',
        'name',
        'color',
        'sort',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'name' => 'array',
            'is_default' => 'boolean',
            'sort' => 'integer',
        ];
    }

    public function label(?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $names = $this->name ?? [];

        return $names[$locale] ?? $names['en'] ?? $names['es'] ?? $this->code;
    }

    public function scopeGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
