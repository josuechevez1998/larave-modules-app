<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('app.institution_identity')" :subheading="__('app.institution_subheading')">
        <form wire:submit="save" class="space-y-6" enctype="multipart/form-data">
            @if (session('status'))
                <flux:callout variant="success">{{ session('status') }}</flux:callout>
            @endif

            <flux:input wire:model="name" :label="__('app.institution_name')" type="text" required />
            <flux:input wire:model="tagline" :label="__('app.tagline')" type="text" />

            <div class="space-y-3">
                <flux:heading size="sm">{{ __('app.logo') }}</flux:heading>

                @if ($currentLogoUrl)
                    <div class="flex items-center gap-4">
                        <img
                            src="{{ $currentLogoUrl }}"
                            alt="{{ __('app.logo') }}"
                            class="h-16 w-16 rounded-xl border border-stone-200 bg-white object-contain p-1 dark:border-stone-700"
                        >
                        <flux:button
                            type="button"
                            variant="danger"
                            size="sm"
                            wire:click="removeLogo"
                            wire:confirm="{{ __('app.logo_remove_confirm') }}"
                        >
                            {{ __('app.remove_logo') }}
                        </flux:button>
                    </div>
                @endif

                <input
                    type="file"
                    wire:model="logo"
                    accept="image/*"
                    class="block w-full text-sm text-stone-600 file:me-3 file:rounded-lg file:border-0 file:bg-stone-100 file:px-3 file:py-2 file:text-sm file:font-medium dark:text-stone-300 dark:file:bg-stone-800"
                />
                @error('logo')
                    <flux:text class="text-sm text-red-600 dark:text-red-400">{{ $message }}</flux:text>
                @enderror
                <flux:text class="text-sm text-stone-500">{{ __('app.logo_hint') }}</flux:text>
            </div>

            <flux:input wire:model="support_email" :label="__('app.support_email')" type="email" />
            <flux:input wire:model="phone" :label="__('app.phone')" type="text" />
            <flux:input wire:model="mobile" :label="__('app.mobile')" type="text" />

            <div class="space-y-3">
                <div class="flex items-center justify-between gap-3">
                    <flux:heading size="sm">{{ __('app.social_networks') }}</flux:heading>
                    <flux:button type="button" size="sm" variant="ghost" wire:click="addSocialLink">
                        {{ __('app.add_network') }}
                    </flux:button>
                </div>

                <flux:text class="text-sm text-stone-500">{{ __('app.social_https_hint') }}</flux:text>

                @forelse ($social_links as $index => $link)
                    <div wire:key="social-link-{{ $index }}" class="grid gap-3 rounded-xl border border-stone-200 p-3 dark:border-stone-700 sm:grid-cols-[1fr_1.4fr_auto]">
                        <flux:input wire:model="social_links.{{ $index }}.name" :label="__('app.network_name')" placeholder="Instagram" />
                        <flux:input wire:model="social_links.{{ $index }}.url" :label="__('URL')" placeholder="https://…" />
                        <div class="flex items-end">
                            <flux:button type="button" variant="danger" size="sm" wire:click="removeSocialLink({{ $index }})">
                                {{ __('app.remove') }}
                            </flux:button>
                        </div>
                    </div>
                @empty
                    <flux:text class="text-sm text-stone-500">{{ __('app.no_social_networks') }}</flux:text>
                @endforelse
            </div>

            <div class="flex items-center gap-3">
                <flux:button type="submit" variant="primary">{{ __('app.save') }}</flux:button>
            </div>
        </form>
    </x-settings.layout>
</section>
