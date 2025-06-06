<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Discount\IndexDiscounts;
use App\Actions\Discount\ShowDiscount;
use App\Actions\Discount\ValidateCoupon;
use App\Http\Resources\DiscountResource;
use Illuminate\Http\Request;
use Lunar\Models\Cart;
use Lunar\Models\Discount;

class DiscountController extends Controller
{
    public function index(Request $request, IndexDiscounts $action)
    {
        $data = $action->execute($request);

        return DiscountResource::collection($data);
    }

    public function show(Discount $discount, ShowDiscount $action)
    {
        return $action->execute($discount);
    }

    public function validateCoupon(Request $request, ValidateCoupon $action)
    {
        $request->validate([
            'coupon_code' => 'required|string',
            'cart_id' => 'nullable|exists:carts,id',
        ]);

        $cart = null;
        if ($request->cart_id) {
            $cart = Cart::find($request->cart_id);
        }

        $result = $action->execute($request->coupon_code, $cart);

        if (! $result['valid']) {
            return response()->json($result, 400);
        }

        return response()->json([
            'valid' => true,
            'discount' => new DiscountResource($result['discount']),
            'message' => $result['message'],
        ]);
    }
}
