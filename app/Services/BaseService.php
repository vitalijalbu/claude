<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseService
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): ?Model
    {
        $record = $this->model->find($id);

        if (! $record) {
            throw new \Exception('Record not found');
        }

        $record->update($data);

        return $record;
    }

    public function delete(int $id): bool
    {
        $record = $this->model->find($id);

        if (! $record) {
            throw new \Exception('Record not found');
        }

        return $record->delete();
    }
}
