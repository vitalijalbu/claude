<?php

declare(strict_types=1);

namespace App\Actions\Discount;

use Lunar\Models\Discount;

class ShowDiscount
{
    public function execute(Discount $discount): Discount
    {
        $discount->load([
            'brands',
            'collections',
            'customerGroups',
            'purchasableRewards',
            'purchasableConditions',
        ]);

        return $discount;
    }
}
