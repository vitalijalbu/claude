<?php

namespace App\Repositories\Web;

use App\Models\TaxonomyGroup;

class TaxonomyRepository
{
    protected $model;

    public function __construct(TaxonomyGroup $model)
    {
        $this->model = $model;
    }

    public function findAll()
    {
        return $this->model->load('taxonomies')->get();
    }

    public function findBySlug($slug)
    {
        return $this->model->where('slug', $slug)->firstOrFail();
    }
}
