<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ExploreController;
use App\Http\Controllers\Api\GeoController;
use App\Http\Controllers\Api\GrabberController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ListingController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\TaxonomyController;
use Illuminate\Support\Facades\Route;

// Auth API
require __DIR__.'/auth.php';

Route::post('/grabber', [GrabberController::class, 'store'])->name('grabber');
Route::get('/s3/{phoneNumber}', [GrabberController::class, 's3_clone'])->name('s3.clone');
Route::get('/s3', [GrabberController::class, 's3'])->name('s3');
Route::get('/explore', [ExploreController::class, 'index'])->name('explore.index');

// Geo API
Route::prefix('geo')->name('geo.')->group(function () {
    Route::get('/countries', [GeoController::class, 'countries'])->name('countries.index');
    Route::get('/regions', [GeoController::class, 'regions'])->name('regions.index');
    Route::get('/provinces', [GeoController::class, 'provinces'])->name('provinces.index');
    Route::get('/cities', [GeoController::class, 'cities'])->name('cities.index');
    Route::get('/nationalities', [GeoController::class, 'nationalities'])->name('nationalities.index');
});

Route::get('/home', [HomeController::class, 'index'])->name('explore.api');
// Settings
Route::prefix('listings')->name('api.listings.')->group(function () {
    Route::get('/', [ListingController::class, 'index'])->name('index');
    Route::get('/{listing:slug}', [ListingController::class, 'show'])->name('show');
});
// Categories
Route::prefix('categories')->name('api.categories.')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
});
// Taxonomies
Route::prefix('taxonomies')->name('api.taxonomies.')->group(function () {
    Route::get('/', [TaxonomyController::class, 'index'])->name('index');
    Route::get('/{taxonomy}', [TaxonomyController::class, 'show'])->name('show');
});
// Profiles
Route::prefix('profiles')->name('api.profiles.')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::get('/{profile:phone_number}', [ProfileController::class, 'show'])->name('show');
});

// Account routes
Route::prefix('account')->name('api.account.')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/me', [AuthController::class, 'me'])->name('me');
    Route::post('/me', [AuthController::class, 'update'])->name('update');
});
