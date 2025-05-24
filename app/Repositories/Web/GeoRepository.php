<?php

namespace App\Repositories\Web;

use App\Models\Geo\City;
use App\Models\Geo\Country;
use App\Models\Geo\Nationality;
use App\Models\Geo\Province;
use App\Models\Geo\Region;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

final class GeoRepository
{
    protected array $sorters = ['name'];

    public function findAllCities($params): LengthAwarePaginator
    {
        return QueryBuilder::for(City::class)
            ->allowedFilters(['name', 'slug', 'country.code', 'country.name'])
            ->allowedSorts(['name'])
            ->allowedIncludes(['region', 'province', 'country'])
            ->paginate($params['per_page'] ?? 50)
            ->appends($params);
    }

    public function findAllRegions($params): LengthAwarePaginator
    {
        return QueryBuilder::for(Region::class)
            ->allowedFilters(['name', 'country.code', 'country.name'])
            ->allowedSorts(['name'])
            ->allowedIncludes(['country'])
            ->paginate($params['per_page'] ?? 50)
            ->appends($params);
    }

    public function findAllProvinces($params): LengthAwarePaginator
    {
        return QueryBuilder::for(Province::class)
            ->allowedFilters(['name', 'region_id', 'country_id'])
            ->allowedSorts(['name'])
            ->allowedIncludes(['region', 'country'])
            ->paginate($params['per_page'] ?? 50)
            ->appends($params);
    }

    public function findAllCountries($params): LengthAwarePaginator
    {
        return QueryBuilder::for(Country::class)
            ->allowedFilters(['name', 'code'])
            ->allowedSorts(['name'])
            ->paginate($params['per_page'] ?? 50)
            ->appends($params);
    }

    public function findCitiesByRegion(int $regionId, $params): LengthAwarePaginator
    {
        return QueryBuilder::for(City::where('region_id', $regionId))
            ->allowedFilters(['name', 'province.name', 'region.name', 'country.code', 'country.name'])
            ->allowedSorts(['name'])
            ->allowedIncludes(['region', 'country'])
            ->paginate($params['per_page'] ?? 50)
            ->appends($params);
    }

    public function findCitiesByCountry(int $countryId, $params): LengthAwarePaginator
    {
        return QueryBuilder::for(City::where('country_id', $countryId))
            ->allowedFilters(['name', 'province.name', 'region.name', 'country.code', 'country.name'])
            ->allowedSorts(['name'])
            ->allowedIncludes(['region', 'country'])
            ->paginate($params['per_page'] ?? 50)
            ->appends($params);
    }

    public function findRegionsByCountry(int $countryId, $params): LengthAwarePaginator
    {
        return QueryBuilder::for(Region::where('country_id', $countryId))
            ->allowedFilters(['name'])
            ->allowedSorts(['name'])
            ->allowedIncludes(['country'])
            ->paginate($params['per_page'] ?? 50)
            ->appends($params);
    }

    public function search(?string $query)
    {
        return QueryBuilder::for(City::class)
            ->allowedFilters(['name'])
            ->when($query, fn ($q) => $q->where('name', 'LIKE', "%{$query}%"))
            ->limit(5)
            ->get()
            ->each->setAttribute('type', 'city');
    }

    // Search city by name, slug or id
    public function findCity($query)
    {
        return City::when($query, function ($q) use ($query) {
            $q->where(function ($sub) use ($query) {
                $sub->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('slug', 'LIKE', "%{$query}%")
                    ->orWhere('id', $query);
            });
        })
            ->first();
    }

    // Find all nationalities
    public function findAllNationalities(): array
    {
        return Nationality::where('site_id', 1)->with('country')->get()->toArray();
    }
}
