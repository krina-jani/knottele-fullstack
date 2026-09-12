<?php

use Illuminate\Support\Facades\Route;
use App\Models\Offer;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Helpers\CartHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

Route::get('/test-offers', function () {
    DB::beginTransaction();
    try {
        // 1. Setup Test Data
        $variant = ProductVariant::first();
        if (!$variant) {
            return response()->json(['error' => 'No product variants found to test with'], 404);
        }
        
        // Ensure accurate pricing for test
        $price = $variant->price;
        $qty = 2;
        $subtotal = $price * $qty;

        // 2. Create Offers
        $percentOffer = Offer::create([
            'name' => 'Test 10%',
            'code' => 'TEST10',
            'status' => true,
            'offer_type' => 'percentage',
            'discount_value' => 10,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDay(),
        ]);

        $fixedOffer = Offer::create([
            'name' => 'Test Fixed 50',
            'code' => 'FIXED50',
            'status' => true,
            'offer_type' => 'fixed',
            'discount_value' => 50,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDay(),
        ]);

        // 3. Test Cart Helper
        $cartHelper = new CartHelper();
        
        // Clear any existing cart
        $cartHelper->clearCart();
        
        // Add to cart
        $cartHelper->addToCart($variant->id, $qty); // Local cart since not logged in
        
        // 4. Verification 1: Apply Percentage
        $cartHelper->applyCoupon('TEST10');
        $cart = $cartHelper->getCart();
        
        $expectedDiscount = $subtotal * 0.10;
        $actualDiscount = $cart['discount_total'];
        
        $results = [];
        $results['percentage_test'] = [
            'subtotal' => $cart['subtotal'],
            'expected_discount' => $expectedDiscount,
            'actual_discount' => $actualDiscount,
            'passed' => abs($expectedDiscount - $actualDiscount) < 0.01
        ];

        // 5. Verification 2: Apply Fixed
        // Re-add to cart/reset (applyCoupon replaces existing)
        $cartHelper->applyCoupon('FIXED50');
        $cart = $cartHelper->getCart();
        
        $expectedFixedDiscount = 50;
        $actualFixedDiscount = $cart['discount_total'];
        
        $results['fixed_test'] = [
            'subtotal' => $cart['subtotal'],
            'expected_discount' => $expectedFixedDiscount,
            'actual_discount' => $actualFixedDiscount,
            'passed' => abs($expectedFixedDiscount - $actualFixedDiscount) < 0.01
        ];

        DB::rollBack(); // Don't keep junk data

        return response()->json([
            'success' => true,
            'results' => $results
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});
