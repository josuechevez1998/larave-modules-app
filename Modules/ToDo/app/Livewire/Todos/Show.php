<?php

namespace Modules\ToDo\Livewire\Todos;

use Modules\ToDo\Livewire\Forms\TodoForm;
use Modules\ToDo\Models\Todo;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    public TodoForm $form;

    public function mount(Todo $todo): void
    {
        $this->form->setTodoModel($todo);
    }

    public function render(): View
    {
        return view('todo::livewire.todo.show', ['todo' => $this->form->todoModel])
            ->layout('components.layouts.app');
    }
}
