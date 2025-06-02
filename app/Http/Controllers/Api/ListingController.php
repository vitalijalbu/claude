<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Listing\DeleteListing;
use App\Actions\Listing\IndexListings;
use App\Actions\Listing\ShowListing;
use App\Actions\Listing\StoreListing;
use App\Actions\Listing\UpdateListing;
use App\DTO\Listing\ListingDTO;
use App\DTO\Listing\UpdateListingDTO;
use App\Http\Requests\Listing\StoreListingRequest;
use App\Http\Requests\Listing\UpdateListingRequest;
use App\Http\Resources\Api\ListingCollectionResource;
use App\Http\Resources\Api\ListingResource;
use App\Models\Listing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class ListingController extends ApiController
{
    public function index(Request $request, IndexListings $action): JsonResponse
    {
        $listings = $action->handle($request); 

        return response()->json([
            'success' => true,
            'data' => ListingCollectionResource::collection($listings->items()),
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

    public function show(Listing $listing, ShowListing $action): JsonResponse
    {
        $listing = $action->handle($listing->slug);

        return response()->json([
            'success' => true,
            'data' => new ListingResource($listing),
        ]);
    }

    public function store(StoreListingRequest $request, StoreListing $action): JsonResponse
    {
        $dto = ListingDTO::fromRequest($request->validated());
        $listing = $action->handle($dto);

        return response()->json([
            'success' => true,
            'message' => 'Listing created successfully',
            'data' => new ListingResource($listing),
        ], Response::HTTP_CREATED);
    }

    public function update(UpdateListingRequest $request, Listing $listing, UpdateListing $action): JsonResponse
    {
        $dto = UpdateListingDTO::fromRequest($request->validated());
        $listing = $action->handle($listing, $dto);

        return response()->json([
            'success' => true,
            'message' => 'Listing updated successfully',
            'data' => new ListingResource($listing),
        ]);
    }

    public function destroy(Listing $listing, DeleteListing $action): JsonResponse
    {
        $action->handle($listing);

        return response()->json([
            'success' => true,
            'message' => 'Listing deleted successfully',
        ], Response::HTTP_NO_CONTENT);
    }
}