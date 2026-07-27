<?php

namespace Modules\ToDo\Services;

use App\Services\BaseService;
use Modules\ToDo\Models\Todo;
use Illuminate\Database\Eloquent\Model;

class TodoService extends BaseService
{
    public function create(array $data): Model
    {
        return Todo::query()->create($data);
    }

    public function update(Model $model, array $data): Model
    {
        /** @var Todo $model */
        $model->update($data);

        return $model->refresh();
    }

    public function delete(Model $model): bool
    {
        return (bool) $model->delete();
    }
}
