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
            401 => 'errors.401',
            403 => 'errors.403',
            404 => 'errors.404',
            419 => 'errors.419',
            429 => 'errors.429',
            503 => 'errors.503',
            default => 'errors.500',
        };

        return response()->view($view, [
            'message' => $message,
            'code' => $code,
        ], $status);
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

if (! function_exists('swal_toast_icon_html')) {
    /**
     * SVG limpio (estilo Lucide) para toasts — evita el icono animado roto de SweetAlert2.
     */
    function swal_toast_icon_html(string $icon = 'success'): string
    {
        $svg = match ($icon) {
            'info' => <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
SVG,
            'warning' => <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
SVG,
            'error' => <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
SVG,
            default => <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
SVG,
        };

        return '<span class="swal-toast-glyph swal-toast-glyph-'.e($icon).'">'.$svg.'</span>';
    }
}

if (! function_exists('flash_swal_toast')) {
    /**
     * Toast vía sesión (sobrevive al redirect). Preferir esto tras create/update/delete.
     * success / info / warning: 12s. Icono SVG (no animación nativa de Swal).
     */
    function flash_swal_toast(string $message, string $icon = 'success'): void
    {
        $timer = in_array($icon, ['success', 'info', 'warning'], true)
            ? 12_000
            : (int) config('sweetalert.timer', 12_000);

        alert()
            ->toast($message, $icon)
            ->iconHtml(swal_toast_icon_html($icon))
            ->autoClose($timer)
            ->timerProgressBar();
    }
}

if (! function_exists('livewire_swal_toast')) {
    /**
     * JS snippet for SweetAlert2 toast from Livewire ($this->js(...)).
     * Requires sweetalert JS loaded (alwaysLoadJS / layout include).
     *
     * success / info / warning: 12s. Other icons: timer scales with message length.
     */
    function livewire_swal_toast(string $message, string $icon = 'success'): string
    {
        $customClass = array_filter((array) config('sweetalert.customClass', []), fn ($v) => filled($v));

        if (in_array($icon, ['success', 'info', 'warning'], true)) {
            $timer = 12_000;
        } else {
            $baseMs = (int) config('sweetalert.timer', 12_000);
            $perCharMs = 55;
            $minMs = max(3500, (int) ($baseMs * 0.7));
            $maxMs = 14_000;
            $timer = (int) min($maxMs, max($minMs, $baseMs + (mb_strlen($message) * $perCharMs)));
        }

        $payload = [
            'toast' => true,
            'position' => (string) config('sweetalert.toast_position', 'top-end'),
            'icon' => $icon,
            'iconHtml' => swal_toast_icon_html($icon),
            'title' => $message,
            'showConfirmButton' => false,
            'timer' => $timer,
            'timerProgressBar' => (bool) config('sweetalert.timer_progress_bar', true),
            'background' => (string) config('sweetalert.background', 'var(--color-app-surface, #faf9f7)'),
        ];

        if ($customClass !== []) {
            $payload['customClass'] = $customClass;
        }

        return 'typeof Swal!=="undefined"&&Swal.fire('.json_encode($payload, JSON_UNESCAPED_UNICODE).')';
    }
}

if (! function_exists('livewire_swal_confirm_delete')) {
    /**
     * JS snippet: SweetAlert2 confirm, then $wire.delete(id).
     * Use from Livewire: $this->js(livewire_swal_confirm_delete($id));
     */
    function livewire_swal_confirm_delete(
        int|string $id,
        ?string $title = null,
        ?string $text = null,
    ): string {
        $customClass = array_filter((array) config('sweetalert.customClass', []), fn ($v) => filled($v));
        $customClass['confirmButton'] = trim(($customClass['confirmButton'] ?? 'swal-app-confirm').' swal-app-confirm-danger');

        $payload = [
            'title' => $title ?? __('¿Eliminar registro?'),
            'text' => $text ?? __('Esta acción no se puede deshacer.'),
            'icon' => (string) config('sweetalert.confirm_delete_icon', 'warning'),
            'showCancelButton' => (bool) config('sweetalert.confirm_delete_show_cancel_button', true),
            'showCloseButton' => (bool) config('sweetalert.confirm_delete_show_close_button', false),
            'confirmButtonText' => __(config('sweetalert.confirm_delete_confirm_button_text', 'Sí, eliminar')),
            'cancelButtonText' => __(config('sweetalert.confirm_delete_cancel_button_text', 'Cancelar')),
            'confirmButtonColor' => config('sweetalert.confirm_delete_confirm_button_color', '#991b1b'),
            'cancelButtonColor' => config('sweetalert.confirm_delete_cancel_button_color', '#78716c'),
            'showLoaderOnConfirm' => (bool) config('sweetalert.confirm_delete_show_loader_on_confirm', true),
            'reverseButtons' => true,
            'focusCancel' => true,
            'background' => (string) config('sweetalert.background', 'var(--color-app-surface, #faf9f7)'),
            'customClass' => $customClass,
        ];

        return 'typeof Swal!=="undefined"&&Swal.fire('
            .json_encode($payload, JSON_UNESCAPED_UNICODE)
            .').then(function(r){if(r.isConfirmed){$wire.delete('.json_encode($id).')}})';
    }
}
