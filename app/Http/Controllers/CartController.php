<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Cart\AddToCart;
use App\Actions\Cart\ApplyCoupon;
use App\Actions\Cart\ClearCart;
use App\Actions\Cart\RemoveCoupon;
use App\Actions\Cart\RemoveFromCart;
use App\Actions\Cart\ShowCart;
use App\Actions\Cart\UpdateCartLine;
use App\Http\Requests\Cart\StoreCartRequest;
use App\Http\Resources\CartLineResource;
use App\Http\Resources\CartResource;
use Illuminate\Http\Request;
use Lunar\Models\Cart;
use Lunar\Models\CartLine;
use Lunar\Models\ProductVariant;

class CartController extends Controller
{
    public function show(ShowCart $action)
    {
        $cart = $this->getCurrentCart();

        return $action->execute($cart);
    }

    public function store(StoreCartRequest $request)
    {
        try {
            $cart = Cart::create([
                'session_id' => session()->getId(),
                'user_id' => auth()->id(),
                'currency_id' => $request->validated('currency_id') ?? 1,
                'meta' => $request->validated('meta') ?? null,
            ]);

            session(['cart_id' => $cart->id]);

            return response()->json([
                'message' => 'Cart created successfully',
                'cart' => new CartResource($cart),
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create cart'], 500);
        }
    }

    public function addItem(Request $request, AddToCart $action)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1|max:999',
            'meta' => 'nullable|array',
        ]);

        try {
            // Check if variant is purchasable
            $variant = ProductVariant::findOrFail($request->variant_id);
            if ($variant->purchasable === 'never') {
                return response()->json(['error' => 'This product variant is not available for purchase'], 400);
            }

            // Check stock if needed
            if ($variant->purchasable === 'in_stock' && $variant->stock < $request->quantity) {
                return response()->json(['error' => 'Insufficient stock available'], 400);
            }

            $cart = $this->getCurrentCart();
            $cartLine = $action->execute($cart, $request->validated());

            return response()->json([
                'message' => 'Item added to cart',
                'cart_line' => new CartLineResource($cartLine),
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function updateItem(Request $request, CartLine $cartLine, UpdateCartLine $action)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0|max:999',
            'meta' => 'nullable|array',
        ]);

        try {
            // Verify cart line belongs to current cart
            $currentCart = $this->getCurrentCart();
            if ($cartLine->cart_id !== $currentCart->id) {
                return response()->json(['error' => 'Cart line not found'], 404);
            }

            $result = $action->execute($cartLine, $request->validated());

            if (! $result) {
                return response()->json(['message' => 'Item removed from cart'], 200);
            }

            return response()->json([
                'message' => 'Cart item updated',
                'cart_line' => new CartLineResource($result),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function removeItem(CartLine $cartLine, RemoveFromCart $action)
    {
        try {
            // Verify cart line belongs to current cart
            $currentCart = $this->getCurrentCart();
            if ($cartLine->cart_id !== $currentCart->id) {
                return response()->json(['error' => 'Cart line not found'], 404);
            }

            $action->execute($cartLine);

            return response()->json(['message' => 'Item removed from cart'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function clear(ClearCart $action)
    {
        try {
            $cart = $this->getCurrentCart();
            $result = $action->execute($cart);

            return response()->json([
                'message' => 'Cart cleared successfully',
                'cart' => new CartResource($result),
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function applyCoupon(Request $request, ApplyCoupon $action)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50',
        ]);

        try {
            $cart = $this->getCurrentCart();
            $result = $action->execute($cart, $request->coupon_code);

            return response()->json([
                'message' => 'Coupon applied successfully',
                'cart' => new CartResource($result),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function removeCoupon(Request $request, RemoveCoupon $action)
    {
        $request->validate([
            'discount_id' => 'nullable|exists:discounts,id',
        ]);

        try {
            $cart = $this->getCurrentCart();
            $result = $action->execute($cart, $request->discount_id);

            return response()->json([
                'message' => 'Coupon removed successfully',
                'cart' => new CartResource($result),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getCartSummary()
    {
        try {
            $cart = $this->getCurrentCart();

            return response()->json([
                'items_count' => $cart->lines()->sum('quantity'),
                'sub_total' => $cart->subTotal->formatted ?? '0.00',
                'tax_total' => $cart->taxTotal->formatted ?? '0.00',
                'total' => $cart->total->formatted ?? '0.00',
                'has_discounts' => $cart->discounts()->exists(),
                'currency' => $cart->currency->code ?? 'EUR',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to get cart summary'], 500);
        }
    }

    private function getCurrentCart(): Cart
    {
        $cartId = session('cart_id');

        if ($cartId) {
            $cart = Cart::find($cartId);
            if ($cart) {
                // Update user_id if user logged in after cart creation
                if (auth()->check() && ! $cart->user_id) {
                    $cart->update(['user_id' => auth()->id()]);
                }

                return $cart;
            }
        }

        // Create new cart if none exists
        $cart = Cart::create([
            'session_id' => session()->getId(),
            'user_id' => auth()->id(),
            'currency_id' => 1, // Default currency
        ]);

        session(['cart_id' => $cart->id]);

        return $cart;
    }
}
