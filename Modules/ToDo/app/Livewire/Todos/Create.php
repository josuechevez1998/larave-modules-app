<?php

namespace Modules\ToDo\Livewire\Todos;

use Modules\ToDo\Livewire\Forms\TodoForm;
use Modules\ToDo\Services\TodoService;
use Modules\ToDo\Models\TodoEstado;
use Illuminate\View\View;
use Livewire\Component;

class Create extends Component
{
    public TodoForm $form;

    /**
     * @return array<int|string, string>
     */
    public function todoEstadoOptions(): array
    {
        return TodoEstado::query()
            ->orderBy('nombre')
            ->pluck('nombre', 'id')
            ->all();
    }

    public function save(TodoService $service)
    {
        $this->form->store($service);

        flash_swal_toast(__('Registro guardado.'), 'success');

        return $this->redirectRoute('todos.index');
    }

    public function render(): View
    {
        return view('todo::livewire.todo.create')
            ->layout('components.layouts.app');
    }
}
