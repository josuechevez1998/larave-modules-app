<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? __('errors.forbidden') }} — {{ config('app.name') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-zinc-100 font-sans text-zinc-800 antialiased dark:bg-zinc-900 dark:text-zinc-100">
        <main class="mx-auto flex min-h-screen max-w-lg flex-col items-center justify-center gap-4 px-6 text-center">
            <p class="text-sm font-medium uppercase tracking-wide text-zinc-500">403</p>
            <h1 class="text-2xl font-semibold">{{ __('errors.forbidden') }}</h1>
            <p class="text-zinc-600 dark:text-zinc-400">{{ $message ?? __('errors.forbidden') }}</p>
            <a href="{{ url('/') }}" class="text-sm font-medium text-zinc-900 underline dark:text-zinc-100">{{ __('Home') }}</a>
        </main>
    </body>
</html>
