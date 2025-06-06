<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Wishlist\AddToWishlist;
use App\Actions\Wishlist\IndexWishlist;
use App\Actions\Wishlist\RemoveFromWishlist;
use App\Http\Requests\Wishlist\StoreWishlistRequest;
use App\Http\Resources\WishlistResource;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request, IndexWishlist $action)
    {
        $data = $action->execute($request);

        return WishlistResource::collection($data);
    }

    public function store(StoreWishlistRequest $request, AddToWishlist $action)
    {
        try {
            $wishlistItem = $action->execute(
                $request->validated('type'),
                $request->validated('id'),
                $request->validated('meta')
            );

            return response()->json([
                'message' => 'Item added to wishlist',
                'wishlist_item' => new WishlistResource($wishlistItem),
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy(Wishlist $wishlistItem, RemoveFromWishlist $action)
    {
        try {
            $action->execute($wishlistItem);

            return response()->json(['message' => 'Item removed from wishlist'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'type' => 'required|in:Lunar\Models\Product,Lunar\Models\ProductVariant',
            'id' => 'required|integer',
            'meta' => 'nullable|array',
        ]);

        try {
            $existing = Wishlist::where('user_id', auth()->id())
                ->where('wishlistable_type', $request->type)
                ->where('wishlistable_id', $request->id)
                ->first();

            if ($existing) {
                $existing->delete();

                return response()->json([
                    'message' => 'Item removed from wishlist',
                    'in_wishlist' => false,
                ]);
            } else {
                $wishlistItem = Wishlist::create([
                    'user_id' => auth()->id(),
                    'wishlistable_type' => $request->type,
                    'wishlistable_id' => $request->id,
                    'meta' => $request->meta,
                ]);

                return response()->json([
                    'message' => 'Item added to wishlist',
                    'in_wishlist' => true,
                    'wishlist_item' => new WishlistResource($wishlistItem),
                ], 201);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function check(Request $request)
    {
        $request->validate([
            'type' => 'required|in:Lunar\Models\Product,Lunar\Models\ProductVariant',
            'id' => 'required|integer',
        ]);

        $exists = Wishlist::where('user_id', auth()->id())
            ->where('wishlistable_type', $request->type)
            ->where('wishlistable_id', $request->id)
            ->exists();

        return response()->json(['in_wishlist' => $exists]);
    }

    public function clear()
    {
        try {
            $count = Wishlist::where('user_id', auth()->id())->count();
            Wishlist::where('user_id', auth()->id())->delete();

            return response()->json([
                'message' => "Wishlist cleared. {$count} items removed.",
                'items_removed' => $count,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to clear wishlist'], 500);
        }
    }

    public function moveToCart(Request $request)
    {
        $request->validate([
            'wishlist_item_id' => 'required|exists:wishlists,id',
            'quantity' => 'nullable|integer|min:1|max:999',
        ]);

        try {
            $wishlistItem = Wishlist::where('id', $request->wishlist_item_id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            // Only works for product variants
            if ($wishlistItem->wishlistable_type !== 'Lunar\Models\ProductVariant') {
                return response()->json(['error' => 'Can only move product variants to cart'], 400);
            }

            // Add to cart logic here
            $cartController = app(CartController::class);
            $addRequest = new Request([
                'variant_id' => $wishlistItem->wishlistable_id,
                'quantity' => $request->quantity ?? 1,
                'meta' => $wishlistItem->meta,
            ]);

            $cartResult = $cartController->addItem($addRequest, app(AddToCart::class));

            // Remove from wishlist if successfully added to cart
            if ($cartResult->getStatusCode() === 201) {
                $wishlistItem->delete();

                return response()->json([
                    'message' => 'Item moved to cart successfully',
                    'cart_result' => $cartResult->getData(),
                ]);
            }

            return response()->json(['error' => 'Failed to add item to cart'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
