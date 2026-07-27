<?php

namespace Modules\ToDo\Livewire\TodoEstados;

use Modules\ToDo\Livewire\Forms\TodoEstadoForm;
use Modules\ToDo\Models\TodoEstado;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    public TodoEstadoForm $form;

    public function mount(TodoEstado $todoEstado): void
    {
        $this->form->setTodoEstadoModel($todoEstado);
    }

    public function render(): View
    {
        return view('todo::livewire.todo-estado.show', ['todoEstado' => $this->form->todoEstadoModel])
            ->layout('components.layouts.app');
    }
}
