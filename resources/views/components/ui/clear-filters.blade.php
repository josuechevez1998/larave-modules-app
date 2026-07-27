@props([
    'wireClick' => 'clearFilters',
])

<flux:button
    type="button"
    variant="ghost"
    wire:click="{{ $wireClick }}"
    {{ $attributes }}
>
    {{ $slot->isEmpty() ? __('app.clear_filters') : $slot }}
</flux:button>
