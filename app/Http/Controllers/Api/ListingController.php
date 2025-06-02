<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Profile\IndexProfiles;
use App\Actions\Profile\StoreProfile;
use App\Actions\Profile\UpdateProfile;
use App\DTO\Profile\ProfileDTO;
use App\DTO\Profile\ProfileFilterDTO;
use App\DTO\Profile\UpdateProfileDTO;
use App\Http\Requests\Profile\StoreProfileRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Resources\Api\ProfileCollectionResource;
use App\Http\Resources\Api\ProfileSingleResource;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class ProfileController extends ApiController
{
    public function index(Request $request, IndexProfiles $action): JsonResponse
    {
        $filters = ProfileFilterDTO::fromRequest($request->all());
        $profiles = $action->handle($filters);

        return response()->json([
            'success' => true,
            'data' => ProfileCollectionResource::collection($profiles),
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

    public function store(StoreProfileRequest $request, StoreProfile $action): JsonResponse
    {
        $dto = ProfileDTO::fromRequest($request->validated());
        $profile = $action->handle($dto);

        return response()->json([
            'success' => true,
            'message' => 'Profile created successfully',
            'data' => new ProfileSingleResource($profile),
        ], Response::HTTP_CREATED);
    }

    public function show(Profile $profile): JsonResponse
    {
        $profile->load([
            'listings' => function ($query) {
                $query->latest()->take(5);
            },
            'taxonomies.group',
            'city',
            'province',
            'category',
        ])->loadCount('listings');

        return response()->json([
            'success' => true,
            'data' => new ProfileSingleResource($profile),
        ]);
    }

    // public function update(UpdateProfileRequest $request, Profile $profile, UpdateProfile $action): JsonResponse
    // {
    //     $dto = UpdateProfileDTO::fromRequest($request->validated());
    //     $profile = $action->handle($profile, $dto);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Profile updated successfully',
    //         'data' => new ProfileSingleResource($profile),
    //     ]);
    // }

    // public function destroy(Profile $profile): JsonResponse
    // {
    //     $profile->delete();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Profile deleted successfully',
    //     ], Response::HTTP_NO_CONTENT);
    // }
}
