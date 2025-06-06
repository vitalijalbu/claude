<?php
declare(strict_types=1);
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DiscountResource;
use App\Actions\Discounts\IndexDiscountsAction;
use App\Actions\Discounts\ShowDiscountAction;
use App\Actions\Discounts\ValidateCouponAction;
use Lunar\Models\Discount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class DiscountController extends Controller
{
    public function index(IndexDiscountsAction $action): JsonResponse
    {
        $discounts = $action->execute(['per_page' => request('per_page', 15)]);
        
        return response()->json([
            'data' => DiscountResource::collection($discounts->items()),
            'meta' => [
                'current_page' => $discounts->currentPage(),
                'last_page' => $discounts->lastPage(),
                'per_page' => $discounts->perPage(),
                'total' => $discounts->total()
            ]
        ]);
    }

    public function show(Discount $discount, ShowDiscountAction $action): JsonResponse
    {
        $discount = $action->execute($discount);
        return response()->json(['data' => new DiscountResource($discount)]);
    }

    public function validateCoupon(Request $request, ValidateCouponAction $action): JsonResponse
    {
        $request->validate([
            'coupon' => 'required|string'
        ]);

        $result = $action->execute($request->coupon);

        if (!$result['valid']) {
            return response()->json([
                'valid' => false,
                'message' => $result['message']
            ], 400);
        }

        return response()->json([
            'valid' => true,
            'data' => new DiscountResource($result['discount']),
            'message' => $result['message']
        ]);
    }
}
