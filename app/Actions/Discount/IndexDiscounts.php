<?php

declare(strict_types=1);

namespace App\Actions\Discount;

use Lunar\Models\Discount;



class IndexDiscounts
{
    public function execute($request)
    {
        $data = Discount::query()
            ->with(['thumbnail'])
            ->when($request->input('search'), function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->input('search')}%");
            })
            ->orderBy('name')
            ->get();

        return $data;
    }
}
