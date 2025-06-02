<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Auth\LoginUser;
use App\Actions\Auth\RegisterUser;
use App\DTO\Auth\LoginDTO;
use App\DTO\Auth\RegisterDTO;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Api\AuthResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class AuthController extends ApiController
{
    public function register(RegisterRequest $request, RegisterUser $action): JsonResponse
    {
        $dto = RegisterDTO::fromRequest($request->validated());
        $user = $action->handle($dto);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully.',
            'data' => new AuthResource($user),
        ], 201);
    }

    public function login(LoginRequest $request, LoginUser $action): JsonResponse
    {
        $dto = LoginDTO::fromRequest($request->validated());
        $user = $action->handle($dto);

        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => new AuthResource($user),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new AuthResource($request->user()),
        ]);
    }
}
