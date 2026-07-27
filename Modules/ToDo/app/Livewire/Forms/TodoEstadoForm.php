<?php

namespace Modules\ToDo\Livewire\Forms;

use Livewire\Form;
use Modules\ToDo\Models\TodoEstado;
use Modules\ToDo\Services\TodoEstadoService;

class TodoEstadoForm extends Form
{
    public ?TodoEstado $todoEstadoModel = null;

    public $nombre = '';

    public $estado = true;

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'estado' => 'required|boolean',
        ];
    }

    public function setTodoEstadoModel(TodoEstado $todoEstadoModel): void
    {
        $this->todoEstadoModel = $todoEstadoModel;
        $this->nombre = $this->todoEstadoModel->nombre;
        $this->estado = (bool) $this->todoEstadoModel->estado;
    }

    public function store(TodoEstadoService $service): void
    {
        $service->create($this->validate());

        $this->reset();
    }

    public function update(TodoEstadoService $service): void
    {
        $service->update($this->todoEstadoModel, $this->validate());

        $this->reset();
    }
}
