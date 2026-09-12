<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function getActiveOffers(): JsonResponse
    {
        try {
            $offers = Offer::active()
                ->where('status', true)
                ->where(function($q) {
                    $q->where(function($query) {
                        $query->whereNotNull('discount_value')
                              ->where('discount_value', '>', 0);
                    })
                    ->orWhereIn('offer_type', ['bogo', 'buy_x_get_y', 'free_shipping']);
                })
                ->select('id', 'name', 'code', 'offer_type', 'discount_value', 'buy_qty', 'get_qty', 'min_cart_amount', 'ends_at')
                ->orderByRaw('CASE WHEN discount_value IS NULL THEN 1 ELSE 0 END')
                ->orderBy('discount_value', 'desc')
                ->limit(5)
                ->get();
            return response()->json([
                'success' => true,
                'data' => $offers,
                'message' => 'Active offers retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch offers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getStartBanner(): JsonResponse
    {
        try {
            $offer = Offer::active()
                ->where('show_at_start', true)
                ->select('id', 'name', 'code', 'banner', 'banner_button_text', 'banner_button_link')
                ->first();

            if (!$offer) {
                return response()->json([
                    'success' => true,
                    'data' => null,
                    'message' => 'No start banner found'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $offer->id,
                    'name' => $offer->name,
                    'code' => $offer->code,
                    'banner_button_text' => $offer->banner_button_text,
                    'banner_button_link' => $offer->banner_button_link,
                    'banner_url' => $offer->banner ? (\Illuminate\Support\Str::startsWith($offer->banner, 'http') ? $offer->banner : asset('storage/' . $offer->banner)) : null
                ],
                'message' => 'Start banner retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch start banner',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function validateOffer(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric|min:0'
        ]);

        try {
            $offer = Offer::active()
                ->where('status', true)
                ->where('code', $request->code)
                ->first();

            if (!$offer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired offer code'
                ], 400);
            }

            // Check min cart amount
            if ($offer->min_cart_amount && $request->subtotal < $offer->min_cart_amount) {
                return response()->json([
                    'success' => false,
                    'message' => "This offer requires a minimum cart amount of ₹{$offer->min_cart_amount}"
                ], 400);
            }

            // Check max usage
            if ($offer->max_uses && $offer->used_count >= $offer->max_uses) {
                return response()->json([
                    'success' => false,
                    'message' => 'This offer has reached its maximum usage limit'
                ], 400);
            }

            // Calculate discount
            $discountAmount = 0;
            if ($offer->offer_type === 'percentage') {
                $discountAmount = ($request->subtotal * $offer->discount_value) / 100;
                if ($offer->max_discount && $discountAmount > $offer->max_discount) {
                    $discountAmount = $offer->max_discount;
                }
            } elseif ($offer->offer_type === 'fixed') {
                $discountAmount = $offer->discount_value;
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Only percentage and fixed discounts are supported at checkout currently'
                ], 400);
            }

            // Ensure discount doesn't exceed subtotal
            if ($discountAmount > $request->subtotal) {
                $discountAmount = $request->subtotal;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'offer' => [
                        'id' => $offer->id,
                        'code' => $offer->code,
                        'name' => $offer->name,
                        'offer_type' => $offer->offer_type,
                        'discount_value' => $offer->discount_value
                    ],
                    'discount_amount' => round($discountAmount, 2)
                ],
                'message' => 'Offer applied successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to validate offer',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
