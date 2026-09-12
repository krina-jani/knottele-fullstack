<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Helpers\CartHelper;

class WishlistController extends Controller
{
    protected $cartHelper;

    public function __construct(CartHelper $cartHelper)
    {
        $this->cartHelper = $cartHelper;
    }
    public function index()
    {
        $customer = Auth::guard('customer')->user();

        // Get customer's default wishlist or create one
        $wishlist = Wishlist::firstOrCreate(
            [
                'customer_id' => $customer->id,
                'name' => 'My Wishlist',
            ],
            [
                'is_public' => false,
            ]
        );

        // Get wishlist items with product details
        $wishlistItems = WishlistItem::where('wishlist_id', $wishlist->id)
            ->with(['variant.product', 'variant.images', 'variant.primaryImage.media'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $wishlistCount = $wishlist->items()->count();

        // Get total estimated price
        $totalPrice = $wishlistItems->sum(function($item) {
            return $item->variant->price ?? $item->variant->product->price ?? 0;
        });

        // Get cart items count
        $cart = \App\Models\Cart::where('customer_id', $customer->id)->where('status', 1)->first();
        $cartCount = $cart ? $cart->items()->sum('quantity') : 0;

        // Get orders count
        $ordersCount = \App\Models\Order::where('customer_id', $customer->id)->count();

        return view('customer.wishlist.index', compact(
            'customer',
            'wishlist',
            'wishlistItems',
            'wishlistCount',
            'totalPrice',
            'cartCount',
            'ordersCount'
        ));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
        ]);

        $customer = Auth::guard('customer')->user();

        // Get or create default wishlist
        $wishlist = Wishlist::firstOrCreate(
            [
                'customer_id' => $customer->id,
                'name' => 'My Wishlist',
            ],
            [
                'is_public' => false,
            ]
        );

        // Check if variant already exists in wishlist
        $existingItem = WishlistItem::where('wishlist_id', $wishlist->id)
            ->where('product_variant_id', $request->product_variant_id)
            ->first();

        if ($existingItem) {
            return response()->json([
                'success' => false,
                'message' => 'This item is already in your wishlist'
            ], 400);
        }

        // Add to wishlist
        $wishlistItem = WishlistItem::create([
            'wishlist_id' => $wishlist->id,
            'product_variant_id' => $request->product_variant_id,
        ]);

        $wishlistCount = $wishlist->items()->count();

        return response()->json([
            'success' => true,
            'message' => 'Added to wishlist successfully',
            'count' => $wishlistCount,
            'item_id' => $wishlistItem->id
        ]);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:wishlist_items,id',
        ]);

        $customer = Auth::guard('customer')->user();

        // Find the wishlist item
        $wishlistItem = WishlistItem::where('id', $request->item_id)
            ->whereHas('wishlist', function($query) use ($customer) {
                $query->where('customer_id', $customer->id);
            })
            ->first();

        if (!$wishlistItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in your wishlist'
            ], 404);
        }

        $wishlistItem->delete();

        $wishlist = Wishlist::where('customer_id', $customer->id)->first();
        $wishlistCount = $wishlist ? $wishlist->items()->count() : 0;

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Removed from wishlist',
                'count' => $wishlistCount
            ]);
        }

        return redirect()->back()->with('success', 'Removed from wishlist');
    }

    public function removeMultiple(Request $request)
    {
        $request->validate([
            'item_ids' => 'required|array',
            'item_ids.*' => 'exists:wishlist_items,id',
        ]);

        $customer = Auth::guard('customer')->user();

        // Delete multiple items
        $deleted = WishlistItem::whereIn('id', $request->item_ids)
            ->whereHas('wishlist', function($query) use ($customer) {
                $query->where('customer_id', $customer->id);
            })
            ->delete();

        $wishlist = Wishlist::where('customer_id', $customer->id)->first();
        $wishlistCount = $wishlist ? $wishlist->items()->count() : 0;

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Items removed from wishlist',
                'count' => $wishlistCount,
                'deleted_count' => $deleted
            ]);
        }

        return redirect()->back()->with('success', 'Selected items removed');
    }

    public function moveToCart(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:wishlist_items,id',
        ]);

        $customer = Auth::guard('customer')->user();

        // Find the wishlist item with variant details
        $wishlistItem = WishlistItem::where('id', $request->item_id)
            ->whereHas('wishlist', function($query) use ($customer) {
                $query->where('customer_id', $customer->id);
            })
            ->with('variant')
            ->first();

        if (!$wishlistItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in your wishlist'
            ], 404);
        }

        // Add to cart using CartHelper
        try {
            $this->cartHelper->addToCart(
                $wishlistItem->product_variant_id,
                1
            );

            // Remove from wishlist after adding to cart
            $wishlistItem->delete();

            $wishlist = Wishlist::where('customer_id', $customer->id)->first();
            $wishlistCount = $wishlist ? $wishlist->items()->count() : 0;

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item moved to cart successfully',
                    'count' => $wishlistCount
                ]);
            }

            return redirect()->back()->with('success', 'Item moved to cart successfully');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to add to cart: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Failed to add to cart: ' . $e->getMessage());
        }
    }

    public function moveAllToCart(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $wishlist = Wishlist::where('customer_id', $customer->id)->first();

        if (!$wishlist) {
            return response()->json([
                'success' => false,
                'message' => 'No wishlist found'
            ], 404);
        }

        $items = $wishlist->items()->with('variant')->get();

        $movedCount = 0;
        $errors = [];

        // Add all items to cart
        foreach ($items as $item) {
            try {
                $this->cartHelper->addToCart(
                    $item->product_variant_id,
                    1
                );

                // Remove from wishlist
                $item->delete();
                $movedCount++;
            } catch (\Exception $e) {
                $errors[] = "Item {$item->id}: " . $e->getMessage();
            }
        }

        $wishlistCount = $wishlist->items()->count();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$movedCount} items moved to cart successfully",
                'count' => $wishlistCount,
                'errors' => $errors
            ]);
        }

        if (count($errors) > 0) {
            return redirect()->back()->with('warning', "{$movedCount} items moved to cart. Some errors occurred.");
        }

        return redirect()->back()->with('success', "All items moved to cart successfully");
    }

    public function clear(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $wishlist = Wishlist::where('customer_id', $customer->id)->first();

        if ($wishlist) {
            $wishlist->items()->delete();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Wishlist cleared successfully'
            ]);
        }

        return redirect()->route('customer.wishlist.index')
            ->with('success', 'Wishlist cleared successfully');
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'is_public' => 'boolean',
        ]);

        $customer = Auth::guard('customer')->user();

        // Check if wishlist with same name exists
        $existing = Wishlist::where('customer_id', $customer->id)
            ->where('name', $request->name)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You already have a wishlist with this name'
            ], 400);
        }

        $wishlist = Wishlist::create([
            'customer_id' => $customer->id,
            'name' => $request->name,
            'is_public' => $request->is_public ?? false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Wishlist created successfully',
            'wishlist' => $wishlist
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'is_public' => 'boolean',
        ]);

        $customer = Auth::guard('customer')->user();

        $wishlist = Wishlist::where('customer_id', $customer->id)
            ->where('id', $id)
            ->first();

        if (!$wishlist) {
            return response()->json([
                'success' => false,
                'message' => 'Wishlist not found'
            ], 404);
        }

        // Check if another wishlist with same name exists
        $existing = Wishlist::where('customer_id', $customer->id)
            ->where('name', $request->name)
            ->where('id', '!=', $id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You already have a wishlist with this name'
            ], 400);
        }

        $wishlist->update([
            'name' => $request->name,
            'is_public' => $request->is_public ?? false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Wishlist updated successfully',
            'wishlist' => $wishlist
        ]);
    }

    public function delete($id)
    {
        $customer = Auth::guard('customer')->user();

        $wishlist = Wishlist::where('customer_id', $customer->id)
            ->where('id', $id)
            ->first();

        if (!$wishlist) {
            return response()->json([
                'success' => false,
                'message' => 'Wishlist not found'
            ], 404);
        }

        // Don't allow deletion of default wishlist
        if ($wishlist->name === 'My Wishlist') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete default wishlist'
            ], 400);
        }

        $wishlist->delete();

        return response()->json([
            'success' => true,
            'message' => 'Wishlist deleted successfully'
        ]);
    }

    public function share($id)
    {
        $customer = Auth::guard('customer')->user();

        $wishlist = Wishlist::where('customer_id', $customer->id)
            ->where('id', $id)
            ->first();

        if (!$wishlist) {
            return response()->json([
                'success' => false,
                'message' => 'Wishlist not found'
            ], 404);
        }

        if (!$wishlist->is_public) {
            return response()->json([
                'success' => false,
                'message' => 'Wishlist is not public. Please enable public sharing first.'
            ], 403);
        }

        $shareUrl = route('wishlist.shared', $wishlist->id);

        return response()->json([
            'success' => true,
            'message' => 'Share link generated',
            'share_url' => $shareUrl
        ]);
    }

    public function shared($id)
    {
        $wishlist = Wishlist::where('id', $id)
            ->where('is_public', true)
            ->with(['items.variant.product', 'items.variant.images'])
            ->first();

        if (!$wishlist) {
            abort(404, 'Wishlist not found or is private');
        }

        $wishlistCount = $wishlist->items()->count();
        $totalPrice = $wishlist->items->sum(function($item) {
            return $item->variant->price ?? $item->variant->product->price ?? 0;
        });

        return view('customer.wishlist.shared', compact(
            'wishlist',
            'wishlistCount',
            'totalPrice'
        ));
    }

    public function count()
    {
        $customer = Auth::guard('customer')->user();

        $wishlist = Wishlist::where('customer_id', $customer->id)->first();
        $count = $wishlist ? $wishlist->items()->count() : 0;

        return response()->json(['count' => $count]);
    }

    public function getWishlistItems()
    {
        $customer = Auth::guard('customer')->user();

        $wishlist = Wishlist::where('customer_id', $customer->id)->first();

        if (!$wishlist) {
            return response()->json(['items' => []]);
        }

        $items = $wishlist->items()
            ->with(['variant.product' => function($query) {
                $query->select('id', 'name', 'slug');
            }, 'variant.images'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'variant_id' => $item->product_variant_id,
                    'product_name' => $item->variant->product->name ?? 'Unknown Product',
                    'product_slug' => $item->variant->product->slug ?? '#',
                    'price' => $item->variant->price ?? 0,
                    'compare_price' => $item->variant->compare_price ?? 0,
                    'image' => $item->variant->display_image,
                    'in_stock' => $item->variant->stock_quantity > 0,
                    'added_at' => $item->created_at->format('M d, Y'),
                ];
            });

        return response()->json(['items' => $items]);
    }

    public function getWishlists()
    {
        $customer = Auth::guard('customer')->user();

        $wishlists = Wishlist::where('customer_id', $customer->id)
            ->withCount('items')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['wishlists' => $wishlists]);
    }

    public function addItemToWishlist(Request $request, $wishlistId)
    {
        $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
        ]);

        $customer = Auth::guard('customer')->user();

        $wishlist = Wishlist::where('customer_id', $customer->id)
            ->where('id', $wishlistId)
            ->first();

        if (!$wishlist) {
            return response()->json([
                'success' => false,
                'message' => 'Wishlist not found'
            ], 404);
        }

        // Check if variant already exists in wishlist
        $existingItem = WishlistItem::where('wishlist_id', $wishlist->id)
            ->where('product_variant_id', $request->product_variant_id)
            ->first();

        if ($existingItem) {
            return response()->json([
                'success' => false,
                'message' => 'This item is already in this wishlist'
            ], 400);
        }

        // Add to wishlist
        $wishlistItem = WishlistItem::create([
            'wishlist_id' => $wishlist->id,
            'product_variant_id' => $request->product_variant_id,
        ]);

        $wishlistCount = $wishlist->items()->count();

        return response()->json([
            'success' => true,
            'message' => 'Added to wishlist successfully',
            'count' => $wishlistCount,
            'item_id' => $wishlistItem->id
        ]);
    }
}
