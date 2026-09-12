<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Customer\CustomerApiAuthController;
use App\Http\Controllers\Api\Customer\ProductController;
use App\Http\Controllers\Api\Customer\OfferController;

Route::prefix('customer')->group(function () {
    // Public routes
    Route::get('home', [\App\Http\Controllers\Api\Customer\HomeController::class, 'index']);
    Route::post('login', [CustomerApiAuthController::class, 'login']);
    Route::post('register', [CustomerApiAuthController::class, 'register']);

    // Public offers routes
    Route::get('offers/active', [OfferController::class, 'getActiveOffers']);
    Route::get('offers/start-banner', [OfferController::class, 'getStartBanner']);
    Route::post('offers/validate', [OfferController::class, 'validateOffer']);

    // Public product and category routes (no authentication required)
    Route::get('categories', [\App\Http\Controllers\Api\Customer\CategoryController::class, 'index']);
    
    // Contact form route
    Route::get('contact/settings', [\App\Http\Controllers\Api\ContactController::class, 'settings']);
    Route::post('contact', [\App\Http\Controllers\Api\ContactController::class, 'submit']);
    
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/featured-collections', [ProductController::class, 'featuredCollections']);

        // IMPORTANT: Put slug route at the bottom to avoid conflicts
        Route::get('/{productId}/related', [ProductController::class, 'relatedProducts']);
        Route::get('/{slug}', [ProductController::class, 'show']); // Move to bottom
    });

    // Order checkout (public for guests, can be moved to protected if needed)
    Route::post('orders', [\App\Http\Controllers\Api\Customer\OrderController::class, 'store']);
    Route::post('cart/validate', [\App\Http\Controllers\Api\Customer\OrderController::class, 'validateCart']);

    // Protected routes (require authentication)
    Route::middleware('auth:customer_api')->group(function () {
        Route::get('profile', [CustomerApiAuthController::class, 'profile']);
        Route::post('logout', [CustomerApiAuthController::class, 'logout']);
        Route::get('orders', [\App\Http\Controllers\Api\Customer\OrderController::class, 'index']);
        // Add more protected routes here
    });
});
