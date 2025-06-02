<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Listing\IndexListings;
use App\Actions\Listing\ShowListing;
use App\DTO\Listing\ListingFilterDTO;
use App\Http\Resources\Api\ListingResource;
use App\Models\Listing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListingController extends ApiController
{
    public function index(Request $request, IndexListings $action): JsonResponse
    {
        $filters = ListingFilterDTO::fromRequest($request->all());
        $listings = $action->handle($filters);

        return response()->json([
            'success' => true,
            'data' => ListingResource::collection($listings),
            'meta' => [
                'pagination' => [
                    'current_page' => $listings->currentPage(),
                    'per_page' => $listings->perPage(),
                    'total' => $listings->total(),
                    'last_page' => $listings->lastPage(),
                ],
            ],
        ]);
    }

    public function show(Request $request, ShowListing $action): JsonResponse
    {
        $listing = $action->handle($request->slug);

        // Get similar listings
        $similarListings = Listing::where('category_id', $listing->category_id)
            ->where('id', '!=', $listing->id)
            ->with(['city', 'category', 'profile'])
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => (new ListingResource($listing))->toSingle($request),
            'similar_listings' => ListingResource::collection($similarListings),
        ]);
    }
}
