<?php

namespace Modules\Blog\Livewire\Blogs;

use App\Services\ListQueryBuilder;
use Modules\Blog\Services\BlogService;
use Modules\Blog\Models\Blog;

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

    public function updated(string $property): void
    {
        if (in_array($property, ['nombre', 'sort', 'per_page'], true)) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['nombre']);
        $this->resetPage();
    }


    public function confirmDelete(int $id): void
    {
        $this->js(livewire_swal_confirm_delete($id));
    }

    public function delete(Blog $blog, BlogService $service)
    {
        $service->delete($blog);

        flash_swal_toast(__('Registro eliminado.'), 'success');

        return $this->redirectRoute('blogs.index');
    }

    public function render(): View
    {
        $request = request()->merge([
            'sort' => $this->sort,
            'per_page' => $this->per_page,

            'nombre' => $this->nombre,
        ]);

        $blogs = ListQueryBuilder::for(
            Blog::query(),
            ['id', 'created_at', 'updated_at', 'nombre'],
            []
        )
            ->withColumnFilters(
                textColumns: ['nombre'],
                exactColumns: [],
            )
            ->apply($request)
            ->paginate($request);

        return view('blog::livewire.blog.index', compact('blogs'))
            ->layout('components.layouts.app')
            ->with('i', ($this->getPage() - 1) * $blogs->perPage());
    }
}
