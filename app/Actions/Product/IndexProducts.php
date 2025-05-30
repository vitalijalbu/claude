<?php

declare(strict_types=1);

namespace App\Actions\Product;

use Lunar\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class IndexProducts
{
    public function execute($request): LengthAwarePaginator
    {
        $data = Product::query()
            ->with(['variants', 'images', 'categories', 'brands'])
            ->when($request->input('search'), function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->input('search')}%");
            })
            ->orderBy('name')
            ->paginate($request->per_page, ['*'], 'page', $request->page);

        return $data;
    }
}
