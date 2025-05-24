<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\ProfileResource;
use App\Services\Api\ProfileService;
use Illuminate\Http\Request;

final class ProfileController extends ApiController
{
    protected ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function index(Request $request)
    {
        $data = $this->profileService->findAll($request->all());

        return ProfileResource::collection($data);
    }

    public function show(Request $request)
    {
        $data = $this->profileService->findByPhone($request->phone_number);

        return response()->json((new ProfileResource($data))->toSingle($request));
    }
}
