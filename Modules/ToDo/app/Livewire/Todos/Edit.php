<?php

namespace Modules\ToDo\Livewire\Todos;

use Modules\ToDo\Livewire\Forms\TodoForm;
use Modules\ToDo\Models\Todo;
use Modules\ToDo\Services\TodoService;
use Modules\ToDo\Models\TodoEstado;
use Illuminate\View\View;
use Livewire\Component;

class Edit extends Component
{
    public TodoForm $form;

    public function mount(Todo $todo): void
    {
        $this->form->setTodoModel($todo);
    }

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
        $this->form->update($service);

        flash_swal_toast(__('Registro actualizado.'), 'success');

        return $this->redirectRoute('todos.index');
    }

    public function render(): View
    {
        return view('todo::livewire.todo.edit')
            ->layout('components.layouts.app');
    }
}
