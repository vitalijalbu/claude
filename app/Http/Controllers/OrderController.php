<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Order\CreateOrder;
use App\Actions\Order\IndexOrders;
use App\Actions\Order\ShowOrder;
use App\Actions\Order\UpdateOrderStatus;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use Lunar\Models\Cart;
use Lunar\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request, IndexOrders $action)
    {
        $data = $action->execute($request);

        return OrderResource::collection($data);
    }

    public function show(Order $order, ShowOrder $action)
    {
        return $action->execute($order);
    }

    public function store(CreateOrderRequest $request, CreateOrder $action)
    {
        try {
            $cart = Cart::findOrFail($request->validated('cart_id'));

            // Verify cart has items
            if ($cart->lines()->count() === 0) {
                return response()->json(['error' => 'Cart is empty'], 400);
            }

            $order = $action->execute($cart, $request->validated());

            // Clear cart after successful order
            $cart->lines()->delete();

            return response()->json(new OrderResource($order), 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function updateStatus(Request $request, Order $order, UpdateOrderStatus $action)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
            'notes' => 'nullable|string',
        ]);

        // Authorization check for admin
        if (! auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Admin access required'], 403);
        }

        try {
            $result = $action->execute($order, $request->status, $request->notes);

            return response()->json(new OrderResource($result));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
