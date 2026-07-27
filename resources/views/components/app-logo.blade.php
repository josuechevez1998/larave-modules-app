@props([
    'sidebar' => true,
])

@php
    $name = institution_name();
    $logoUrl = institution_logo_url();
@endphp

<div {{ $attributes->class('flex items-center gap-2') }}>
    @if ($logoUrl)
        <img
            src="{{ $logoUrl }}"
            alt="{{ $name }}"
            class="size-8 rounded-md object-contain"
        >
    @else
        <div class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent text-accent-foreground">
            <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
        </div>
    @endif
    @if ($sidebar)
        <div class="grid flex-1 text-start text-sm leading-tight">
            <span class="truncate font-semibold">{{ $name }}</span>
        </div>
    @endif
</div>
