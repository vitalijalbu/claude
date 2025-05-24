<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\ListingResource;
use App\Services\Api\ListingService;
use Illuminate\Http\Request;

final class ListingController extends ApiController
{
    protected ListingService $listingService;

    public function __construct(ListingService $listingService)
    {
        $this->listingService = $listingService;
    }

    public function index(Request $request)
    {
        $filters = $request->all();
        $data = $this->listingService->findAll($filters);

        return ListingResource::collection($data);
    }

    public function show(Request $request)
    {
        $data = $this->listingService->findBySlug($request->slug);
        $similarListings = $this->listingService->getSimilar($data);

        return response()->json([
            'data' => (new ListingResource($data))->toSingle($request),
            'similar_listings' => ListingResource::collection($similarListings),
        ]);
    }
}
