<?php

namespace App\Repositories\Web;

use App\Models\Geo\City;

final class CityRepository
{
    protected $model;

    protected array $filters = ['title', 'description', 'category_id', 'city_id', 'profile_id'];

    protected array $sorters = ['title'];

    protected array $with = [
        'city',
        'category',
        'profile',
    ];

    public function __construct(City $model)
    {
        $this->model = $model;
    }

    public function findAll()
    {
        return $this->model->get();
    }

    public function findSpotlight()
    {
        return $this->model->where('is_featured', true)->get();
    }

    public function findBySlug($slug)
    {
        return $this->model->where('slug', $slug)->firstOrFail();
    }
}
