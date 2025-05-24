<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    // Public routes
    Route::get('/csrf-cookie', [AuthController::class, 'csrfCookie'])->middleware('web')->name('csrf-cookie');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
});

// Route::get('/auth/csrf-cookie', [AuthController::class, 'csrfCookie'])->name('csrf-cookie');
