<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Category\IndexCategories;
use App\Http\Resources\Api\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CategoryController extends ApiController
{
    public function index(Request $request, IndexCategories $action): JsonResponse
    {
        $categories = $action->handle($request->all());

        return response()->json([
            'success' => true,
            'data' => CategoryResource::collection($categories),
        ]);
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new CategoryResource($category),
        ]);
    }
}
