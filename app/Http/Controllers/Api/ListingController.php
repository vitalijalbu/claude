<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Listing\IndexListings;
use App\Actions\Listing\ShowListing;
use App\Http\Resources\Api\ListingCollectionResource;
use App\Http\Resources\Api\ListingResource;
use App\Models\Listing;
use App\Services\PathResolver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ListingController extends ApiController
{
    public function __construct(
        private PathResolver $pathResolver
    ) {}

    public function search(Request $request, IndexListings $action): JsonResponse
    {
        try {
            // Parse path and get page
            $pathInfo = $request->path();
            [$cleanPath, $page] = $this->pathResolver->extractPageFromPath($pathInfo);

            // Resolve path to models and filters
            $resolved = $this->pathResolver->resolvePath($cleanPath);

            // Merge filters into request
            $request->merge($resolved['filters']);
            $request->merge(['page' => $page]);

            $listings = $action->handle($request);

            return response()->json([
                'success' => true,
                'data' => ListingCollectionResource::collection($listings->items()),
                'filters' => $resolved['filters'],
                'route' => $resolved['metadata'],
                'meta' => [
                    'pagination' => [
                        'current_page' => $listings->currentPage(),
                        'per_page' => $listings->perPage(),
                        'total' => $listings->total(),
                        'last_page' => $listings->lastPage(),
                        'title' => $resolved['metadata']['title'],
                    ],
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Route not found',
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    public function index(Request $request, IndexListings $action): JsonResponse
    {
        $listings = $action->handle($request);

        return response()->json([
            'success' => true,
            'data' => ListingCollectionResource::collection($listings->items()),
            'filters' => $request->all(),
            'meta' => [
                'pagination' => [
                    'current_page' => $listings->currentPage(),
                    'per_page' => $listings->perPage(),
                    'total' => $listings->total(),
                    'last_page' => $listings->lastPage(),
                    'title' => 'search title',

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
}
