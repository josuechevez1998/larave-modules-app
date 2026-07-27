@props([
    'title',
    'description' => null,
])

<div class="mb-2 flex w-full flex-col text-center">
    <flux:heading size="xl" level="1">{{ $title }}</flux:heading>
    @if ($description)
        <flux:subheading class="mt-1">{{ $description }}</flux:subheading>
    @endif
</div>
