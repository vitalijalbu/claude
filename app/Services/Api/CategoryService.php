<?php

namespace App\Services\Api;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    protected CategoryRepository $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findAll(): Collection
    {
        return $this->repository->findAll();
    }

    public function findOne(string $query): Category
    {
        return $this->repository->findOne($query);
    }

    public function findBySlug(string $slug): Category
    {
        return $this->repository->findBySlug($slug);
    }
}
