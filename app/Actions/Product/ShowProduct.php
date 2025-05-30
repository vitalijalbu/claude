<?php

declare(strict_types=1);

namespace App\Actions\Product;

use App\Http\Resources\ProductResource;
use Lunar\Models\Product;

class ShowProduct
{
    public function execute(Product $product)
    {
        $product->load([            
            'images',
            'prices',
            'thumbnail',
            'urls',
            'brand',
            'collections',
            'variants',
            'tags'
        ]);

        return new ProductResource($product);
    }
}
