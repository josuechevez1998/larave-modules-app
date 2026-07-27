<?php

namespace Modules\Blog\Livewire\Blogs;

use Modules\Blog\Livewire\Forms\BlogForm;
use Modules\Blog\Models\Blog;
use Modules\Blog\Services\BlogService;
use Illuminate\View\View;
use Livewire\Component;

class Edit extends Component
{
    public BlogForm $form;

    public function mount(Blog $blog): void
    {
        $this->form->setBlogModel($blog);
    }

    public function save(BlogService $service)
    {
        $this->form->update($service);

        flash_swal_toast(__('Registro actualizado.'), 'success');

        return $this->redirectRoute('blogs.index');
    }

    public function render(): View
    {
        return view('blog::livewire.blog.edit')
            ->layout('components.layouts.app');
    }
}
