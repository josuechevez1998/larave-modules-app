<?php

namespace Modules\ToDo\Livewire\Todos;

use App\Services\ListQueryBuilder;
use Modules\ToDo\Services\TodoService;
use Modules\ToDo\Models\Todo;
use Modules\ToDo\Models\TodoEstado;

use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $sort = '-id';

    #[Url]
    public int $per_page = 15;


    #[Url]
    public string $nombre = '';

    #[Url]
    public string $descripcion = '';

    #[Url]
    public string $fecha_inicio = '';

    #[Url]
    public string $fecha_fin = '';

    #[Url]
    public string $todo_estado_id = '';

    public function updated(string $property): void
    {
        if (in_array($property, ['nombre', 'descripcion', 'fecha_inicio', 'fecha_fin', 'todo_estado_id', 'sort', 'per_page'], true)) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['nombre', 'descripcion', 'fecha_inicio', 'fecha_fin', 'todo_estado_id']);
        $this->resetPage();
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

    public function confirmDelete(int $id): void
    {
        $this->js(livewire_swal_confirm_delete($id));
    }

    public function delete(Todo $todo, TodoService $service)
    {
        $service->delete($todo);

        flash_swal_toast(__('Registro eliminado.'), 'success');

        return $this->redirectRoute('todos.index');
    }

    public function render(): View
    {
        $request = request()->merge([
            'sort' => $this->sort,
            'per_page' => $this->per_page,

            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'todo_estado_id' => $this->todo_estado_id,
        ]);

        $todos = ListQueryBuilder::for(
            Todo::query()->with(['todoEstado', 'actualizadoPor']),
            ['id', 'created_at', 'updated_at', 'nombre', 'descripcion', 'fecha_inicio', 'fecha_fin'],
            ['todoEstado', 'actualizadoPor']
        )
            ->withColumnFilters(
                textColumns: ['nombre', 'descripcion', 'fecha_inicio', 'fecha_fin'],
                exactColumns: ['todo_estado_id'],
            )
            ->apply($request)
            ->paginate($request);

        return view('todo::livewire.todo.index', compact('todos'))
            ->layout('components.layouts.app')
            ->with('i', ($this->getPage() - 1) * $todos->perPage());
    }
}
