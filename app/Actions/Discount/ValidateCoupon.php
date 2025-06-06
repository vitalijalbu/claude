<?php

declare(strict_types=1);

namespace App\Actions\Discount;

use Lunar\Models\Cart;
use Lunar\Models\Discount;

class ValidateCoupon
{
    public function execute(string $couponCode, ?Cart $cart = null): array
    {
        $discount = Discount::where('coupon', $couponCode)->first();

        if (! $discount) {
            return [
                'valid' => false,
                'message' => 'Coupon code not found',
                'discount' => null,
            ];
        }

        // Check if discount is active
        if ($discount->starts_at > now()) {
            return [
                'valid' => false,
                'message' => 'Coupon is not yet active',
                'discount' => $discount,
            ];
        }

        if ($discount->ends_at && $discount->ends_at < now()) {
            return [
                'valid' => false,
                'message' => 'Coupon has expired',
                'discount' => $discount,
            ];
        }

        // Check usage limits
        if ($discount->max_uses && $discount->uses >= $discount->max_uses) {
            return [
                'valid' => false,
                'message' => 'Coupon usage limit reached',
                'discount' => $discount,
            ];
        }

        // Check per-user limits if user is authenticated
        if (auth()->check() && $discount->max_uses_per_customer) {
            $userUsage = $discount->orders()
                ->where('user_id', auth()->id())
                ->count();

            if ($userUsage >= $discount->max_uses_per_customer) {
                return [
                    'valid' => false,
                    'message' => 'You have reached the usage limit for this coupon',
                    'discount' => $discount,
                ];
            }
        }

        // Check minimum spend if cart is provided
        if ($cart && $discount->min_spend) {
            $cartTotal = $cart->subTotal->value;
            if ($cartTotal < $discount->min_spend) {
                return [
                    'valid' => false,
                    'message' => "Minimum spend of {$discount->min_spend} required",
                    'discount' => $discount,
                ];
            }
        }

        return [
            'valid' => true,
            'message' => 'Coupon is valid',
            'discount' => $discount,
        ];
    }
}
