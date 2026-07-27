<?php

namespace Modules\ToDo\Services;

use App\Services\BaseService;
use Modules\ToDo\Models\TodoEstado;
use Illuminate\Database\Eloquent\Model;

class TodoEstadoService extends BaseService
{
    public function create(array $data): Model
    {
        return TodoEstado::query()->create($data);
    }

    public function update(Model $model, array $data): Model
    {
        /** @var TodoEstado $model */
        $model->update($data);

        return $model->refresh();
    }

    public function delete(Model $model): bool
    {
        return (bool) $model->delete();
    }
}
