<?php

namespace Modules\Blog\Livewire\Blogs;

use Modules\Blog\Livewire\Forms\BlogForm;
use Modules\Blog\Models\Blog;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    public BlogForm $form;

    public function mount(Blog $blog): void
    {
        $this->form->setBlogModel($blog);
    }

    public function render(): View
    {
        return view('blog::livewire.blog.show', ['blog' => $this->form->blogModel])
            ->layout('components.layouts.app');
    }
}
