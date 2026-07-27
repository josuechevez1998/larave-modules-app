<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-app-canvas font-sans antialiased dark:bg-app-canvas">
        <div class="flex min-h-svh flex-col items-center justify-center gap-4 bg-background p-4 sm:gap-6 sm:p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-2">
                <a href="{{ url('/') }}" class="mb-2 flex flex-col items-center gap-2 font-medium" wire:navigate>
                    @if (institution_logo_url())
                        <img
                            src="{{ institution_logo_url() }}"
                            alt="{{ institution_name() }}"
                            class="mb-1 size-12 rounded-xl object-contain"
                        >
                    @else
                        <span class="mb-1 flex size-12 items-center justify-center rounded-xl bg-accent text-accent-foreground shadow-lg shadow-teal-900/20">
                            <x-app-logo-icon class="size-6 fill-current text-white" />
                        </span>
                    @endif
                    <span class="ui-display text-base text-stone-900 dark:text-stone-100">{{ institution_name() }}</span>
                    @if (institution_tagline())
                        <span class="text-center text-xs text-stone-500 dark:text-stone-400">{{ institution_tagline() }}</span>
                    @endif
                </a>

                <div class="ui-surface p-6 sm:p-8">
                    {{ $slot }}
                </div>
            </div>
        </div>

        @fluxScripts
    </body>
</html>
