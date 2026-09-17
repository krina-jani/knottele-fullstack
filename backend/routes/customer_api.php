<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Customer\CustomerApiAuthController;
use App\Http\Controllers\Api\Customer\ProductController;
use App\Http\Controllers\Api\Customer\OfferController;

Route::prefix('customer')->group(function () {
    // Public routes
    Route::get('home', [\App\Http\Controllers\Api\Customer\HomeController::class, 'index']);
    Route::get('media', [\App\Http\Controllers\Api\Customer\MediaController::class, 'index']);
    Route::get('about', [\App\Http\Controllers\Api\Customer\MediaController::class, 'getAboutPage']);
    Route::get('contact', [\App\Http\Controllers\Api\Customer\MediaController::class, 'getContactPage']);
    Route::get('homepage/media', [\App\Http\Controllers\Api\Customer\MediaController::class, 'index']);
    Route::post('media/video/{id}/view', [\App\Http\Controllers\Api\Customer\MediaController::class, 'incrementView']);
    Route::post('login', [CustomerApiAuthController::class, 'login']);
    Route::post('register', [CustomerApiAuthController::class, 'register']);
    Route::post('verify-otp', [CustomerApiAuthController::class, 'verifyOtp']);
    Route::post('resend-otp', [CustomerApiAuthController::class, 'resendOtp']);
    Route::post('forgot-password', [CustomerApiAuthController::class, 'forgotPassword']);
    Route::post('reset-password', [CustomerApiAuthController::class, 'resetPassword']);

    // Public payment routes (Razorpay)
    Route::post('payment/razorpay/order', [\App\Http\Controllers\Api\Customer\RazorpayPaymentController::class, 'createOrder']);
    Route::post('payment/razorpay/verify', [\App\Http\Controllers\Api\Customer\RazorpayPaymentController::class, 'verifyPayment']);

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
    Route::post('update-profile', [CustomerApiAuthController::class, 'updateProfile']);
    Route::middleware('auth:customer_api')->group(function () {
        Route::get('profile', [CustomerApiAuthController::class, 'profile']);
        Route::post('logout', [CustomerApiAuthController::class, 'logout']);
        Route::get('orders', [\App\Http\Controllers\Api\Customer\OrderController::class, 'index']);
        Route::get('orders/{id}', [\App\Http\Controllers\Api\Customer\OrderController::class, 'show']);

        // Wishlist routes
        Route::get('wishlist', [\App\Http\Controllers\Api\Customer\WishlistController::class, 'index']);
        Route::post('wishlist/sync', [\App\Http\Controllers\Api\Customer\WishlistController::class, 'sync']);
        Route::post('wishlist/{productId}', [\App\Http\Controllers\Api\Customer\WishlistController::class, 'toggle']);
        Route::delete('wishlist/{productId}', [\App\Http\Controllers\Api\Customer\WishlistController::class, 'remove']);
        // Address routes
        Route::get('addresses', [\App\Http\Controllers\Api\Customer\AddressController::class, 'index']);
        Route::post('addresses', [\App\Http\Controllers\Api\Customer\AddressController::class, 'store']);
        Route::put('addresses/{id}', [\App\Http\Controllers\Api\Customer\AddressController::class, 'update']);
        Route::delete('addresses/{id}', [\App\Http\Controllers\Api\Customer\AddressController::class, 'destroy']);
        Route::post('addresses/{id}/default', [\App\Http\Controllers\Api\Customer\AddressController::class, 'setDefault']);
    });
});

