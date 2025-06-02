<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Profile\IndexProfiles;
use App\Actions\Profile\ShowProfile;
use App\DTO\Profile\ProfileFilterDTO;
use App\Http\Resources\Api\ProfileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends ApiController
{
    public function index(Request $request, IndexProfiles $action): JsonResponse
    {
        $filters = ProfileFilterDTO::fromRequest($request->all());
        $profiles = $action->handle($filters);

        return response()->json([
            'success' => true,
            'data' => ProfileResource::collection($profiles),
            'meta' => [
                'pagination' => [
                    'current_page' => $profiles->currentPage(),
                    'per_page' => $profiles->perPage(),
                    'total' => $profiles->total(),
                    'last_page' => $profiles->lastPage(),
                ],
            ],
        ]);
    }

    public function show(Request $request, ShowProfile $action): JsonResponse
    {
        $profile = $action->handle($request->phone_number);

        return response()->json([
            'success' => true,
            'data' => (new ProfileResource($profile))->toSingle($request),
        ]);
    }
}
