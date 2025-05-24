<?php

namespace App\Repositories;

use App\Models\Category;

final class CategoryRepository
{
    protected $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    public function findAll()
    {
        return $this->model->get();
    }

    public function findBySlug($slug)
    {
        return $this->model->where('slug', $slug)->firstOrFail();
    }

    public function findOne($query)
    {
        return Category::when($query, function ($q) use ($query) {
            $q->where(function ($sub) use ($query) {
                $sub->where('title', 'LIKE', "%{$query}%")
                    ->orWhere('slug', 'LIKE', "%{$query}%");
            });
        })
            ->first();
    }
}
