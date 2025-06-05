<?php

declare(strict_types=1);

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class Operators
{
    public static function apply(Builder $query, string $field, string $operator, $value): Builder
    {
        return match (mb_strtolower($operator)) {
            'eq' => $query->where($field, $value),
            'in' => $query->whereIn($field, explode(',', $value)),
            'contains', 'like' => $query->where($field, 'like', "%{$value}%"),
            'between' => $query->whereBetween($field, explode(',', $value)),
            'gt' => $query->where($field, '>', $value),
            'gte' => $query->where($field, '>=', $value),
            'lt' => $query->where($field, '<', $value),
            'lte' => $query->where($field, '<=', $value),
            'ne' => $query->where($field, '!=', $value),
            default => $query->where($field, $value),
        };
    }

    public static function applyRelation(Builder $query, string $relation, string $field, string $operator, $value): Builder
    {
        return $query->whereHas($relation, function (Builder $q) use ($field, $operator, $value) {
            return self::apply($q, $field, $operator, $value);
        });
    }
}
