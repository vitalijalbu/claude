<?php

declare(strict_types=1);

namespace App\Actions\Wishlist;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexWishlist
{
    public function execute(Request $request)
    {
        return QueryBuilder::for(Wishlist::class)
            ->where('user_id', auth()->id())
            ->allowedFilters([
                AllowedFilter::exact('wishlistable_type'),
                AllowedFilter::callback('product_name', function ($query, $value) {
                    $query->whereHasMorph('wishlistable', ['Lunar\Models\Product'], function ($q) use ($value) {
                        $q->where('name', 'like', "%{$value}%");
                    });
                }),
            ])
            ->allowedSorts([
                'created_at',
                'updated_at',
            ])
            ->allowedIncludes([
                'wishlistable',
                'wishlistable.images',
                'wishlistable.brand',
                'wishlistable.product', // For variants
            ])
            ->with(['wishlistable'])
            ->defaultSort('-created_at')
            ->paginate($request->input('per_page', 15))
            ->appends($request->query());
    }
}
