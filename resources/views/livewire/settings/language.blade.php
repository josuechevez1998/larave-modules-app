<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('app.locale')" :subheading="__('app.language_subheading')">
        <form wire:submit="updateLanguage" class="space-y-6">
            <flux:radio.group wire:model="locale" variant="segmented">
                <flux:radio value="es">{{ __('app.locale_es') }}</flux:radio>
                <flux:radio value="en">{{ __('app.locale_en') }}</flux:radio>
            </flux:radio.group>

            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit">{{ __('app.save') }}</flux:button>
            </div>
        </form>
    </x-settings.layout>
</section>
