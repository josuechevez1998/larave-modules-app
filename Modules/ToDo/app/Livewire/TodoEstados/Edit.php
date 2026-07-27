<?php

namespace Modules\ToDo\Livewire\TodoEstados;

use Illuminate\View\View;
use Livewire\Component;
use Modules\ToDo\Livewire\Forms\TodoEstadoForm;
use Modules\ToDo\Models\TodoEstado;
use Modules\ToDo\Services\TodoEstadoService;

class Edit extends Component
{
    public TodoEstadoForm $form;

    public function mount(TodoEstado $todoEstado): void
    {
        $this->form->setTodoEstadoModel($todoEstado);
    }

    public function save(TodoEstadoService $service)
    {
        $this->form->update($service);

        flash_swal_toast(__('Registro actualizado.'), 'success');

        return $this->redirectRoute('todo-estados.index');
    }

    public function render(): View
    {
        return view('todo::livewire.todo-estado.edit')
            ->layout('components.layouts.app');
    }
}
