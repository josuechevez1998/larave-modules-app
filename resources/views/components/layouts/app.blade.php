@props([
    'title' => null,
])

<x-layouts.app.sidebar :title="$title">
    <flux:main>
        @isset($header)
            <div class="mb-6">
                {{ $header }}
            </div>
        @endisset

        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
