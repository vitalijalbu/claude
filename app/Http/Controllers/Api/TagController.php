<?php

namespace App\Http\Controllers\Api;

use App\Actions\Tag\IndexTags;
use App\Actions\Tag\ShowTag;
use App\Http\Resources\Api\TagResource;
use App\Models\TagGroup;
use Illuminate\Http\JsonResponse;

final class TagController extends ApiController
{
    public function index(IndexTags $action): JsonResponse
    {

        $data = $action->handle();

        return response()->json([
            'success' => true,
            'data' => TagResource::collection($data),
        ]);
    }

    public function show(TagGroup $group, ShowTag $action): JsonResponse
    {
        $group = $action->handle($group);

        return response()->json([
            'success' => true,
            'data' => new TagResource($group),
        ]);
    }
}
