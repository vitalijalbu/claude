<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\CategoryResource;
use App\Services\Api\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class CategoryController extends ApiController
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(): JsonResponse
    {

        $data = $this->categoryService->findAll();

        return response()->json(CategoryResource::collection($data), Response::HTTP_OK);
    }

    public function show(Request $request): JsonResponse
    {
        $data = $this->categoryService->findBySlug($request->slug);

        return response()->json(new CategoryResource($data), Response::HTTP_OK);
    }
}
