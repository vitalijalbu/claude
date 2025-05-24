<?php

namespace App\Services\Api;

use App\Models\TaxonomyGroup;
use App\Repositories\TaxonomyRepository;
use Illuminate\Database\Eloquent\Collection;

class TaxonomyService
{
    protected TaxonomyRepository $repository;

    public function __construct(TaxonomyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findAll(): Collection
    {
        return $this->repository->findAll();
    }

    public function findBySlug(string $slug): TaxonomyGroup
    {
        return $this->repository->findBySlug($slug);
    }
}
