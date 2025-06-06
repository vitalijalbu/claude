<?php

declare(strict_types=1);

namespace App\Actions\Product;

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
            'variants.prices',
            'variants.images',
            'tags',
        ]);

        return $product;
    }
}
