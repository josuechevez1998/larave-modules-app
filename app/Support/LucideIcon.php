<?php

namespace App\Support;

final class LucideIcon
{
    /**
     * Render a Lucide icon as inline SVG by name (kebab-case).
     * Icons are loaded from node_modules/lucide/dist/esm/icons when available,
     * otherwise a minimal fallback span is returned.
     */
    public static function svg(string $name, string $class = 'w-5 h-5'): string
    {
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9\-]/', '', str_replace('_', '-', $name)) ?? $name);
        $path = base_path("node_modules/lucide-static/icons/{$slug}.svg");

        if (! is_file($path)) {
            $path = base_path("node_modules/lucide/dist/esm/icons/{$slug}.js");
            if (! is_file($path)) {
                return '<span class="'.e($class).'" aria-hidden="true" data-icon="'.e($slug).'"></span>';
            }

            return '<span class="'.e($class).'" aria-hidden="true" data-lucide="'.e($slug).'"></span>';
        }

        $svg = file_get_contents($path) ?: '';
        $svg = preg_replace('/<svg\b/', '<svg class="'.e($class).'"', $svg, 1) ?? $svg;

        return $svg;
    }
}
