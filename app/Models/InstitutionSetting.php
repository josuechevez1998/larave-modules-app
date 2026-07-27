<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class InstitutionSetting extends Model
{
    protected $fillable = [
        'name',
        'logo_path',
        'tagline',
        'support_email',
        'phone',
        'mobile',
        'social_links',
    ];

    protected function casts(): array
    {
        return [
            'social_links' => 'array',
        ];
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate([]);
    }

    public static function findCurrent(): ?self
    {
        return static::query()->first();
    }

    public function resolvedName(): string
    {
        return filled($this->name)
            ? (string) $this->name
            : (string) config('brand.name', config('app.name'));
    }

    public function resolvedTagline(): ?string
    {
        if (filled($this->tagline)) {
            return (string) $this->tagline;
        }

        $fallback = config('brand.tagline');

        return filled($fallback) ? (string) $fallback : null;
    }

    public function resolvedSupportEmail(): ?string
    {
        if (filled($this->support_email)) {
            return (string) $this->support_email;
        }

        $fallback = config('brand.support_email');

        return filled($fallback) ? (string) $fallback : null;
    }

    public function logoUrl(): ?string
    {
        if (! filled($this->logo_path)) {
            return null;
        }

        return Storage::disk('public')->url($this->logo_path);
    }

    public function resolvedLogoUrl(): ?string
    {
        return $this->logoUrl() ?? brand_logo_url_from_config();
    }

    /**
     * @return list<array{name: string, url: string}>
     */
    public function normalizedSocialLinks(): array
    {
        $links = $this->social_links ?? [];

        if (! is_array($links)) {
            return [];
        }

        return collect($links)
            ->filter(fn ($link) => is_array($link) && filled($link['name'] ?? null) && filled($link['url'] ?? null))
            ->map(fn (array $link) => [
                'name' => (string) $link['name'],
                'url' => (string) $link['url'],
            ])
            ->values()
            ->all();
    }

    public function hasContactInfo(): bool
    {
        return filled($this->support_email)
            || filled($this->phone)
            || filled($this->mobile)
            || $this->normalizedSocialLinks() !== [];
    }
}
