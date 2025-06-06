<?php

declare(strict_types=1);

namespace App\Actions\Order;

use Lunar\Models\Order;

class ShowOrder
{
    public function execute(Order $order): Order
    {
        // Authorization check
        if (auth()->check() && ! auth()->user()->isAdmin() && $order->user_id !== auth()->id()) {
            throw new \Illuminate\Auth\Access\AuthorizationException('You can only view your own orders');
        }

        $order->load([
            'lines.purchasable.product',
            'lines.purchasable.images',
            'user',
            'shippingAddress',
            'billingAddress',
            'transactions',
            'discounts',
        ]);

        return $order;
    }
}
