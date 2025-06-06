<?php

declare(strict_types=1);

namespace App\Actions\Brand;

use Illuminate\Http\Request;
use Lunar\Models\Brand;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class IndexBrands
{
    public function execute(Request $request)
    {
        return QueryBuilder::for(Brand::class)
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::partial('slug'),
                AllowedFilter::exact('external_id'),
                AllowedFilter::callback('has_products', function ($query, $value) {
                    if (filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
                        $query->has('products');
                    }
                }),
            ])
            ->allowedSorts([
                'name',
                'created_at',
                'updated_at',
                AllowedSort::callback('products_count', function ($query, $descending) {
                    $direction = $descending ? 'desc' : 'asc';
                    $query->withCount('products')->orderBy('products_count', $direction);
                }),
            ])
            ->allowedIncludes([
                'thumbnail',
                'products',
                'products.variants',
            ])
            ->with(['thumbnail'])
            ->defaultSort('name')
            ->paginate($request->input('per_page', 15))
            ->appends($request->query());
    }
}
