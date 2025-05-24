<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AuthController extends Controller
{
    // Fetch the login page
    public function page()
    {
        return Inertia::render('auth/login');
    }

    // Set CSRF cookie
    public function csrfCookie()
    {
        return response()->json(['message' => 'CSRF cookie set'])
            ->cookie('XSRF-TOKEN', csrf_token(), 60, '/', null, false, true);
    }

    // User Login
    public function login(LoginRequest $request)
    {
        $fields = $request->validated();
        $user = User::where('email', $fields['email'])->first();

        if (! $user) {
            // Return error message using Inertia render
            return Inertia::render('auth/login', [
                'errors' => 'Invalid credentials. No matching user found with this email.',
                'email' => $fields['email'],
            ]);
        }

        if (! Hash::check($fields['password'], $user->password)) {
            // Return error message using Inertia render
            return Inertia::render('auth/login', [
                'errors' => 'Invalid credentials. The provided password is incorrect.',
                'email' => $fields['email'],
            ]);
        }

        // Authenticate the user
        Auth::login($user);

        // Redirect after successful login (no error, so we don't need to pass error or email)
        return redirect()->route('home');
    }

    // User Logout
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Inertia::render('auth/login', [
            'message' => 'Logged out successfully.',
        ]);
    }

    // Get Authenticated User
    public function me(Request $request)
    {
        return Inertia::render('auth/me', [
            'user' => $request->user(),
        ]);
    }
}
