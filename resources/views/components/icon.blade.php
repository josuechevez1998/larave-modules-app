@props([
    'name',
    'class' => 'w-5 h-5',
])

@php
    $icon = \App\Support\LucideIcon::svg($name, $class);
@endphp

{!! $icon !!}
