<?php

declare(strict_types=1);

namespace App\Actions\Order;

use Illuminate\Http\Request;
use Lunar\Models\Order;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexOrders
{
    public function execute(Request $request)
    {
        $query = QueryBuilder::for(Order::class)
            ->allowedFilters([
                AllowedFilter::partial('reference'),
                AllowedFilter::partial('customer_reference'),
                AllowedFilter::exact('status'),
                AllowedFilter::exact('user_id'),
                AllowedFilter::exact('channel_id'),
                AllowedFilter::callback('user_email', function ($query, $value) {
                    $query->whereHas('user', function ($q) use ($value) {
                        $q->where('email', 'like', "%{$value}%");
                    });
                }),
                AllowedFilter::callback('user_name', function ($query, $value) {
                    $query->whereHas('user', function ($q) use ($value) {
                        $q->where('name', 'like', "%{$value}%");
                    });
                }),
                AllowedFilter::callback('total_min', function ($query, $value) {
                    $query->where('total', '>=', $value * 100); // Convert to cents
                }),
                AllowedFilter::callback('total_max', function ($query, $value) {
                    $query->where('total', '<=', $value * 100); // Convert to cents
                }),
                AllowedFilter::callback('date_from', function ($query, $value) {
                    $query->where('placed_at', '>=', $value);
                }),
                AllowedFilter::callback('date_to', function ($query, $value) {
                    $query->where('placed_at', '<=', $value);
                }),
            ])
            ->allowedSorts([
                'reference',
                'status',
                'total',
                'placed_at',
                'created_at',
                'updated_at',
            ])
            ->allowedIncludes([
                'user',
                'lines',
                'lines.purchasable',
                'shippingAddress',
                'billingAddress',
                'discounts',
                'transactions',
            ])
            ->with(['user', 'shippingAddress', 'billingAddress'])
            ->defaultSort('-placed_at');

        // If user is authenticated and not admin, only show their orders
        if (auth()->check() && ! auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        return $query->paginate($request->input('per_page', 15))
            ->appends($request->query());
    }
}
