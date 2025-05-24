<?php

namespace App\Services\Api;

use App\Repositories\GeoRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GeoService
{
    protected GeoRepository $repository;

    public function __construct(GeoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findAllCountries($params): LengthAwarePaginator
    {
        return $this->repository->findAllCountries($params);
    }

    // --------------------
    // Regions
    // --------------------

    public function findAllRegions($params): LengthAwarePaginator
    {
        return $this->repository->findAllRegions($params);
    }

    // --------------------
    // Provinces
    // --------------------

    public function findAllProvinces($params): LengthAwarePaginator
    {
        return $this->repository->findAllProvinces($params);
    }

    // --------------------
    // Cities
    // --------------------
    public function findAllCities($params): LengthAwarePaginator
    {
        return $this->repository->findAllCities($params);
    }

    public function findCity($query = [])
    {
        return $this->repository->findCity($query);
    }

    // --------------------
    // Nationalities
    // --------------------
    public function findAllNationalities(): array
    {
        return $this->repository->findAllNationalities();
    }
}
