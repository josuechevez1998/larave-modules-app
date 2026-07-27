{{-- Kept for Livewire component_layout layouts::app --}}
<x-layouts.app :title="$title ?? null">
    @isset($header)
        <x-slot:header>{{ $header }}</x-slot:header>
    @endisset

    {{ $slot }}
</x-layouts.app>
