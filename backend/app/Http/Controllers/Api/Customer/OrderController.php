<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Validate cart items and return fresh prices and tax rates
     */
    public function validateCart(Request $request)
    {
        try {
            $items = $request->items ?? [];
            $validatedItems = [];
            
            foreach ($items as $item) {
                if (isset($item['variant']) && isset($item['variant']['id'])) {
                    $variant = \App\Models\ProductVariant::with('product.taxClass')->find($item['variant']['id']);
                    if ($variant && $variant->product) {
                        $item['variant']['price'] = (float) $variant->price;
                        $item['variant']['compare_price'] = $variant->compare_price ? (float) $variant->compare_price : null;
                        $item['variant']['stock_quantity'] = $variant->stock_quantity;
                        $item['tax_rate'] = $variant->product->taxClass ? (float) $variant->product->taxClass->total_rate : 0;
                        $validatedItems[] = $item;
                    }
                }
            }
            
            return $this->apiResponse(true, $validatedItems, 'Cart validated successfully');
        } catch (\Exception $e) {
            Log::error('Cart validate error: ' . $e->getMessage());
            return $this->apiResponse(false, null, 'Failed to validate cart', 500);
        }
    }

    /**
     * Get a list of orders for the authenticated customer.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $customerId = auth('customer_api')->id();
            
            if (!$customerId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.'
                ], 401);
            }

            $orders = Order::where('customer_id', $customerId)
                ->with(['items.variant.product', 'items.variant.images'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Map 'variant' to 'product_variant' for frontend compatibility
            $orders->each(function ($order) {
                $order->items->each(function ($item) {
                    $item->product_variant = $item->variant;
                    unset($item->variant);
                });
            });

            return response()->json([
                'success' => true,
                'data' => $orders
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching customer orders: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch orders.'
            ], 500);
        }
    }

    /**
     * Store a newly created order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.variant_id' => 'required|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
            
            'shipping_address' => 'required|array',
            'shipping_address.name' => 'required|string|max:255',
            'shipping_address.email' => 'required|email|max:255',
            'shipping_address.phone' => 'required|string|max:20',
            'shipping_address.address_line_1' => 'required|string',
            'shipping_address.city' => 'required|string',
            'shipping_address.pin_code' => 'required|string',

            'payment_method' => 'required|string|in:upi,card,netbanking,cod',
            'offer_code' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // 1. Calculate totals securely
            $subtotal = 0;
            $totalTaxAmount = 0;
            $orderItemsData = [];

            foreach ($request->items as $item) {
                $variant = ProductVariant::with('product.taxClass')->findOrFail($item['variant_id']);
                
                // Assuming price is standard, in a real app check discounts
                $unitPrice = $variant->price;
                $quantity = $item['quantity'];
                $total = $unitPrice * $quantity;

                $subtotal += $total;
                
                $taxAmount = 0;
                if ($variant->product && $variant->product->taxClass) {
                    $taxAmount = ($total * $variant->product->taxClass->total_rate) / 100;
                    $totalTaxAmount += $taxAmount;
                }

                $orderItemsData[] = [
                    'product_variant_id' => $variant->id,
                    'product_name' => $variant->product->name ?? 'Unknown',
                    'sku' => $variant->sku,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total' => $total,
                ];
            }

            // Check for offer code
            $discountTotal = 0;
            $appliedOfferId = null;
            if ($request->filled('offer_code')) {
                $offer = Offer::active()->where('code', $request->offer_code)->first();
                if ($offer && (!$offer->min_cart_amount || $subtotal >= $offer->min_cart_amount)) {
                    if (!$offer->max_uses || $offer->used_count < $offer->max_uses) {
                        if ($offer->offer_type === 'percentage') {
                            $discountTotal = ($subtotal * $offer->discount_value) / 100;
                            if ($offer->max_discount && $discountTotal > $offer->max_discount) {
                                $discountTotal = $offer->max_discount;
                            }
                        } elseif ($offer->offer_type === 'fixed') {
                            $discountTotal = $offer->discount_value;
                        }
                        if ($discountTotal > $subtotal) {
                            $discountTotal = $subtotal;
                        }
                        $appliedOfferId = $offer->id;
                    }
                }
            }

            // Simple shipping rule as per frontend
            $shippingTotal = (($subtotal - $discountTotal) > 799 || $subtotal == 0) ? 0 : 50;
            $grandTotal = $subtotal - $discountTotal + $shippingTotal + $totalTaxAmount;

            // Optional: get customer ID if authenticated
            $customerId = auth('customer_api')->id();

            // 2. Create the Order
            $order = Order::create([
                'order_number' => 'KN' . strtoupper(uniqid()),
                'customer_id' => $customerId, // nullable
                'payment_method' => $request->payment_method,
                'shipping_method' => 'custom',
                'currency' => 'INR',
                'status' => 'pending',
                'payment_status' => $request->payment_method === 'cod' ? 'pending' : 'paid',
                'subtotal' => $subtotal,
                'tax_total' => $totalTaxAmount,
                'shipping_total' => $shippingTotal,
                'discount_total' => $discountTotal,
                'offer_id' => $appliedOfferId,
                'grand_total' => $grandTotal,
                'shipping_address' => collect($request->shipping_address)->filter()->toArray(),
                'billing_address' => collect($request->shipping_address)->filter()->toArray(),
            ]);

            // 3. Create Order Items
            foreach ($orderItemsData as $itemData) {
                $order->items()->create($itemData);
            }

            // 4. Create initial status history
            $order->statusHistory()->create([
                'status' => 'pending',
                'notes' => 'Order placed successfully by customer.',
            ]);

            // 5. Increment offer usage if applied
            if ($appliedOfferId) {
                $offer->incrementUsage($customerId, $order->id, $discountTotal);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully',
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'grand_total' => $order->grand_total,
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error placing order: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to place order. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
