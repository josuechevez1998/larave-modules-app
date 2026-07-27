<?php

namespace App\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ListQueryBuilder
{
    /** @param  array<int, string>  $sortable */
    /** @param  array<int, string>  $includes */
    public function __construct(
        protected Builder $query,
        protected array $sortable = ['id', 'created_at', 'updated_at'],
        protected array $includes = [],
        protected int $defaultPerPage = 15,
        protected int $maxPerPage = 100,
    ) {}

    public static function for(Builder|string $model, array $sortable = [], array $includes = []): self
    {
        $query = $model instanceof Builder ? $model : $model::query();

        return new self($query, $sortable ?: ['id', 'created_at', 'updated_at'], $includes);
    }

    public function apply(Request $request): self
    {
        $this->applyIncludes($request);
        $this->applyStatus($request);
        $this->applySort($request);

        return $this;
    }

    public function applyIncludes(Request $request): self
    {
        $raw = $request->input('include', $request->input('with'));
        if (! $raw) {
            return $this;
        }

        $requested = is_array($raw) ? $raw : explode(',', (string) $raw);
        $allowed = array_values(array_intersect($this->includes, array_map('trim', $requested)));

        if ($allowed !== []) {
            $this->query->with($allowed);
        }

        return $this;
    }

    public function applyStatus(Request $request): self
    {
        if ($request->filled('status_id') && $this->hasColumn('status_id')) {
            $this->query->where('status_id', $request->integer('status_id'));
        }

        if ($request->filled('status') && $this->hasColumn('status_id')) {
            $this->query->whereHas('status', fn (Builder $q) => $q->where('code', $request->string('status')->toString()));
        }

        return $this;
    }

    public function applySort(Request $request): self
    {
        $sort = $request->string('sort')->toString() ?: '-id';
        $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
        $column = ltrim($sort, '-');

        if (! in_array($column, $this->sortable, true)) {
            $column = 'id';
            $direction = 'desc';
        }

        $this->query->orderBy($column, $direction);

        return $this;
    }

    public function paginate(Request $request): LengthAwarePaginator
    {
        $perPage = min(
            max(1, $request->integer('per_page', $this->defaultPerPage)),
            $this->maxPerPage
        );

        return $this->query->paginate($perPage)->withQueryString();
    }

    public function query(): Builder
    {
        return $this->query;
    }

    protected function hasColumn(string $column): bool
    {
        /** @var Model $model */
        $model = $this->query->getModel();

        if (in_array($column, $model->getFillable(), true)) {
            return true;
        }

        return $model->getConnection()
            ->getSchemaBuilder()
            ->hasColumn($model->getTable(), $column);
    }
}
