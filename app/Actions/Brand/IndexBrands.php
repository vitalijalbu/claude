<?php

declare(strict_types=1);

namespace App\Actions\Brand;

use Lunar\Models\Brand;



class IndexBrands
{
    public function execute($request)
    {
        $data = Brand::query()
            ->with(['thumbnail'])
            ->when($request->input('search'), function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->input('search')}%");
            })
            ->orderBy('name')
            ->get();

        return $data;
    }
}
