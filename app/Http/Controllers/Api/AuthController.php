<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserType;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Api\AuthResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    // Set CSRF cookie
    public function csrfCookie(): JsonResponse
    {
        return response()->json(['message' => 'CSRF cookie set'])
            ->cookie('XSRF-TOKEN', csrf_token(), 60, '/', null, false, true);
    }

    // User Registration
    public function register(RegisterRequest $request)
    {
        $fields = $request->validated();

        $user = User::create([
            'type' => UserType::USER,
            'first_name' => $fields['first_name'],
            'last_name' => $fields['last_name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully.',
            'data' => new AuthResource($user),
        ], 201);
    }

    // User Login
    public function login(LoginRequest $request): JsonResponse
    {
        $fields = $request->validated();
        $user = User::where('email', $fields['email'])->first();

        if (! $user || ! Hash::check($fields['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], 401);
        }

        // Autenticazione
        Auth::login($user);
        $request->session()->regenerate();

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
        ]);
    }

    // User Logout
    public function logout(Request $request): JsonResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    // Get Authenticated User
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }
}
