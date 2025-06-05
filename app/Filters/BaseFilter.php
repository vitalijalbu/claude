<?php

declare(strict_types=1);

namespace App\Filters;

use Spatie\QueryBuilder\AllowedFilter;

abstract class BaseFilter
{
    abstract public static function getFilterableFields(): array;

    abstract public static function getSortableFields(): array;

    public static function getFilterableRelations(): array
    {
        return [];
    }

    public static function getIncludableRelations(): array
    {
        return [];
    }

    public static function getAllowedFilters(): array
    {
        $filters = array_map(fn ($field) => AllowedFilter::exact($field), static::getFilterableFields());

        foreach (static::getFilterableRelations() as $alias => $relation) {
            $filters[] = AllowedFilter::exact($alias, is_array($relation) ? implode('.', $relation) : $relation);
        }

        return $filters;
    }
}
