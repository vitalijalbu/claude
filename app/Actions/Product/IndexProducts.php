<?php

declare(strict_types=1);

namespace App\Actions\Product;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Lunar\Models\Product;

class IndexProducts
{
    public function execute($request): LengthAwarePaginator
    {
        $data = Product::query()
            ->with(['variants', 'images', 'collections', 'brand'])
            ->when($request->input('search'), function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->input('search')}%");
            })
            ->orderBy('name')
            ->paginate($request->per_page, ['*'], 'page', $request->page);

        return $data;
    }
}
