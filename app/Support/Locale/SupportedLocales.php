<?php

namespace App\Support\Locale;

final class SupportedLocales
{
    public const DEFAULT = 'es';

    /**
     * @return list<string>
     */
    public static function all(): array
    {
        return ['es', 'en'];
    }

    public static function isValid(?string $locale): bool
    {
        return is_string($locale) && in_array($locale, self::all(), true);
    }
}
