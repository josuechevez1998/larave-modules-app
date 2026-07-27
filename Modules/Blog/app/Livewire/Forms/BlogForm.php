<?php

namespace Modules\Blog\Livewire\Forms;

use Modules\Blog\Services\BlogService;
use Modules\Blog\Models\Blog;
use Livewire\Form;

class BlogForm extends Form
{
    public ?Blog $blogModel = null;
    
    public $nombre = '';

    public function rules(): array
    {
        return [
			'nombre' => 'required|string',
        ];
    }

    public function setBlogModel(Blog $blogModel): void
    {
        $this->blogModel = $blogModel;
        
        $this->nombre = $this->blogModel->nombre;
    }

    public function store(BlogService $service): void
    {
        $service->create($this->validate());

        $this->reset();
    }

    public function update(BlogService $service): void
    {
        $service->update($this->blogModel, $this->validate());

        $this->reset();
    }
}
