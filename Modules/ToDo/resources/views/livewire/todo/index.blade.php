<section class="w-full space-y-6">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1">{{ __('ToDos') }}</flux:heading>
            <flux:subheading class="mt-1">{{ __('Listado de :resource', ['resource' => __('ToDos')]) }}</flux:subheading>
        </div>
        <flux:button variant="primary" :href="route('todos.create')" wire:navigate>
            {{ __('Nuevo') }}
        </flux:button>
    </div>

    <flux:separator variant="subtle" />

    <div class="flex flex-wrap items-end gap-4">

<div>
    <flux:input
        wire:model.live.debounce.300ms="nombre"
        :label="__('Nombre')"
        type="text"
        :placeholder="__('Buscar :field…', ['field' => __('Nombre')])"
    />
</div>
<div>
    <flux:input
        wire:model.live.debounce.300ms="descripcion"
        :label="__('Descripcion')"
        type="text"
        :placeholder="__('Buscar :field…', ['field' => __('Descripcion')])"
    />
</div>
<div>
    <flux:input
        wire:model.live.debounce.300ms="fecha_inicio"
        :label="__('Fecha Inicio')"
        type="text"
        :placeholder="__('Buscar :field…', ['field' => __('Fecha Inicio')])"
    />
</div>
<div>
    <flux:input
        wire:model.live.debounce.300ms="fecha_fin"
        :label="__('Fecha Fin')"
        type="text"
        :placeholder="__('Buscar :field…', ['field' => __('Fecha Fin')])"
    />
</div>
<div>
    <flux:select wire:model.live="todo_estado_id" :label="__('Estado')">
        <flux:select.option value="">{{ __('Todos') }}</flux:select.option>
        @foreach ($this->todoEstadoOptions() as $value => $label)
            <flux:select.option :value="$value">{{ $label }}</flux:select.option>
        @endforeach
    </flux:select>
</div>

        <div class="pb-1">
            <x-ui.clear-filters />
        </div>
    </div>

    <div
        class="ui-table ui-table-busy"
        wire:loading.delay.200ms.attr="data-loading"
    >
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-stone-200 dark:divide-stone-700">
                <thead class="ui-thead">
                    <tr>
                        <th scope="col" class="ui-th pl-4">#</th>
                        
         <th scope="col" class="ui-th">Nombre</th>
         <th scope="col" class="ui-th">Descripción</th>
         <th scope="col" class="ui-th">Fecha inicio</th>
         <th scope="col" class="ui-th">Fecha fin</th>
         <th scope="col" class="ui-th">Estado</th>
         <th scope="col" class="ui-th">Última actualización</th>
         <th scope="col" class="ui-th">Actualizado por</th>

                        <th scope="col" class="ui-th text-right pr-4">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200 bg-transparent dark:divide-stone-800">
                    @forelse ($todos as $todo)
                        <tr class="ui-tr" wire:key="{{ $todo->id }}">
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-stone-900 dark:text-stone-100">{{ ++$i }}</td>
                            
          <td class="whitespace-nowrap px-3 py-4 text-sm text-stone-600 dark:text-stone-300">{{ $todo->nombre }}</td>
          <td class="max-w-xs truncate px-3 py-4 text-sm text-stone-600 dark:text-stone-300">{{ $todo->descripcion }}</td>
          <td class="whitespace-nowrap px-3 py-4 text-sm text-stone-600 dark:text-stone-300">{{ $todo->fecha_inicio?->format('d/m/Y') }}</td>
          <td class="whitespace-nowrap px-3 py-4 text-sm text-stone-600 dark:text-stone-300">{{ $todo->fecha_fin?->format('d/m/Y') }}</td>
          <td class="whitespace-nowrap px-3 py-4 text-sm text-stone-600 dark:text-stone-300">{{ $todo->todoEstado?->nombre ?? '—' }}</td>
          <td class="whitespace-nowrap px-3 py-4 text-sm text-stone-600 dark:text-stone-300">{{ $todo->updated_at?->format('d/m/Y H:i') }}</td>
          <td class="whitespace-nowrap px-3 py-4 text-sm text-stone-600 dark:text-stone-300">{{ $todo->actualizadoPor?->name ?? '—' }}</td>

                            <td class="whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm">
                                <div class="inline-flex flex-wrap items-center justify-end gap-2">
                                    <flux:button size="sm" variant="ghost" :href="route('todos.show', $todo->id)" wire:navigate>
                                        {{ __('Ver') }}
                                    </flux:button>
                                    <flux:button size="sm" variant="ghost" :href="route('todos.edit', $todo->id)" wire:navigate>
                                        {{ __('Editar') }}
                                    </flux:button>
                                    <flux:button
                                        size="sm"
                                        variant="danger"
                                        type="button"
                                        wire:click="confirmDelete({{ $todo->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="confirmDelete, delete"
                                    >
                                        {{ __('Eliminar') }}
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="99" class="px-4 py-10 text-center text-sm text-stone-500 dark:text-stone-400">
                                {{ __('No hay registros.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($todos->hasPages())
            <div class="border-t border-stone-200 px-4 py-3 dark:border-stone-700">
                {!! $todos->withQueryString()->links() !!}
            </div>
        @endif
    </div>
</section>
