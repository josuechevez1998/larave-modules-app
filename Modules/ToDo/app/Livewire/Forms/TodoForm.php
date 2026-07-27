<?php

namespace Modules\ToDo\Livewire\Forms;

use Livewire\Form;
use Modules\ToDo\Models\Todo;
use Modules\ToDo\Services\TodoService;

class TodoForm extends Form
{
    public ?Todo $todoModel = null;

    public $nombre = '';

    public $descripcion = '';

    public $fecha_inicio = '';

    public $fecha_fin = '';

    public $todo_estado_id = null;

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'todo_estado_id' => 'required|integer|exists:todo_estado,id',
        ];
    }

    public function setTodoModel(Todo $todoModel): void
    {
        $this->todoModel = $todoModel;
        $this->nombre = $this->todoModel->nombre;
        $this->descripcion = $this->todoModel->descripcion ?? '';
        $this->fecha_inicio = $this->todoModel->fecha_inicio?->format('Y-m-d') ?? '';
        $this->fecha_fin = $this->todoModel->fecha_fin?->format('Y-m-d') ?? '';
        $this->todo_estado_id = $this->todoModel->todo_estado_id;
    }

    public function store(TodoService $service): void
    {
        $service->create($this->validate());

        $this->reset();
    }

    public function update(TodoService $service): void
    {
        $service->update($this->todoModel, $this->validate());

        $this->reset();
    }
}
