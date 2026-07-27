<?php

namespace Modules\ToDo\Livewire\TodoEstados;

use App\Services\ListQueryBuilder;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\ToDo\Models\TodoEstado;
use Modules\ToDo\Services\TodoEstadoService;

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
    public string $estado = '';

    public function updated(string $property): void
    {
        if (in_array($property, ['nombre', 'estado', 'sort', 'per_page'], true)) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['nombre', 'estado']);
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->js(livewire_swal_confirm_delete($id));
    }

    public function delete(TodoEstado $todoEstado, TodoEstadoService $service)
    {
        $service->delete($todoEstado);

        flash_swal_toast(__('Registro eliminado.'), 'success');

        return $this->redirectRoute('todo-estados.index');
    }

    public function render(): View
    {
        $request = request()->merge([
            'sort' => $this->sort,
            'per_page' => $this->per_page,
            'nombre' => $this->nombre,
            'estado' => $this->estado,
        ]);

        $todoEstados = ListQueryBuilder::for(
            TodoEstado::query()->with(['actualizadoPor']),
            ['id', 'created_at', 'updated_at', 'nombre', 'estado'],
            ['actualizadoPor']
        )
            ->withColumnFilters(
                textColumns: ['nombre'],
                exactColumns: ['estado'],
            )
            ->apply($request)
            ->paginate($request);

        return view('todo::livewire.todo-estado.index', compact('todoEstados'))
            ->layout('components.layouts.app')
            ->with('i', ($this->getPage() - 1) * $todoEstados->perPage());
    }
}
