<?php

declare(strict_types=1);

namespace App\Actions\Brand;

use App\Http\Resources\BrandResource;
use Lunar\Models\Brand;

class ShowBrand
{
    public function execute(Brand $brand)
    {
        // $brand->load([
        //     'images',
        //     'prices',
        //     'thumbnail',
        //     'urls',
        //     'brand',
        //     'collections',
        //     'variants',
        //     'tags'
        // ]);

        return new BrandResource($brand);
    }
}
