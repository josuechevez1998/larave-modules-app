<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <flux:navlist>
            <flux:navlist.item :href="route('settings.profile')" :current="request()->routeIs('settings.profile')" wire:navigate>
                {{ __('app.profile') }}
            </flux:navlist.item>
            <flux:navlist.item :href="route('settings.password')" :current="request()->routeIs('settings.password')" wire:navigate>
                {{ __('app.password') }}
            </flux:navlist.item>
            <flux:navlist.item :href="route('settings.appearance')" :current="request()->routeIs('settings.appearance')" wire:navigate>
                {{ __('app.appearance') }}
            </flux:navlist.item>
            <flux:navlist.item :href="route('settings.language')" :current="request()->routeIs('settings.language')" wire:navigate>
                {{ __('app.locale') }}
            </flux:navlist.item>
            @can('settings.institution')
                <flux:navlist.item :href="route('settings.institution')" :current="request()->routeIs('settings.institution')" wire:navigate>
                    {{ __('app.institution_identity') }}
                </flux:navlist.item>
            @endcan
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
