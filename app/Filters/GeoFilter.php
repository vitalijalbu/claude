<?php

declare(strict_types=1);

namespace App\Filters;

use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;

class GeoFilter
{
    public static function countryFilters(): array
    {
        return [
            AllowedFilter::partial('name'),
            AllowedFilter::exact('code'),
            AllowedFilter::exact('is_active'),
        ];
    }

    public static function regionFilters(): array
    {
        return [
            AllowedFilter::partial('name'),
            AllowedFilter::exact('country_id'),
        ];
    }

    public static function provinceFilters(): array
    {
        return [
            AllowedFilter::partial('name'),
            AllowedFilter::exact('region_id'),
        ];
    }

    public static function cityFilters(): array
    {
        return [
            AllowedFilter::partial('name'),
            AllowedFilter::exact('province_id'),
        ];
    }

    public static function geoSorts(): array
    {
        return [
            AllowedSort::field('name'),
            AllowedSort::field('code'),
            AllowedSort::field('created_at'),
        ];
    }

    public static function geoIncludes(): array
    {
        return [
            AllowedInclude::relationship('country'),
            AllowedInclude::relationship('region'),
            AllowedInclude::relationship('province'),
            AllowedInclude::relationship('provinces'),
            AllowedInclude::relationship('cities'),
            AllowedInclude::count('regions'),
            AllowedInclude::count('provinces'),
            AllowedInclude::count('cities'),
        ];
    }
}
