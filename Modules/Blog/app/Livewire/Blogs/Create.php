<?php

namespace Modules\Blog\Livewire\Blogs;

use Modules\Blog\Livewire\Forms\BlogForm;
use Modules\Blog\Services\BlogService;
use Illuminate\View\View;
use Livewire\Component;

class Create extends Component
{
    public BlogForm $form;

    public function save(BlogService $service)
    {
        $this->form->store($service);

        flash_swal_toast(__('Registro guardado.'), 'success');

        return $this->redirectRoute('blogs.index');
    }

    public function render(): View
    {
        return view('blog::livewire.blog.create')
            ->layout('components.layouts.app');
    }
}
