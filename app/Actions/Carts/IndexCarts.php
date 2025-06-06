<?php

declare(strict_types=1);

namespace App\Actions\Cart;

use Lunar\Models\Cart;

class IndexCarts
{
    public function execute($request)
    {
        $data = Cart::query()
            ->with(['thumbnail'])
            ->when($request->input('search'), function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->input('search')}%");
            })
            ->orderBy('name')
            ->get();

        return $data;
    }
}
