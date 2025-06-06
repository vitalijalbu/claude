<?php

declare(strict_types=1);

namespace App\Actions\Collection;

use Illuminate\Http\Request;
use Lunar\Models\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class IndexCollections
{
    public function execute(Request $request)
    {
        return QueryBuilder::for(Collection::class)
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::partial('slug'),
                AllowedFilter::exact('external_id'),
                AllowedFilter::exact('parent_id'),
                AllowedFilter::callback('root_only', function ($query, $value) {
                    if (filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
                        $query->whereNull('parent_id');
                    }
                }),
                AllowedFilter::callback('has_products', function ($query, $value) {
                    if (filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
                        $query->has('products');
                    }
                }),
            ])
            ->allowedSorts([
                'name',
                'sort',
                'created_at',
                'updated_at',
                AllowedSort::callback('products_count', function ($query, $descending) {
                    $direction = $descending ? 'desc' : 'asc';
                    $query->withCount('products')->orderBy('products_count', $direction);
                }),
            ])
            ->allowedIncludes([
                'thumbnail',
                'children',
                'parent',
                'products',
                'products.variants',
            ])
            ->with(['thumbnail', 'children'])
            ->defaultSort('sort')
            ->paginate($request->input('per_page', 15))
            ->appends($request->query());
    }
}
