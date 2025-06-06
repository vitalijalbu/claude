<?php

declare(strict_types=1);

namespace App\Actions\Cart;

use App\Http\Resources\CartResource;
use Lunar\Models\Cart;

class ShowCart
{
    public function execute(Cart $brand)
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

        return new CartResource($brand);
    }
}
