<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('app.password')" :subheading="__('app.password_subheading')">
        <form wire:submit="updatePassword" class="mt-6 space-y-6">
            <flux:input
                wire:model="current_password"
                :label="__('Current Password')"
                type="password"
                required
                autocomplete="current-password"
            />
            <flux:input
                wire:model="password"
                :label="__('New Password')"
                type="password"
                required
                autocomplete="new-password"
            />
            <flux:input
                wire:model="password_confirmation"
                :label="__('Confirm Password')"
                type="password"
                required
                autocomplete="new-password"
            />

            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit">{{ __('app.save') }}</flux:button>

                <x-action-message class="me-3" on="password-updated">
                    {{ __('app.saved') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>
