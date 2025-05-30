<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\GrabberController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Lunar\Models\Product;


// Products
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{product}', [ProductController::class, 'show'])->name('show');
});
// Collections
Route::prefix('collections')->name('collections.')->group(function () {
    Route::get('/', [CollectionController::class, 'index'])->name('index');
    Route::get('/{collection}', [CollectionController::class, 'show'])->name('show');
});
// Brands
Route::prefix('brands')->name('brands.')->group(function () {
    Route::get('/', [BrandController::class, 'index'])->name('index');
    Route::get('/{brand}', [BrandController::class, 'show'])->name('show');
});

// Cart
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'show']);
    Route::post('/', [CartController::class, 'store']);
    Route::post('/items', [CartController::class, 'addItem']);
    Route::put('/items/{cartLine}', [CartController::class, 'updateItem']);
    Route::delete('/items/{cartLine}', [CartController::class, 'removeItem']);
    Route::delete('/clear', [CartController::class, 'clear']);
    Route::post('/coupon', [CartController::class, 'applyCoupon']);
    Route::delete('/coupon', [CartController::class, 'removeCoupon']);
});

// Discounts
Route::get('discounts', [DiscountController::class, 'index']);
Route::get('discounts/{discount}', [DiscountController::class, 'show']);
Route::post('discounts/validate-coupon', [DiscountController::class, 'validateCoupon']);

// Grabber (External API)
Route::prefix('grabber')->group(function () {
    Route::post('/process', [GrabberController::class, 'process']);
    Route::post('/batch-process', [GrabberController::class, 'batchProcess']);
    Route::get('/logs', [GrabberController::class, 'logs']);
});
