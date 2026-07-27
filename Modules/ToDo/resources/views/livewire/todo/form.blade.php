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
        <flux:textarea
            wire:model="form.descripcion"
            :label="__('Descripción')"
            rows="4"
            :placeholder="__('Descripción')"
        />
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <flux:input
                wire:model="form.fecha_inicio"
                :label="__('Fecha inicio')"
                type="date"
            />
        </div>
        <div>
            <flux:input
                wire:model="form.fecha_fin"
                :label="__('Fecha fin')"
                type="date"
            />
        </div>
    </div>

    <div>
        <flux:select wire:model="form.todo_estado_id" :label="__('Estado')">
            <flux:select.option value="">{{ __('Seleccionar…') }}</flux:select.option>
            @foreach ($this->todoEstadoOptions() as $value => $label)
                <flux:select.option :value="$value">{{ $label }}</flux:select.option>
            @endforeach
        </flux:select>
    </div>

    <div class="flex flex-wrap items-center gap-3 pt-2">
        <flux:button variant="primary" type="submit">{{ __('app.save') }}</flux:button>
        <flux:button variant="ghost" :href="route('todos.index')" wire:navigate type="button">
            {{ __('Cancelar') }}
        </flux:button>
    </div>
</div>
