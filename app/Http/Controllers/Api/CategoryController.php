<?php

namespace App\Http\Controllers\Api;

use App\Actions\Category\IndexCategories;
use App\Actions\Category\ShowCategory;
use App\DTO\Category\CategoryFilterDTO;
use App\Http\Resources\Api\CategoryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CategoryController extends ApiController
{
    public function index(Request $request, IndexCategories $action): JsonResponse
    {
        $filters = CategoryFilterDTO::fromRequest($request->all());
        $categories = $action->handle($filters);

        return response()->json([
            'success' => true,
            'data' => CategoryResource::collection($categories),
            'meta' => [
                'pagination' => [
                    'current_page' => $categories->currentPage(),
                    'per_page' => $categories->perPage(),
                    'total' => $categories->total(),
                    'last_page' => $categories->lastPage(),
                ],
            ],
        ]);
    }

    public function show(Request $request, ShowCategory $action): JsonResponse
    {
        $category = $action->handle($request->slug);

        return response()->json([
            'success' => true,
            'data' => new CategoryResource($category),
        ]);
    }
}
