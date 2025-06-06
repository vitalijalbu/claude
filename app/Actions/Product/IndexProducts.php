<?php

declare(strict_types=1);

namespace App\Actions\Product;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Lunar\Models\Product;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class IndexProducts
{
    public function execute(Request $request): LengthAwarePaginator
    {
        return QueryBuilder::for(Product::class)
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::partial('sku'),
                AllowedFilter::exact('brand_id'),
                AllowedFilter::exact('status'),
                AllowedFilter::callback('collection_id', function ($query, $value) {
                    $query->whereHas('collections', function ($q) use ($value) {
                        $q->where('collections.id', $value);
                    });
                }),
                AllowedFilter::callback('brand_slug', function ($query, $value) {
                    $query->whereHas('brand', function ($q) use ($value) {
                        $q->where('slug', $value);
                    });
                }),
                AllowedFilter::callback('collection_slug', function ($query, $value) {
                    $query->whereHas('collections', function ($q) use ($value) {
                        $q->where('collections.slug', $value);
                    });
                }),
                AllowedFilter::callback('price_min', function ($query, $value) {
                    $query->whereHas('variants.prices', function ($q) use ($value) {
                        $q->where('price', '>=', $value * 100); // Convert to cents
                    });
                }),
                AllowedFilter::callback('price_max', function ($query, $value) {
                    $query->whereHas('variants.prices', function ($q) use ($value) {
                        $q->where('price', '<=', $value * 100); // Convert to cents
                    });
                }),
                AllowedFilter::callback('in_stock', function ($query, $value) {
                    if (filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
                        $query->whereHas('variants', function ($q) {
                            $q->where('stock', '>', 0);
                        });
                    }
                }),
            ])
            ->allowedSorts([
                'name',
                'created_at',
                'updated_at',
                AllowedSort::callback('price', function ($query, $descending) {
                    $direction = $descending ? 'desc' : 'asc';
                    $query->leftJoin('product_variants', 'products.id', '=', 'product_variants.product_id')
                        ->leftJoin('prices', 'product_variants.id', '=', 'prices.priceable_id')
                        ->where('prices.priceable_type', 'Lunar\Models\ProductVariant')
                        ->orderBy('prices.price', $direction)
                        ->select('products.*')
                        ->distinct();
                }),
            ])
            ->allowedIncludes([
                'brand',
                'collections',
                'variants',
                'variants.prices',
                'images',
                'thumbnail',
            ])
            ->with(['variants', 'images', 'collections', 'brand', 'thumbnail'])
            ->defaultSort('-created_at')
            ->paginate($request->input('per_page', 15))
            ->appends($request->query());
    }
}
