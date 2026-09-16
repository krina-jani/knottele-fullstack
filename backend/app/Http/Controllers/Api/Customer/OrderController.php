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
     * Get a list of orders for the authenticated customer or email query.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $customerId = auth('customer_api')->id();
            $email = $request->query('email');

            $query = Order::with(['items.variant.product', 'items.variant.images'])
                ->orderBy('created_at', 'desc');

            if ($customerId) {
                $query->where(function ($q) use ($customerId, $email) {
                    $q->where('customer_id', $customerId);
                    if ($email) {
                        $q->orWhere('shipping_address->email', $email);
                    }
                });
            } elseif ($email) {
                $query->where('shipping_address->email', $email);
            }

            $orders = $query->get();

            // Format for frontend consumption
            $formattedOrders = $orders->map(function ($order) {
                return [
                    'id' => (string) $order->id,
                    'orderNumber' => $order->order_number,
                    'orderDate' => $order->created_at ? $order->created_at->format('d M Y') : '',
                    'estimatedDelivery' => $order->created_at ? $order->created_at->addDays(6)->format('d M Y') : '',
                    'items' => $order->items->map(function ($item) {
                        return [
                            'id' => (string) $item->id,
                            'productId' => (string) ($item->product_variant_id ?? 'prod-1'),
                            'product' => [
                                'id' => (string) ($item->product_variant_id ?? 'prod-1'),
                                'name' => $item->product_name ?? 'Handmade Product',
                                'price' => (float) $item->unit_price,
                                'images' => [$item->variant->product->main_image ?? '/images/products/rose-bouquet.png'],
                                'category' => 'Handmade Crochet',
                                'slug' => $item->variant->product->slug ?? 'product',
                            ],
                            'quantity' => (int) $item->quantity,
                            'price' => (float) $item->unit_price,
                        ];
                    }),
                    'shippingAddress' => [
                        'fullName' => $order->shipping_address['name'] ?? 'Customer',
                        'email' => $order->shipping_address['email'] ?? '',
                        'phone' => $order->shipping_address['phone'] ?? '',
                        'addressLine1' => $order->shipping_address['address_line_1'] ?? '',
                        'addressLine2' => $order->shipping_address['address_line_2'] ?? '',
                        'city' => $order->shipping_address['city'] ?? '',
                        'state' => $order->shipping_address['state'] ?? '',
                        'pincode' => $order->shipping_address['pin_code'] ?? '',
                        'country' => 'India',
                    ],
                    'paymentMethod' => match ($order->payment_method) {
                        'cod' => 'Cash on Delivery',
                        'upi' => 'UPI',
                        'card' => 'Credit/Debit Card',
                        'netbanking' => 'Net Banking',
                        default => ucfirst($order->payment_method ?? 'UPI'),
                    },
                    'paymentStatus' => ucfirst($order->payment_status ?? 'Pending'),
                    'subtotal' => (float) $order->subtotal,
                    'shipping' => (float) $order->shipping_total,
                    'discount' => (float) $order->discount_total,
                    'total' => (float) $order->grand_total,
                    'status' => match ($order->status) {
                        'pending' => 'Order Placed',
                        'confirmed' => 'Order Confirmed',
                        'processing' => 'Crafting Your Order',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                        default => ucfirst($order->status ?? 'Order Placed'),
                    },
                    'timeline' => [
                        [
                            'status' => 'Order Placed',
                            'date' => $order->created_at ? $order->created_at->format('d M Y, h:i A') : 'Just now',
                            'description' => 'Order received and queued for artisan assignment.',
                            'completed' => true,
                            'current' => $order->status === 'pending',
                        ],
                        [
                            'status' => 'Order Confirmed',
                            'date' => $order->confirmed_at ? $order->confirmed_at->format('d M Y') : 'Upcoming',
                            'description' => 'Natural cotton yarns prepared for crafting.',
                            'completed' => in_array($order->status, ['confirmed', 'processing', 'shipped', 'delivered']),
                            'current' => $order->status === 'confirmed',
                        ],
                        [
                            'status' => 'Crafting Your Order',
                            'date' => $order->processing_at ? $order->processing_at->format('d M Y') : 'Upcoming',
                            'description' => 'Master artisan is hand-crocheting your order.',
                            'completed' => in_array($order->status, ['processing', 'shipped', 'delivered']),
                            'current' => $order->status === 'processing',
                        ],
                        [
                            'status' => 'Shipped',
                            'date' => $order->shipped_at ? $order->shipped_at->format('d M Y') : 'Upcoming',
                            'description' => 'Handed over to express courier.',
                            'completed' => in_array($order->status, ['shipped', 'delivered']),
                            'current' => $order->status === 'shipped',
                        ],
                        [
                            'status' => 'Delivered',
                            'date' => $order->delivered_at ? $order->delivered_at->format('d M Y') : 'Upcoming',
                            'description' => 'Delivered to your doorstep.',
                            'completed' => $order->status === 'delivered',
                            'current' => $order->status === 'delivered',
                        ],
                    ]
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedOrders
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
            'items.*.variant_id' => 'nullable',
            'items.*.product_id' => 'nullable',
            'items.*.product_code' => 'nullable',
            'items.*.name' => 'nullable|string',
            'items.*.unit_price' => 'nullable|numeric',
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
                $variant = null;
                
                if (!empty($item['variant_id']) && is_numeric($item['variant_id'])) {
                    $variant = ProductVariant::with('product.taxClass')->find($item['variant_id']);
                }

                if (!$variant && !empty($item['product_id'])) {
                    $code = $item['product_id'];
                    $product = \App\Models\Product::where('product_code', $code)->first();
                    if (!$product && is_numeric($code)) {
                        $product = \App\Models\Product::find($code);
                    }
                    if ($product) {
                        $variant = ProductVariant::with('product.taxClass')
                            ->where('product_id', $product->id)
                            ->first();
                    }
                }

                if (!$variant) {
                    $variant = ProductVariant::with('product.taxClass')->first();
                }

                $quantity = (int) $item['quantity'];
                $unitPrice = isset($item['unit_price']) ? (float) $item['unit_price'] : ($variant ? (float) $variant->price : 0);
                $productName = $item['name'] ?? ($variant->product->name ?? 'Handmade Product');

                $total = $unitPrice * $quantity;
                $subtotal += $total;

                $taxAmount = 0;
                if ($variant && $variant->product && $variant->product->taxClass) {
                    $taxAmount = ($total * (float) $variant->product->taxClass->total_rate) / 100;
                    $totalTaxAmount += $taxAmount;
                }

                $orderItemsData[] = [
                    'product_variant_id' => $variant ? $variant->id : 1,
                    'product_name' => $productName,
                    'sku' => $variant ? $variant->sku : 'KNT-ITEM',
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

            // Shipping rule: 99rs shipping for order subtotal < 999rs; Free (0rs) for subtotal >= 999rs or 0rs
            $effectiveSubtotal = max(0, $subtotal - $discountTotal);
            $shippingTotal = ($subtotal == 0 || $effectiveSubtotal >= 999) ? 0 : 99;

            $grandTotal = $subtotal - $discountTotal + $shippingTotal + $totalTaxAmount;

            // Associate customer ID if authenticated or matching email found
            $customerId = auth('customer_api')->id();
            $customerEmail = $request->shipping_address['email'] ?? null;
            if (!$customerId && $customerEmail) {
                $existingCust = \App\Models\Customer::where('email', $customerEmail)->first();
                if ($existingCust) {
                    $customerId = $existingCust->id;
                }
            }

            // 2. Create the Order
            $order = Order::create([
                'order_number' => 'KN' . strtoupper(uniqid()),
                'customer_id' => $customerId,
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
                    'order_id' => (string) $order->id,
                    'order_number' => $order->order_number,
                    'grand_total' => (float) $order->grand_total,
                    'subtotal' => (float) $order->subtotal,
                    'shipping_total' => (float) $order->shipping_total,
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
