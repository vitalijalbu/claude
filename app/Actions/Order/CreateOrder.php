<?php

declare(strict_types=1);

namespace App\Actions\Order;

use Illuminate\Support\Facades\DB;
use Lunar\Models\Cart;
use Lunar\Models\Order;

class CreateOrder
{
    public function execute(Cart $cart, array $data = []): Order
    {
        return DB::transaction(function () use ($cart, $data) {
            $order = Order::create([
                'user_id' => $cart->user_id ?? auth()->id(),
                'channel_id' => $cart->channel_id ?? 1,
                'status' => 'pending',
                'reference' => $this->generateReference(),
                'customer_reference' => $data['customer_reference'] ?? null,
                'sub_total' => $cart->subTotal,
                'discount_total' => $cart->discountTotal ?? 0,
                'shipping_total' => $data['shipping_total'] ?? 0,
                'tax_total' => $cart->taxTotal,
                'total' => $cart->total,
                'notes' => $data['notes'] ?? null,
                'currency_code' => $cart->currency->code,
                'compare_currency_code' => $cart->currency->code,
                'exchange_rate' => 1,
                'placed_at' => now(),
                'meta' => $data['meta'] ?? null,
            ]);

            // Copy cart lines to order lines
            foreach ($cart->lines as $cartLine) {
                $order->lines()->create([
                    'purchasable_type' => $cartLine->purchasable_type,
                    'purchasable_id' => $cartLine->purchasable_id,
                    'type' => 'physical',
                    'description' => $cartLine->purchasable->name ?? 'Product',
                    'option' => $cartLine->purchasable->option ?? null,
                    'identifier' => $cartLine->purchasable->sku ?? $cartLine->purchasable->id,
                    'unit_price' => $cartLine->unitPrice,
                    'unit_quantity' => 1,
                    'quantity' => $cartLine->quantity,
                    'sub_total' => $cartLine->subTotal,
                    'discount_total' => $cartLine->discountTotal ?? 0,
                    'tax_total' => $cartLine->taxTotal,
                    'total' => $cartLine->total,
                    'notes' => null,
                    'meta' => $cartLine->meta,
                ]);
            }

            // Copy addresses
            if ($cart->shippingAddress) {
                $order->shippingAddress()->create($cart->shippingAddress->toArray());
            }

            if ($cart->billingAddress) {
                $order->billingAddress()->create($cart->billingAddress->toArray());
            }

            // Copy discounts
            if ($cart->discounts) {
                foreach ($cart->discounts as $discount) {
                    $order->discounts()->attach($discount->id);
                }
            }

            return $order;
        });
    }

    private function generateReference(): string
    {
        return 'ORD-'.strtoupper(uniqid());
    }
}
