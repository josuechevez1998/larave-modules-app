<?php

use App\Models\InstitutionSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

if (! function_exists('friendly_error_response')) {
    /**
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    function friendly_error_response(Request $request, string $message, string $code, int $status)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => $message,
                'code' => $code,
            ], $status);
        }

        if ($request->hasSession()) {
            $request->session()->flash('error', $message);
        }

        $view = match ($status) {
            403 => 'errors.403',
            404 => 'errors.404',
            default => 'errors.500',
        };

        return response()->view($view, ['message' => $message], $status);
    }
}

if (! function_exists('brand_logo_url_from_config')) {
    function brand_logo_url_from_config(): ?string
    {
        $absolute = config('brand.logo_url');

        if (filled($absolute)) {
            return (string) $absolute;
        }

        $path = config('brand.logo_path');

        if (! filled($path)) {
            return null;
        }

        return asset((string) $path);
    }
}

if (! function_exists('institution_setting')) {
    function institution_setting(): ?InstitutionSetting
    {
        try {
            if (! Schema::hasTable('institution_settings')) {
                return null;
            }

            return InstitutionSetting::findCurrent();
        } catch (\Throwable) {
            return null;
        }
    }
}

if (! function_exists('institution_name')) {
    function institution_name(): string
    {
        $settings = institution_setting();

        if ($settings) {
            return $settings->resolvedName();
        }

        return (string) config('brand.name', config('app.name'));
    }
}

if (! function_exists('institution_logo_url')) {
    function institution_logo_url(): ?string
    {
        $settings = institution_setting();

        if ($settings) {
            return $settings->resolvedLogoUrl();
        }

        return brand_logo_url_from_config();
    }
}

if (! function_exists('institution_tagline')) {
    function institution_tagline(): ?string
    {
        $settings = institution_setting();

        if ($settings) {
            return $settings->resolvedTagline();
        }

        $fallback = config('brand.tagline');

        return filled($fallback) ? (string) $fallback : null;
    }
}
