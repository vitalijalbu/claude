<?php

declare(strict_types=1);

namespace App\Filters;

use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;

class ListingFilter
{
    public static function filters(): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('slug'),
            AllowedFilter::exact('category_id'),
            AllowedFilter::exact('city_id'),
            AllowedFilter::exact('profile_id'),
            AllowedFilter::exact('phone_number'),
            AllowedFilter::exact('is_verified'),
            AllowedFilter::exact('is_featured'),
            AllowedFilter::exact('is_active'),
            AllowedFilter::partial('title'),
            AllowedFilter::partial('description'),
            AllowedFilter::scope('price_range'),
            AllowedFilter::scope('age_range'),
            'category.slug',
            'city.slug',
            'province.slug',
        ];
    }

    public static function sorts(): array
    {
        return [
            AllowedSort::field('id'),
            AllowedSort::field('title'),
            AllowedSort::field('created_at'),
            AllowedSort::field('updated_at'),
            AllowedSort::field('views_count'),
            AllowedSort::field('rating'),
            'category.name',
            'city.name',
        ];
    }

    public static function includes(): array
    {
        return [
            AllowedInclude::relationship('city'),
            AllowedInclude::relationship('category'),
            AllowedInclude::relationship('profile'),
            AllowedInclude::relationship('province'),
            AllowedInclude::relationship('taxonomies'),
            AllowedInclude::relationship('taxonomies.group'),
            AllowedInclude::relationship('media'),
        ];
    }
}
