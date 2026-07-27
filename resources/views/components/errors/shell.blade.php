@props([
    'code',
    'title',
    'message' => null,
])

@php
    $brandName = institution_name();
    $logoUrl = institution_logo_url();
    $detail = $message ?: $title;
    $pageTitle = $code.' — '.$title.' — '.$brandName;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head', ['title' => $pageTitle])
    </head>
    <body class="min-h-screen bg-app-canvas font-sans text-stone-800 antialiased dark:bg-app-canvas dark:text-stone-100">
        <div class="flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <a href="{{ url('/') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                @if ($logoUrl)
                    <img
                        src="{{ $logoUrl }}"
                        alt="{{ $brandName }}"
                        class="size-12 rounded-xl object-contain"
                    >
                @else
                    <span class="flex size-12 items-center justify-center rounded-xl bg-accent text-accent-foreground shadow-lg shadow-teal-900/20">
                        <x-app-logo-icon class="size-6 fill-current text-white" />
                    </span>
                @endif
                <span class="ui-display text-base text-stone-900 dark:text-stone-100">{{ $brandName }}</span>
            </a>

            <div class="ui-surface w-full max-w-md px-6 py-8 text-center sm:px-8">
                <p class="ui-label text-accent">{{ __('errors.http_error') }} {{ $code }}</p>

                <p class="ui-display mt-4 text-5xl font-normal tracking-tight text-stone-950 dark:text-stone-50">
                    {{ $code }}
                </p>

                <h1 class="mt-4 text-xl font-semibold text-stone-900 dark:text-stone-100">
                    {{ $title }}
                </h1>

                <p class="mt-3 text-sm leading-relaxed text-stone-600 dark:text-stone-400">
                    {{ $detail }}
                </p>

                <div class="mt-8 flex flex-col items-stretch gap-3 sm:flex-row sm:justify-center">
                    <a
                        href="{{ url('/') }}"
                        class="ui-btn-primary"
                        wire:navigate
                    >
                        {{ __('errors.go_home') }}
                    </a>

                    @auth
                        <a
                            href="{{ route('dashboard') }}"
                            class="ui-btn"
                            wire:navigate
                        >
                            {{ __('errors.go_dashboard') }}
                        </a>
                    @else
                        <button
                            type="button"
                            class="ui-btn"
                            onclick="history.length > 1 ? history.back() : (window.location.href = '{{ url('/') }}')"
                        >
                            {{ __('errors.go_back') }}
                        </button>
                    @endauth
                </div>
            </div>
        </div>

        @fluxScripts
    </body>
</html>
