<?php

declare(strict_types=1);

namespace App\Actions\Discount;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Lunar\Models\Discount;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexDiscounts
{
    public function execute(Request $request): LengthAwarePaginator
    {
        $data = QueryBuilder::for(Discount::class)
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::partial('coupon'),
                AllowedFilter::exact('type'),
                AllowedFilter::callback('active', function ($query, $value) {
                    if (filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
                        $query->where('starts_at', '<=', now())
                            ->where(function ($q) {
                                $q->whereNull('ends_at')
                                    ->orWhere('ends_at', '>=', now());
                            });
                    }
                }),
                AllowedFilter::callback('has_coupon', function ($query, $value) {
                    if (filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
                        $query->whereNotNull('coupon');
                    }
                }),
                AllowedFilter::callback('available', function ($query, $value) {
                    if (filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
                        $query->where(function ($q) {
                            $q->whereNull('max_uses')
                                ->orWhereRaw('uses < max_uses');
                        });
                    }
                }),
            ])
            ->allowedSorts([
                'name',
                'starts_at',
                'ends_at',
                'priority',
                'uses',
                'created_at',
                'updated_at',
            ])
            ->allowedIncludes([
                'brands',
                'collections',
                'customerGroups',
                'purchasableRewards',
                'purchasableConditions',
            ])
            ->defaultSort('-priority')
            ->paginate($request->input('per_page', 15))
            ->appends($request->query());

        return $data;
    }
}
