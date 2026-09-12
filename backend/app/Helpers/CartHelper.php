<?php
// app/Helpers/CartHelper.php
namespace App\Helpers;

use App\Models\ProductVariant;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class CartHelper
{
    protected $cartKey = 'guest_cart';
    protected $cartExpiry = 43200; // 30 days in minutes

    public function addToCart($variantId, $quantity = 1, $attributes = [])
    {
        if (Auth::guard('customer')->check()) {
            return $this->addToDatabaseCart($variantId, $quantity, $attributes );
        }

        return $this->addToLocalCart($variantId, $quantity, $attributes);
    }

    private function addToDatabaseCart($variantId, $quantity, $attributes)
    {
        $customer = Auth::guard('customer')->user();

        // Check if cart exists for customer
        $cart = Cart::firstOrCreate(
            ['customer_id' => $customer->id, 'status' => 'active'],
            ['session_id' => session()->getId()]
        );

        // Get variant
        $variant = ProductVariant::findOrFail($variantId);

        // Check stock
        if ($variant->stock_quantity < $quantity) {
            throw new \Exception('Insufficient stock available');
        }

        // Check if item already exists in cart
        // Since there's a unique constraint on (cart_id, product_variant_id),
        // we should try to find by variant_id first.
        $existingItem = $cart->items()
            ->where('product_variant_id', $variantId)
            ->first();

        // If you want to differentiate by attributes, you would need to change the DB constraint.
        // For now, if same variant is added, we merge.

        if ($existingItem) {
            // Update quantity
            $newQuantity = $existingItem->quantity + $quantity;

            // Check stock again with new quantity
            if ($variant->stock_quantity < $newQuantity) {
                throw new \Exception('Insufficient stock available');
            }

            $existingItem->update([
                'quantity' => $newQuantity,
                'total' => $variant->price * $newQuantity
            ]);
        } else {
            // Create new cart item
            $cart->items()->create([
                'product_variant_id' => $variantId,
                'quantity' => $quantity,
                'unit_price' => $variant->price,
                'total' => $variant->price * $quantity,
                'attributes' => json_encode($attributes)
            ]);
        }

        $this->recalculateCartTotals($cart);

        return [
            'success' => true,
            'message' => 'Product added to cart',
            'cart_count' => $this->getCartCount(),
            'cart' => $this->formatCartResponse($cart)
        ];
    }

    private function addToLocalCart($variantId, $quantity, $attributes)
    {
        $cart = $this->getLocalCart();

        // Get variant with product and tax info
        $variant = ProductVariant::with([
            'product.taxClass.rates',
            'images',
            'primaryImage.media'
        ])->findOrFail($variantId);

        if (!$variant) {
            throw new \Exception('Product variant not found');
        }

        // Check stock
        if ($variant->stock_quantity < $quantity) {
            throw new \Exception('Insufficient stock available');
        }

        // Prepare item tax details
        $taxRates = [];
        if ($variant->product && $variant->product->taxClass) {
            foreach ($variant->product->taxClass->rates as $rate) {
                if ($rate->is_active) {
                    $taxRates[] = [
                        'name' => $rate->name,
                        'rate' => (float)$rate->rate
                    ];
                }
            }
        }

        // Check if item already exists
        $itemExists = false;
        $normalizedAttributes = $this->normalizeAttributes($attributes);
        
        foreach ($cart['items'] as &$item) {
            $itemAttributes = $this->normalizeAttributes($item['attributes'] ?? []);
            if (
                $item['variant_id'] == $variantId &&
                $itemAttributes === $normalizedAttributes
            ) {
                $item['quantity'] += $quantity;
                $item['total'] = $item['unit_price'] * $item['quantity'];
                $item['tax_rates'] = $taxRates; // Update tax rates just in case
                $itemExists = true;
                break;
            }
        }

        if (!$itemExists) {
            $cart['items'][] = [
                'id' => uniqid('cart_'),
                'variant_id' => $variantId,
                'product_id' => $variant->product_id,
                'slug' => $variant->product->slug,
                'product_name' => $variant->product->name,
                'name' => $variant->product->name,
                'sku' => $variant->sku,
                'unit_price' => $variant->price,
                'price' => $variant->price,
                'quantity' => $quantity,
                'total' => $variant->price * $quantity,
                'attributes' => $attributes,
                'attributes_text' => $this->formatAttributesText($attributes),
                'image' => $this->formatImageUrl($variant->display_image),
                'stock_quantity' => $variant->stock_quantity,
                'tax_rates' => $taxRates
            ];
        }

        $cart = $this->recalculateLocalCartTotals($cart);
        $this->saveLocalCart($cart);

        return [
            'success' => true,
            'message' => 'Product added to cart',
            'cart_count' => $cart['items_count'],
            'cart' => $cart
        ];
    }

    public function getCart()
    {
        if (Auth::guard('customer')->check()) {
            return $this->getDatabaseCart();
        }

        return $this->getLocalCart();
    }

    private function getDatabaseCart()
    {
        $customer = Auth::guard('customer')->user();

        $cart = Cart::with([
            'items.variant.product.taxClass.rates',
            'items.variant.product',
            'items.variant.images',
            'items.variant.primaryImage.media'
        ])
            ->where('customer_id', $customer->id)
            ->where('status', 'active')
            ->first();


        if (!$cart) {
            return $this->createEmptyCartResponse();
        }

        // Recalculate if offer is present to ensure it's still valid (expiry/status)
        if ($cart->offer_id) {
            $this->recalculateCartTotals($cart);
            $cart = $cart->fresh();
        }

        // Auto-apply eligible offers (DISABLED: User must select manually)
        // $this->applyAutoOffers($cart);

        return $this->formatCartResponse($cart);
    }

    private function getLocalCart()
    {
        $cartJson = Cookie::get($this->cartKey);

        if ($cartJson) {
            $cart = json_decode($cartJson, true);
        }

        if (empty($cart) || !is_array($cart)) {
            $cart = $this->createEmptyLocalCart();
        } else {
            // Normalize items to ensure all keys exist (for backward compatibility)
            if (isset($cart['items']) && is_array($cart['items'])) {
                foreach ($cart['items'] as &$item) {
                    if (!isset($item['name']) && isset($item['product_name'])) {
                        $item['name'] = $item['product_name'];
                    }
                    if (!isset($item['slug'])) {
                        $item['slug'] = \Illuminate\Support\Str::slug($item['product_name'] ?? 'product');
                    }
                    if (!isset($item['price']) && isset($item['unit_price'])) {
                        $item['price'] = $item['unit_price'];
                    }
                    if (isset($item['image'])) {
                       $item['image'] = $this->formatImageUrl($item['image']);
                    }
                    if (!isset($item['attributes_text'])) {
                        $item['attributes_text'] = $this->formatAttributesText($item['attributes'] ?? []);
                    }
                }
            }
            
            // Recalculate if offer is present to ensure it's still valid
            if (isset($cart['offer_id'])) {
                $cart = $this->recalculateLocalCartTotals($cart);
                $this->saveLocalCart($cart);
            }
        }

        return $cart;
    }

    public function formatCartResponse($cart)
    {
        $offer = null;
        if (isset($cart->offer_id) && $cart->offer_id) {
            $offer = \App\Models\Offer::find($cart->offer_id);
        }

        // Calculate tax breakdown for display if database cart
    $taxBreakdown = [];
    if (isset($cart->items)) {
        $subtotal = $cart->subtotal;
        $discountTotal = $cart->discount_total ?? 0;
        $effectiveSubtotalRatio = $subtotal > 0 ? ($subtotal - $discountTotal) / $subtotal : 0;

        foreach ($cart->items as $item) {
            $itemTotal = $item->total * $effectiveSubtotalRatio;
            if ($item->variant && $item->variant->product && $item->variant->product->taxClass) {
                foreach ($item->variant->product->taxClass->rates as $rate) {
                    if ($rate->is_active) {
                        $amount = $itemTotal * ($rate->rate / 100);
                        $label = $rate->name;
                        if (!isset($taxBreakdown[$label])) {
                            $taxBreakdown[$label] = [
                                'name' => $label,
                                'rate' => (float)$rate->rate,
                                'amount' => 0
                            ];
                        }
                        $taxBreakdown[$label]['amount'] += $amount;
                    }
                }
            }
        }
    }

    return [
        'id' => $cart->id ?? null,
        'session_id' => $cart->session_id ?? session()->getId(),
        'items' => isset($cart->items) ? $cart->items->map(function ($item) {
            if (!$item->variant || !$item->variant->product) {
                return null;
            }
            return [
                'id' => $item->id,
                'variant_id' => $item->product_variant_id,
                'product_id' => $item->variant->product_id,
                'slug' => $item->variant->product->slug,
                'product_name' => $item->variant->product->name,
                'name' => $item->variant->product->name,
                'sku' => $item->variant->sku,
                'stock_quantity' => $item->variant->stock_quantity,
                'unit_price' => (float) $item->unit_price,
                'price' => (float) $item->unit_price,
                'quantity' => $item->quantity,
                'total' => (float) $item->total,
                'attributes' => json_decode($item->attributes, true) ?? [],
                'attributes_text' => $this->formatAttributesText(json_decode($item->attributes, true) ?? []),
                'image' => $this->formatImageUrl($item->variant->display_image),
                'cod_available' => $item->variant->product->cod_available ?? true, // Pass COD availability
            ];
        })->filter()->values()->toArray() : ($cart['items'] ?? []),
        'items_count' => isset($cart->items) ? (int) $cart->items->sum('quantity') : ($cart['items_count'] ?? 0),
        'subtotal' => (float) ($cart->subtotal ?? $cart['subtotal'] ?? 0),
        'tax_total' => (float) ($cart->tax_total ?? $cart['tax_total'] ?? 0),
        'tax_breakdown' => isset($cart->tax_breakdown) ? $cart->tax_breakdown : (isset($taxBreakdown) ? array_values($taxBreakdown) : ($cart['tax_breakdown'] ?? [])),
            'shipping_total' => (float) ($cart->shipping_total ?? $cart['shipping_total'] ?? 0),
            'discount_total' => (float) ($cart->discount_total ?? $cart['discount_total'] ?? 0),
            'discount_breakdown' => $this->getDiscountBreakdown($cart, $offer, (float) ($cart->discount_total ?? $cart['discount_total'] ?? 0)),
            'grand_total' => (float) ($cart->grand_total ?? $cart['grand_total'] ?? 0),
            'offer' => $offer ? [
                'id' => $offer->id,
                'code' => $offer->code,
                'name' => $offer->name,
                'type' => $offer->offer_type
            ] : null,
            'is_logged_in' => Auth::guard('customer')->check()
        ];
    }

    private function getDiscountBreakdown($cart, $offer, $discountTotal)
    {
        $breakdown = [];
        $subtotal = (float) ($cart->subtotal ?? $cart['subtotal'] ?? 0);

        if ($discountTotal > 0) {
            // Check if it's an offer
            if ($offer) {
                if ($offer->is_auto_apply) {
                    $label = 'Auto Applied: ' . $offer->name;
                    $type = 'auto';
                } else {
                    $label = 'Promo Code (' . $offer->code . ')';
                    $type = 'coupon';
                }

                if ($offer->offer_type == 'percentage') {
                    $label .= ' - ' . round($offer->discount_value) . '% Off';
                } elseif ($offer->offer_type == 'fixed') {
                    $label .= ' - ₹' . round($offer->discount_value) . ' Off';
                }
                
                $breakdown[] = [
                    'label' => $label,
                    'amount' => $discountTotal,
                    'type' => $type
                ];
            } 
            // Check if it's the automatic 5% discount
            elseif ($subtotal > 999 && abs($discountTotal - ($subtotal * 0.05)) < 1.0) {
                 $breakdown[] = [
                    'label' => 'Bulk Order Discount (5% off for orders > ₹999)',
                    'amount' => $discountTotal,
                    'type' => 'bulk'
                ];
            }
            // Fallback
            else {
                 $breakdown[] = [
                    'label' => 'Discount',
                    'amount' => $discountTotal,
                    'type' => 'other'
                ];
            }
        }
        return $breakdown;
    }

    // Public method for recalculating cart totals
    public function recalculateCartTotals($cart)
    {
        if (is_array($cart)) {
            return $this->recalculateLocalCartTotals($cart);
        }

        // Database cart
        $cart->load('items.variant.product.taxClass.rates');
        
        $subtotal = $cart->items()->sum('total');
        $discountTotal = 0;
        $offer = null;

        // Apply offer discount if exists and is still valid
        if ($cart->offer_id) {
            $offer = \App\Models\Offer::find($cart->offer_id);
            
            // Validate offer still exists and is active
            if ($offer && $offer->status && $offer->isActive()) {
                $discountTotal = $this->calculateDiscount($offer, $subtotal, $cart->items);
            } else {
                // Offer deleted, deactivated, or expired - remove from cart
                $cart->offer_id = null;
                $cart->save();
            }
        }

        // Apply automatic 5% discount for orders above ₹999 
        // if no other discount is applied or if we want it to be stackable/priority
        // NOTE: If changing this logic, update getDiscountBreakdown logic too
        if ($discountTotal == 0 && $subtotal > 999) {
            $discountTotal = $subtotal * 0.05;
        }

        // Calculate tax based on items (Tax Inclusive)
        $taxTotal = 0;
        $taxBreakdown = [];
        $effectiveSubtotalRatio = $subtotal > 0 ? ($subtotal - $discountTotal) / $subtotal : 0;
        
        foreach ($cart->items as $item) {
            $itemTotal = $item->total * $effectiveSubtotalRatio;
            
            if ($item->variant->product && $item->variant->product->taxClass) {
                foreach ($item->variant->product->taxClass->rates as $rate) {
                    if ($rate->is_active) {
                        // Back-calculate tax from inclusive amount
                        // Tax = Amount - (Amount / (1 + Rate/100))
                        $taxAmount = $itemTotal - ($itemTotal / (1 + ($rate->rate / 100)));
                        $taxTotal += $taxAmount;
                        
                        $label = $rate->name;
                        if (!isset($taxBreakdown[$label])) {
                            $taxBreakdown[$label] = [
                                'name' => $label,
                                'rate' => (float)$rate->rate,
                                'amount' => 0
                            ];
                        }
                        $taxBreakdown[$label]['amount'] += $taxAmount;
                    }
                }
            }
        }

        // Calculate shipping
        $shippingTotal = $this->calculateShipping($subtotal - $discountTotal);

        // Grand Total = Subtotal (Inclusive) - Discount + Shipping
        // Tax is already in Subtotal, so we don't add it again.
        $grandTotal = $subtotal - $discountTotal + $shippingTotal;

        $cart->update([
            'subtotal' => $subtotal,
            'discount_total' => $discountTotal,
            'tax_total' => $taxTotal,
            'shipping_total' => $shippingTotal,
            'grand_total' => $grandTotal
        ]);
    }

    private function calculateShipping($subtotal)
    {
        // Shipping is calculated dynamically at checkout based on location
        return 0;
    }

    private function recalculateLocalCartTotals($cart)
    {
        $subtotal = 0;
        $itemsCount = 0;

        foreach ($cart['items'] as $item) {
            $subtotal += $item['total'];
            $itemsCount += $item['quantity'];
        }

        $discountTotal = 0;
        if (isset($cart['offer_id'])) {
            $offer = \App\Models\Offer::find($cart['offer_id']);
            
            // Validate offer still exists, is active, and not expired
            if ($offer && $offer->status && $offer->isActive()) {
                $discountTotal = $this->calculateDiscount($offer, $subtotal, collect($cart['items']));
            } else {
                // Offer deleted, deactivated, or expired - remove from cart
                unset($cart['offer_id'], $cart['offer_code'], $cart['offer_type'], $cart['discount_value']);
            }
        }

        // Apply automatic 5% discount for orders above ₹999
        if ($discountTotal == 0 && $subtotal > 999) {
            $discountTotal = $subtotal * 0.05;
        }

        $taxTotal = 0;
        $taxBreakdown = [];
        $effectiveSubtotalRatio = $subtotal > 0 ? ($subtotal - $discountTotal) / $subtotal : 0;

        foreach ($cart['items'] as $item) {
            $itemTotal = $item['total'] * $effectiveSubtotalRatio;
            $rates = $item['tax_rates'] ?? [];
            
            foreach ($rates as $rate) {
                // Back-calculate tax from inclusive amount
                // Tax = Amount - (Amount / (1 + Rate/100))
                $taxAmount = $itemTotal - ($itemTotal / (1 + ($rate['rate'] / 100)));
                $taxTotal += $taxAmount;
                
                $label = $rate['name'];
                if (!isset($taxBreakdown[$label])) {
                    $taxBreakdown[$label] = [
                        'name' => $label,
                        'rate' => $rate['rate'],
                        'amount' => 0
                    ];
                }
                $taxBreakdown[$label]['amount'] += $taxAmount;
            }
        }

        $shippingTotal = $this->calculateShipping($subtotal - $discountTotal);

        $cart['subtotal'] = round($subtotal, 2);
        $cart['discount_total'] = round($discountTotal, 2);
        $cart['tax_total'] = round($taxTotal, 2);
        $cart['tax_breakdown'] = array_values($taxBreakdown);
        $cart['shipping_total'] = round($shippingTotal, 2);
        // Grand Total = Subtotal (Inclusive) - Discount + Shipping
        $cart['grand_total'] = round($subtotal - $discountTotal + $shippingTotal, 2);
        $cart['total'] = $cart['grand_total'];
        $cart['items_count'] = $itemsCount;

        return $cart;
    }

    private function calculateDiscount($offer, $subtotal, $items)
    {
        $discount = 0;

        // Validate minimum cart amount again
        if ($offer->min_cart_amount && $subtotal < $offer->min_cart_amount) {
            return 0;
        }

        // Check if offer is exclusive (product-specific)
        $eligibleTotal = $subtotal;
        if ($offer->is_exclusive) {
            // Get variant IDs that this offer applies to
            $offerVariantIds = $offer->variants()->pluck('id')->toArray();
            
            if (empty($offerVariantIds)) {
                // No specific products selected, can't apply exclusive discount
                return 0;
            }

            // Calculate total only for eligible products
            $eligibleTotal = 0;
            foreach ($items as $item) {
                $variantId = isset($item->product_variant_id) ? $item->product_variant_id : ($item['variant_id'] ?? null);
                if (in_array($variantId, $offerVariantIds)) {
                    $itemTotal = isset($item->total) ? $item->total : ($item['price'] * $item['quantity']);
                    $eligibleTotal += $itemTotal;
                }
            }

            if ($eligibleTotal == 0) {
                // No eligible products in cart
                return 0;
            }
        }

        // Calculate discount based on offer type (only percentage and fixed supported)
        switch ($offer->offer_type) {
            case 'percentage':
                $discount = $eligibleTotal * ($offer->discount_value / 100);
                if ($offer->max_discount && $discount > $offer->max_discount) {
                    $discount = $offer->max_discount;
                }
                break;

            case 'fixed':
                $discount = $offer->discount_value;
                // For exclusive offers, don't let discount exceed eligible total
                if ($offer->is_exclusive) {
                    $discount = min($discount, $eligibleTotal);
                }
                break;

            default:
                // Only percentage and fixed are supported
                $discount = 0;
        }

        return min($discount, $subtotal);
    }

    public function saveLocalCart($cart)
    {
        $cart['updated_at'] = now()->timestamp;
        $cartJson = json_encode($cart);

        Cookie::queue(
            Cookie::make($this->cartKey, $cartJson, $this->cartExpiry, null, null, false, false)
        );

        return $cart;
    }

    private function createEmptyLocalCart()
    {
        return [
            'session_id' => session()->getId(),
            'items' => [],
            'items_count' => 0,
            'subtotal' => 0,
            'tax_total' => 0,
            'shipping_total' => 0,
            'grand_total' => 0,
            'created_at' => now()->timestamp,
            'updated_at' => now()->timestamp
        ];
    }

    public function createEmptyCartResponse()
    {
        return [
            'items' => [],
            'items_count' => 0,
            'subtotal' => 0,
            'tax_total' => 0,
            'shipping_total' => 0,
            'grand_total' => 0
        ];
    }

    public function syncCart()
    {
        if (!Auth::guard('customer')->check()) {
            return false;
        }

        $localCart = $this->getLocalCart();

        if (empty($localCart['items'])) {
            return true;
        }

        try {
            $customer = Auth::guard('customer')->user();

            // Get or create database cart
            $dbCart = Cart::firstOrCreate(
                ['customer_id' => $customer->id, 'status' => 'active'],
                ['session_id' => session()->getId()]
            );

            // Add local cart items to database
            foreach ($localCart['items'] as $item) {
                $variant = ProductVariant::find($item['variant_id']);

                if ($variant) {
                    // Check if item already exists with matching attributes
                    $existingItem = $dbCart->items()
                        ->where('product_variant_id', $item['variant_id'])
                        ->where('attributes', json_encode($item['attributes'] ?? []))
                        ->first();

                    if ($existingItem) {
                        $existingItem->update([
                            'quantity' => $existingItem->quantity + $item['quantity'],
                            'total' => $existingItem->unit_price * ($existingItem->quantity + $item['quantity'])
                        ]);
                    } else {
                        $dbCart->items()->create([
                            'product_variant_id' => $item['variant_id'],
                            'quantity' => $item['quantity'],
                            'unit_price' => $item['unit_price'],
                            'total' => $item['total'],
                            'attributes' => json_encode($item['attributes'])
                        ]);
                    }
                }
            }

            // Recalculate totals
            $this->recalculateCartTotals($dbCart);

            // Clear local cart
            $this->clearLocalCart();

            return true;

        } catch (\Exception $e) {
            \Log::error('Cart sync failed: ' . $e->getMessage());
            return false;
        }
    }

    public function clearLocalCart()
    {
        Cookie::queue(Cookie::forget($this->cartKey));
    }

    public function getCartCount()
    {
        $cart = $this->getCart();
        return $cart['items_count'] ?? 0;
    }

    // New methods for cart operations
    public function updateItemQuantity($itemId, $quantity)
    {
        if (Auth::guard('customer')->check()) {
            return $this->updateDatabaseItemQuantity($itemId, $quantity);
        }

        return $this->updateLocalItemQuantity($itemId, $quantity);
    }

    private function updateDatabaseItemQuantity($itemId, $quantity)
    {
        $customer = Auth::guard('customer')->user();

        $cartItem = CartItem::with('variant', 'cart')
            ->whereHas('cart', function ($query) use ($customer) {
                $query->where('customer_id', $customer->id)
                      ->where('status', 'active');
            })
            ->find($itemId);

        if (!$cartItem) {
            throw new \Exception('Item not found in cart');
        }

        // Check stock
        if ($cartItem->variant->stock_quantity < $quantity) {
            throw new \Exception('Only ' . $cartItem->variant->stock_quantity . ' items available');
        }

        $cartItem->quantity = $quantity;
        $cartItem->total = $cartItem->unit_price * $quantity;
        $cartItem->save();

        $this->recalculateCartTotals($cartItem->cart);

        return $this->formatCartResponse($cartItem->cart);
    }

    private function updateLocalItemQuantity($itemId, $quantity)
    {
        $cart = $this->getLocalCart();

        $itemFound = false;
        foreach ($cart['items'] as &$item) {
            if ($item['id'] == $itemId) {
                // Check stock
                $variant = ProductVariant::find($item['variant_id']);
                if (!$variant || $variant->stock_quantity < $quantity) {
                    $stock = $variant ? $variant->stock_quantity : 0;
                    throw new \Exception('Only ' . $stock . ' items available');
                }

                $item['quantity'] = $quantity;
                $item['total'] = $item['unit_price'] * $quantity;
                $itemFound = true;
                break;
            }
        }

        if (!$itemFound) {
            throw new \Exception('Item not found in cart');
        }

        $cart = $this->recalculateLocalCartTotals($cart);
        $this->saveLocalCart($cart);

        return $cart;
    }

    public function removeItem($itemId)
    {
        if (Auth::guard('customer')->check()) {
            return $this->removeDatabaseItem($itemId);
        }

        return $this->removeLocalItem($itemId);
    }

    private function removeDatabaseItem($itemId)
    {
        $customer = Auth::guard('customer')->user();

        $cartItem = CartItem::with('cart')
            ->whereHas('cart', function ($query) use ($customer) {
                $query->where('customer_id', $customer->id)
                      ->where('status', 'active');
            })
            ->find($itemId);

        if (!$cartItem) {
            throw new \Exception('Item not found in cart');
        }

        $cart = $cartItem->cart;
        $cartItem->delete();

        $this->recalculateCartTotals($cart);

        return $this->formatCartResponse($cart);
    }

    private function removeLocalItem($itemId)
    {
        $cart = $this->getLocalCart();

        $newItems = [];
        foreach ($cart['items'] as $item) {
            if ($item['id'] != $itemId) {
                $newItems[] = $item;
            }
        }

        $cart['items'] = $newItems;

        if (empty($cart['items'])) {
            $cart = $this->createEmptyLocalCart();
        } else {
            $cart = $this->recalculateLocalCartTotals($cart);
        }

        $this->saveLocalCart($cart);

        return $cart;
    }

    public function clearCart()
    {
        if (Auth::guard('customer')->check()) {
            return $this->clearDatabaseCart();
        }

        return $this->clearLocalCart();
    }

    private function clearDatabaseCart()
    {
        $customer = Auth::guard('customer')->user();

        $cart = Cart::where('customer_id', $customer->id)
            ->where('status', 'active')
            ->first();

        if ($cart) {
            $cart->items()->delete();
            $this->recalculateCartTotals($cart);
        }

        return $this->createEmptyCartResponse();
    }

    public function applyCoupon($code)
    {
        $offer = \App\Models\Offer::where('code', $code)
            ->where('status', true)
            ->first();

        if (!$offer) {
            throw new \Exception('Invalid coupon code');
        }

        if (!$offer->isActive()) {
            throw new \Exception('This coupon has expired');
        }

        $customerId = Auth::guard('customer')->id();
        if (!$offer->canApply($customerId)) {
            throw new \Exception('This coupon cannot be applied');
        }

        if (Auth::guard('customer')->check()) {
            return $this->applyDatabaseCoupon($offer);
        }

        return $this->applyLocalCoupon($offer);
    }

    private function applyDatabaseCoupon($offer)
    {
        $customer = Auth::guard('customer')->user();
        $cart = Cart::where('customer_id', $customer->id)
            ->where('status', 'active')
            ->first();


        if (!$cart || $cart->items->isEmpty()) {
            throw new \Exception('Your cart is empty');
        }

        // Check if same coupon is already applied
        if ($cart->offer_id == $offer->id) {
            throw new \Exception('This coupon is already applied');
        }

        // Simple logic: Only one promo code at a time (no stacking)
        $cart->offer_id = $offer->id;

        if ($offer->min_cart_amount && $cart->subtotal < $offer->min_cart_amount) {
            throw new \Exception('Minimum cart amount of ₹' . $offer->min_cart_amount . ' required');
        }

        $cart->save();

        $this->recalculateCartTotals($cart);

        return [
            'success' => true,
            'message' => 'Coupon applied successfully',
            'cart' => $this->formatCartResponse($cart->fresh(['items.variant.product', 'items.variant.images', 'items.variant.primaryImage.media', 'offer']))
        ];
    }

    private function applyLocalCoupon($offer)
    {
        $cart = $this->getLocalCart();

        if (empty($cart['items'])) {
            throw new \Exception('Your cart is empty');
        }

        if (isset($cart['offer_id']) && $cart['offer_id'] == $offer->id) {
             throw new \Exception('This coupon is already applied');
        }

        // Simple logic: Only one promo code at a time (no stacking)
        $cart['offer_id'] = $offer->id;
        $cart['offer_code'] = $offer->code;
        $cart['offer_type'] = $offer->offer_type;
        $cart['discount_value'] = $offer->discount_value;

        if ($offer->min_cart_amount && $cart['subtotal'] < $offer->min_cart_amount) {
            throw new \Exception('Minimum cart amount of ₹' . $offer->min_cart_amount . ' required');
        }

        $cart = $this->recalculateLocalCartTotals($cart);
        $this->saveLocalCart($cart);

        return [
            'success' => true,
            'message' => 'Coupon applied successfully',
            'cart' => $cart
        ];
    }

    public function removeCoupon()
    {
        if (Auth::guard('customer')->check()) {
            return $this->removeDatabaseCoupon();
        }

        return $this->removeLocalCoupon();
    }

    private function formatImageUrl($path)
    {
        if (!$path) return null;
        if (str_starts_with($path, 'http')) return $path;
        return asset('storage/' . $path);
    }

    private function formatAttributesText($attributes)
    {
        if (empty($attributes)) return '';
        
        $parts = [];
        foreach ($attributes as $key => $value) {
            // Handle numeric keys or array values if necessary (simple key-value assumed)
            if (is_array($value)) {
                $parts[] = ($value['name'] ?? $key) . ': ' . ($value['value'] ?? '');
            } else {
                $parts[] = ucfirst($key) . ': ' . $value;
            }
        }
        return implode(', ', array_filter($parts));
    }

    private function removeDatabaseCoupon()
    {
        $customer = Auth::guard('customer')->user();
        $cart = Cart::where('customer_id', $customer->id)
            ->where('status', 'active')
            ->first();

        if ($cart) {
            $cart->offer_id = null;
            $cart->save();
            $this->recalculateCartTotals($cart);
        }

        return [
            'success' => true,
            'message' => 'Coupon removed',
            'cart' => $this->formatCartResponse($cart->fresh(['items.variant.product', 'items.variant.images', 'items.variant.primaryImage.media']))
        ];
    }

    private function removeLocalCoupon()
    {
        $cart = $this->getLocalCart();
        unset($cart['offer_id'], $cart['offer_code'], $cart['offer_type'], $cart['discount_value']);
        $cart = $this->recalculateLocalCartTotals($cart);
        $this->saveLocalCart($cart);

        return [
            'success' => true,
            'message' => 'Coupon removed',
            'cart' => $cart
        ];
    }

    private function normalizeAttributes($attributes): string
    {
        if (empty($attributes)) {
            return '{}';
        }
        
        if (is_string($attributes)) {
            $attributes = json_decode($attributes, true) ?: [];
        }
        
        if (!is_array($attributes)) {
            return '{}';
        }
        
        ksort($attributes);
        return json_encode($attributes);
    }

    /**
     * Auto-apply eligible offers to the cart
     */
    private function applyAutoOffers($cart)
    {
        // Skip if cart is empty
        if ($cart->items->isEmpty()) {
            return;
        }

        $subtotal = $cart->subtotal ?? 0;
        
        // Find eligible auto-apply offers
        $autoOffers = \App\Models\Offer::where('status', true)
            ->where('is_auto_apply', true)
            ->where(function($q) {
                $q->where('starts_at', '<=', now())
                  ->orWhereNull('starts_at');
            })
            ->where(function($q) {
                $q->where('ends_at', '>=', now())
                  ->orWhereNull('ends_at');
            })
            ->get();

        foreach ($autoOffers as $offer) {
            // Check if offer meets minimum cart amount
            if ($offer->min_cart_amount && $subtotal < $offer->min_cart_amount) {
                continue;
            }

            // Check usage limits
            $customerId = Auth::guard('customer')->id();
            if (!$offer->canApply($customerId)) {
                continue;
            }

            // Apply the first eligible offer
            // TODO: Handle stackable logic when implementing multi-offer support
            if (!$cart->offer_id) {
                $cart->offer_id = $offer->id;
                $cart->save();
                $this->recalculateCartTotals($cart);
                break; // Apply only first eligible auto offer for now
            }
        }
    }
}