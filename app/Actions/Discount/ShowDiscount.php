<?php

declare(strict_types=1);

namespace App\Actions\Discount;

use App\Http\Resources\DiscountResource;
use Lunar\Models\Discount;

class ShowDiscount
{
    public function execute(Discount $discount)
    {
        // $discount->load([            
        //     'images',
        //     'prices',
        //     'thumbnail',
        //     'urls',
        //     'brand',
        //     'collections',
        //     'variants',
        //     'tags'
        // ]);

        return $discount;
    }
}
