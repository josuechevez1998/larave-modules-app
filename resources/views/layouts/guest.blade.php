<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-app-canvas antialiased dark:bg-zinc-800">
        <div class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-2">
                <a href="{{ url('/') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <x-app-logo :sidebar="false" class="mb-1" />
                    <span class="sr-only">{{ institution_name() }}</span>
                </a>

                <div class="flex flex-col gap-6">
                    {{ $slot }}
                </div>
            </div>
        </div>

        @fluxScripts
    </body>
</html>
