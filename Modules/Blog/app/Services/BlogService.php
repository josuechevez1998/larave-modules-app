<?php

namespace Modules\Blog\Services;

use App\Services\BaseService;
use Modules\Blog\Models\Blog;
use Illuminate\Database\Eloquent\Model;

class BlogService extends BaseService
{
    public function create(array $data): Model
    {
        return Blog::query()->create($data);
    }

    public function update(Model $model, array $data): Model
    {
        /** @var Blog $model */
        $model->update($data);

        return $model->refresh();
    }

    public function delete(Model $model): bool
    {
        return (bool) $model->delete();
    }
}
