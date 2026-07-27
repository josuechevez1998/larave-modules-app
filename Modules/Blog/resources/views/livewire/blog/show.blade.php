<section class="w-full space-y-6">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1">{{ __('Blog') }}</flux:heading>
            <flux:subheading class="mt-1">{{ __('Detalle del registro') }}</flux:subheading>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <flux:button variant="ghost" :href="route('blogs.edit', $blog->id)" wire:navigate>
                {{ __('Editar') }}
            </flux:button>
            <flux:button variant="ghost" :href="route('blogs.index')" wire:navigate>
                {{ __('Volver') }}
            </flux:button>
        </div>
    </div>

    <flux:separator variant="subtle" />

    <div class="ui-surface p-4 sm:p-8">
        <dl class="divide-y divide-stone-200 dark:divide-stone-700">
            
            <div class="py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-1">
                <dt class="ui-label">Nombre</dt>
                <dd class="mt-1 text-sm text-stone-800 sm:col-span-2 sm:mt-0 dark:text-stone-200">{{ $blog->nombre }}</dd>
            </div>

        </dl>
    </div>
</section>
