<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class WishlistController extends Controller
{
    private function getCustomerWishlist($customer)
    {
        return Wishlist::firstOrCreate(
            ['customer_id' => $customer->id, 'name' => 'Default'],
            ['is_public' => false]
        );
    }

    private function resolveProductVariant($productId)
    {
        if (is_numeric($productId)) {
            $product = Product::with(['variants', 'defaultVariant'])->find($productId);
            if (!$product) {
                $variant = ProductVariant::find($productId);
                if ($variant) return $variant;
            }
        } else {
            // Strip string prefixes like 'prod-' if present
            $cleanId = str_replace('prod-', '', $productId);
            if (is_numeric($cleanId)) {
                $product = Product::with(['variants', 'defaultVariant'])->find($cleanId);
            } else {
                $product = Product::with(['variants', 'defaultVariant'])->where('slug', $productId)->first();
            }
        }

        if (!$product) {
            return null;
        }

        return $product->defaultVariant ?? $product->variants->first();
    }

    private function formatWishlistProduct($product, $variant)
    {
        $mainImage = asset('images/placeholder.jpg');
        if ($variant && $variant->images && $variant->images->isNotEmpty()) {
            $img = $variant->images->first();
            $filePath = $img->file_path ?? '';
            $mainImage = str_starts_with($filePath, 'http') ? $filePath : asset(str_starts_with($filePath, 'storage/') ? $filePath : 'images/' . ltrim($filePath, '/'));
        }

        return [
            'id' => (string) $product->id,
            'db_id' => $product->id,
            'variant_id' => $variant ? $variant->id : null,
            'slug' => $product->slug,
            'name' => $product->name,
            'short_description' => $product->short_description ?? '',
            'description' => $product->description ?? '',
            'main_image' => $mainImage,
            'image' => $mainImage,
            'images' => [$mainImage],
            'price' => $variant ? (float) $variant->price : 0,
            'compare_price' => $variant && $variant->compare_price ? (float) $variant->compare_price : null,
            'compareAtPrice' => $variant && $variant->compare_price ? (float) $variant->compare_price : null,
            'inStock' => $variant ? $variant->stock_quantity > 0 : true,
            'stock' => $variant ? $variant->stock_quantity : 10,
            'category' => $product->mainCategory ? $product->mainCategory->name : 'Handmade',
            'categorySlug' => $product->mainCategory ? $product->mainCategory->slug : 'all',
            'is_featured' => (bool) $product->is_featured,
            'is_new' => (bool) $product->is_new,
            'is_bestseller' => (bool) $product->is_bestseller,
            'rating' => (float) ($product->rating ?? 5.0),
            'reviews_count' => (int) ($product->review_count ?? 12),
        ];
    }

    /**
     * Get current user's wishlist products
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $customer = auth('customer_api')->user();
            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
            }

            $wishlist = $this->getCustomerWishlist($customer);
            $items = WishlistItem::with(['variant.product.mainCategory', 'variant.images'])
                ->where('wishlist_id', $wishlist->id)
                ->get();

            $products = [];
            $productIds = [];

            foreach ($items as $item) {
                if ($item->variant && $item->variant->product) {
                    $prod = $item->variant->product;
                    $products[] = $this->formatWishlistProduct($prod, $item->variant);
                    $productIds[] = (string) $prod->id;
                    $productIds[] = "prod-{$prod->id}";
                }
            }

            return response()->json([
                'success' => true,
                'data' => $products,
                'product_ids' => array_values(array_unique($productIds)),
                'count' => count($products),
            ]);
        } catch (\Exception $e) {
            Log::error('Wishlist index error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch wishlist'], 500);
        }
    }

    /**
     * Toggle product in user's wishlist
     */
    public function toggle(Request $request, $productId): JsonResponse
    {
        try {
            $customer = auth('customer_api')->user();
            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
            }

            $variant = $this->resolveProductVariant($productId);
            if (!$variant) {
                return response()->json(['success' => false, 'message' => 'Product not found'], 444);
            }

            $wishlist = $this->getCustomerWishlist($customer);
            $existing = WishlistItem::where('wishlist_id', $wishlist->id)
                ->where('product_variant_id', $variant->id)
                ->first();

            $inWishlist = false;
            if ($existing) {
                $existing->delete();
                $inWishlist = false;
            } else {
                WishlistItem::create([
                    'wishlist_id' => $wishlist->id,
                    'product_variant_id' => $variant->id,
                ]);
                $inWishlist = true;
            }

            $count = WishlistItem::where('wishlist_id', $wishlist->id)->count();

            return response()->json([
                'success' => true,
                'in_wishlist' => $inWishlist,
                'count' => $count,
                'message' => $inWishlist ? 'Product added to wishlist' : 'Product removed from wishlist',
            ]);
        } catch (\Exception $e) {
            Log::error('Wishlist toggle error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update wishlist'], 500);
        }
    }

    /**
     * Remove product from wishlist
     */
    public function remove(Request $request, $productId): JsonResponse
    {
        try {
            $customer = auth('customer_api')->user();
            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
            }

            $variant = $this->resolveProductVariant($productId);
            if ($variant) {
                $wishlist = $this->getCustomerWishlist($customer);
                WishlistItem::where('wishlist_id', $wishlist->id)
                    ->where('product_variant_id', $variant->id)
                    ->delete();
            }

            $wishlist = $this->getCustomerWishlist($customer);
            $count = WishlistItem::where('wishlist_id', $wishlist->id)->count();

            return response()->json([
                'success' => true,
                'count' => $count,
                'message' => 'Product removed from wishlist',
            ]);
        } catch (\Exception $e) {
            Log::error('Wishlist remove error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to remove from wishlist'], 500);
        }
    }

    /**
     * Sync frontend local wishlist items to user's database wishlist upon login
     */
    public function sync(Request $request): JsonResponse
    {
        try {
            $customer = auth('customer_api')->user();
            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
            }

            $wishlist = $this->getCustomerWishlist($customer);
            $productIds = $request->input('product_ids', []);

            if (is_array($productIds)) {
                foreach ($productIds as $pId) {
                    $variant = $this->resolveProductVariant($pId);
                    if ($variant) {
                        WishlistItem::firstOrCreate([
                            'wishlist_id' => $wishlist->id,
                            'product_variant_id' => $variant->id,
                        ]);
                    }
                }
            }

            return $this->index($request);
        } catch (\Exception $e) {
            Log::error('Wishlist sync error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to sync wishlist'], 500);
        }
    }
}
