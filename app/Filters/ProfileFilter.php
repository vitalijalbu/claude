<?php

declare(strict_types=1);

namespace App\Filters;

use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;

class ProfileFilter
{
    public static function filters(): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('phone_number'),
            AllowedFilter::exact('city_id'),
            AllowedFilter::partial('name'),
            AllowedFilter::partial('bio'),
            AllowedFilter::scope('rating_range'),
            'city.name',
            'category.slug',
        ];
    }

    public static function sorts(): array
    {
        return [
            AllowedSort::field('id'),
            AllowedSort::field('name'),
            AllowedSort::field('rating'),
            AllowedSort::field('created_at'),
            AllowedSort::field('updated_at'),
            'listings_count',
        ];
    }

    public static function includes(): array
    {
        return [
            AllowedInclude::relationship('city'),
            AllowedInclude::relationship('province'),
            AllowedInclude::relationship('category'),
            AllowedInclude::relationship('listings'),
            AllowedInclude::relationship('taxonomies'),
            AllowedInclude::relationship('taxonomies.group'),
            AllowedInclude::count('listings'),
        ];
    }
}
