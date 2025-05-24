<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\TaxonomyResource;
use App\Services\Api\TaxonomyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class TaxonomyController extends ApiController
{
    protected TaxonomyService $categoryService;

    public function __construct(TaxonomyService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(): JsonResponse
    {

        $data = $this->categoryService->findAll();

        return response()->json(TaxonomyResource::collection($data), Response::HTTP_OK);
    }

    public function show(Request $request): JsonResponse
    {
        $data = $this->categoryService->findBySlug($request->slug);

        return response()->json(new TaxonomyResource($data), Response::HTTP_OK);
    }
}
