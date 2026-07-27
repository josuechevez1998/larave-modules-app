<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

/**
 * Base service for domain logic shared by Livewire and API controllers.
 */
abstract class BaseService
{
    /**
     * @param  array<string, mixed>  $data
     */
    abstract public function create(array $data): Model;

    /**
     * @param  array<string, mixed>  $data
     */
    abstract public function update(Model $model, array $data): Model;

    abstract public function delete(Model $model): bool;
}
