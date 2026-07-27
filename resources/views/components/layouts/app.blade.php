@props([
    'title' => null,
])

<x-layouts.app.sidebar :title="$title">
    <x-ui.livewire-busy />

    <flux:main>
        <x-breadcrumbs.trail class="mb-4" />

        @isset($header)
            <div class="mb-6">
                {{ $header }}
            </div>
        @endisset

        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
