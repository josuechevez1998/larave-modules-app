<div class="space-y-6">
    <div>
        <flux:input
            wire:model="form.nombre"
            :label="__('Nombre')"
            type="text"
            autocomplete="form.nombre"
            :placeholder="__('Nombre')"
        />
    </div>

    <div>
        <flux:checkbox wire:model="form.estado" :label="__('Activo')" />
    </div>

    <div class="flex flex-wrap items-center gap-3 pt-2">
        <flux:button variant="primary" type="submit">{{ __('app.save') }}</flux:button>
        <flux:button variant="ghost" :href="route('todo-estados.index')" wire:navigate type="button">
            {{ __('Cancelar') }}
        </flux:button>
    </div>
</div>
