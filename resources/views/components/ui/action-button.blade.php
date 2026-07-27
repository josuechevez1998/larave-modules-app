@props([
    'target' => 'save',
    'label' => null,
    'variant' => 'primary',
    'type' => 'submit',
])

{{--
  Loading ad hoc del panel: una sola etiqueta + spinner nativo de Flux.
  (No usar spans wire:loading/remove dentro de flux:button: se ven ambos textos.)
--}}
<flux:button
    :variant="$variant"
    :type="$type"
    wire:loading.attr="disabled"
    wire:target="{{ $target }}"
    {{ $attributes }}
>
    {{ $label ?? __('app.save') }}
</flux:button>
