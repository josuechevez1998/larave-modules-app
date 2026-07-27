<?php

namespace Modules\ToDo\Livewire\TodoEstados;

use Illuminate\View\View;
use Livewire\Component;
use Modules\ToDo\Livewire\Forms\TodoEstadoForm;
use Modules\ToDo\Services\TodoEstadoService;

class Create extends Component
{
    public TodoEstadoForm $form;

    public function save(TodoEstadoService $service)
    {
        $this->form->store($service);

        flash_swal_toast(__('Registro guardado.'), 'success');

        return $this->redirectRoute('todo-estados.index');
    }

    public function render(): View
    {
        return view('todo::livewire.todo-estado.create')
            ->layout('components.layouts.app');
    }
}
