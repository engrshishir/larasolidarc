<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enum\DeleteStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class CrudRepository
{
    private const DELETED_COLUMN = 'deleted';
    public const PER_PAGE = 20;
    protected string $model;

    public function query(): Builder
    {
        return $this->model::query();
    }

    public function count(): int
    {
        return $this->model::query()->count();
    }

    private function getDefaultOrderBy(): array
    {
        return ['column' => 'id', 'order' => 'desc'];
    }

    public function AuthUser()
    {
        return auth()->user() ?? null;
    }

    public function AuthId()
    {
        return auth()->user()->id ?? null;
    }

    public function AuthRole()
    {
        return auth()->user()->roles()->first();
    }

    public function all(array $filters = [], ?array $orderBy = null): Collection
    {
        // Start with a query on the model
        $query = $this->model::query();
    
        // Set the default order by if not provided
        $orderBy ??= $this->getDefaultOrderBy();
    
        // Apply filters if provided
        if (!empty($filters)) {
            foreach ($filters as $filter) {
                if (isset($filter['column'], $filter['operator'], $filter['value'])) {
                    // Apply each filter with its column, operator, and value
                    $query->where($filter['column'], $filter['operator'], $filter['value']);
                }
            }
        }
    
        // Apply the order by clause
        if ($orderBy && isset($orderBy['column'], $orderBy['order'])) {
            $query->orderBy($orderBy['column'], $orderBy['order']);
        }
    
        // Execute the query and return the results
        return $query->get();
    }
    
    

    public function paginate(?int $perPage = null, array $filters = [], $orderBy = null): array|LengthAwarePaginator
    {
        $query = $this->model::query();
        $perPage ??= self::PER_PAGE; // Default items per page
        $orderBy ??= $this->getDefaultOrderBy(); // Default ordering
    
        // Apply filters if provided
        if (!empty($filters)) {
            foreach ($filters as $filter) {
                if (isset($filter['column'], $filter['operator'], $filter['value'])) {
                    $query->where($filter['column'], $filter['operator'], $filter['value']);
                }
            }
        }
        // Apply the order by clause
        if ($orderBy && isset($orderBy['column'], $orderBy['order'])) {
            $query->orderBy($orderBy['column'], $orderBy['order']);
        }
    
        // Paginate results and return
        return $query->paginate($perPage);
    }
    

    public function create(array $data): object
    {
        return $this->model::create($data);
    }

    public function insert(array $data): bool
    {
        return $this->model::query()->insert($data);
    }

    public function update(object $model, array $data): object
    {
        $model->update($data);
        return $model;
    }

    public function delete(object $model): ?object
    {
        $query = $this->model::where('id', $model->id);
        $query->delete();
        return $model;
    }

    public function find(int $id): ?object
    {
        return $this->model::query()->find($id);
    }

    public function findByColumn(string $column, string $value): ?object
    {
        return $this->model::query()->where($column, $value)->get();
    }

    public function revertBack(object $model): ?object
    {
        $query = $this->model::where('id', $model->id);
        $query->update(['deleted' => DeleteStatus::NOT_DELETED]);
        return $model;
    }

    public function softDelete(object $model): ?object
    {
        $query = $this->model::where('id', $model->id);
        $query->update(['deleted' => DeleteStatus::SOFT_DELETE]);
        return $model;
    }

    public function findByWhere(array $keyValueArray): Collection
    {
        return $this->model::where($keyValueArray)->get();
    }
}