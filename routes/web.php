<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GeoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Settings\GeneralController;
use App\Http\Controllers\Settings\SiteController;
use App\Http\Controllers\Settings\UserController;
use App\Http\Controllers\TaxonomyController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'page'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth');
Route::middleware('auth')->group(function () {
    // Home
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Media
    Route::resource('media', MediaController::class)->names([
        'index' => 'media.index',
        'show' => 'media.show',
        'store' => 'media.store',
        'update' => 'media.update',
    ]);
    // Settings
    Route::prefix('listings')->name('listings.')->group(function () {
        Route::get('/', [ListingController::class, 'index'])->name('index');
        Route::get('/{id}', [ListingController::class, 'show'])->name('show');
        Route::post('/create', [ListingController::class, 'store'])->name('store');
    });
    // Profiles
    Route::prefix('profiles')->name('profiles.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/{id}', [ProfileController::class, 'show'])->name('show');
        Route::post('/', [ProfileController::class, 'store'])->name('store');
        Route::put('/{username}', [ProfileController::class, 'update'])->name('update');
        Route::delete('/{username}', [ProfileController::class, 'destroy'])->name('destroy');
    });
    // Geo
    Route::prefix('geo')->name('geo')->group(function () {
        Route::get('/', [GeoController::class, 'index'])->name('index');
        Route::get('/{slug}', [GeoController::class, 'show'])->name('show');
        Route::post('/create', [GeoController::class, 'store'])->name('store');
        Route::patch('/create', [GeoController::class, 'update'])->name('update');
    });
    // Categories
    Route::prefix('categories')->name('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/{slug}', [CategoryController::class, 'show'])->name('show');
        Route::post('/create', [CategoryController::class, 'store'])->name('store');
        Route::patch('/create', [CategoryController::class, 'update'])->name('update');
    });
    // Taxonomies
    Route::resource('taxonomies', TaxonomyController::class)->names([
        'index' => 'taxonomies.index',
        'show' => 'taxonomies.show',
        'store' => 'taxonomies.store',
        'update' => 'taxonomies.update',
    ]);
    // Taxonomies Groups
    Route::resource('taxonomy-groups', TaxonomyController::class)->names([
        'index' => 'taxonomy-groups.index',
        'show' => 'taxonomy-groups.show',
        'store' => 'taxonomy-groups.store',
        'update' => 'taxonomy-groups.update',
    ]);
    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        // Index
        Route::get('/', [SettingController::class, 'page'])->name('index');
        // General
        Route::prefix('general')->name('general')->group(function () {
            Route::get('/', [GeneralController::class, 'page'])->name('page');
            Route::post('/', [GeneralController::class, 'show'])->name('update');
        });
        // Users
        Route::prefix('users')->name('users')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/{slug}', [UserController::class, 'show'])->name('show');
            Route::post('/create', [UserController::class, 'store'])->name('store');
            Route::patch('/create', [UserController::class, 'update'])->name('update');
        });
        // Sites
        Route::resource('sites', SiteController::class)->names([
            'index' => 'sites.index',
            'show' => 'sites.show',
            'store' => 'sites.store',
            'update' => 'sites.update',
        ]);
    });
});
