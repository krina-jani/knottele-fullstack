<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\OfferCategory;
use App\Models\OfferVariant;
use App\Models\OfferReward;
use App\Http\Resources\Admin\OfferResource;
use App\Http\Resources\Admin\OfferCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OfferController extends Controller
{
    /**
     * Display a listing of offers.
     */
    public function index(Request $request)
    {
        try {
            $query = Offer::query()->with(['categories', 'variants.product', 'rewards.product']);

            // Search filter
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
                });
            }

            // Status filter
            if ($request->has('status') && in_array($request->status, ['1', '0'])) {
                $query->where('status', $request->status);
            }

            // Offer type filter
            if ($request->has('offer_type') && $request->offer_type) {
                $query->where('offer_type', $request->offer_type);
            }

            // Date filters
            if ($request->has('start_date') && $request->start_date) {
                $query->whereDate('starts_at', '>=', $request->start_date);
            }

            if ($request->has('end_date') && $request->end_date) {
                $query->whereDate('ends_at', '<=', $request->end_date);
            }

            // Sort
            $sortField = $request->get('sort', 'created_at');
            $sortDirection = $request->get('direction', 'desc');
            $query->orderBy($sortField, $sortDirection);

            // Pagination
            $perPage = $request->get('per_page', 10);
            $offers = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => new OfferCollection($offers),
                'message' => 'Offers retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve offers: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get offer statistics for dashboard.
     */
    public function statistics()
    {
        try {
            $now = now();
            
            // Total offers (non-deleted)
            $totalOffers = Offer::count();
            
            // Active offers (status=1 and within date range or no date restrictions)
            $activeOffers = Offer::where('status', true)
                ->where(function($q) use ($now) {
                    $q->whereNull('starts_at')
                      ->orWhere('starts_at', '<=', $now);
                })
                ->where(function($q) use ($now) {
                    $q->whereNull('ends_at')
                      ->orWhere('ends_at', '>=', $now);
                })
                ->count();
            
            // Expired offers (ends_at is in the past)
            $expiredOffers = Offer::where('ends_at', '<', $now)->count();
            
            // Most used offer
            $mostUsedOffer = Offer::where('used_count', '>', 0)
                ->orderBy('used_count', 'desc')
                ->first();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'total_offers' => $totalOffers,
                    'active_offers' => $activeOffers,
                    'expired_offers' => $expiredOffers,
                    'most_used_offer' => $mostUsedOffer ? [
                        'name' => $mostUsedOffer->name,
                        'code' => $mostUsedOffer->code,
                        'used_count' => $mostUsedOffer->used_count
                    ] : null
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve statistics: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created offer.
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'code' => ['nullable', 'string', 'max:50', Rule::unique('offers')->whereNull('deleted_at')],
                'status' => 'required|boolean',
                'offer_type' => 'required|in:percentage,fixed',
                'discount_value' => 'required_if:offer_type,percentage,fixed|numeric|min:0',
                'buy_qty' => 'nullable|integer|min:1',
                'get_qty' => 'nullable|integer|min:1',
                'min_cart_amount' => 'nullable|numeric|min:0',
                'max_cart_amount' => 'nullable|numeric|min:0',
                'max_discount' => 'nullable|numeric|min:0',
                'max_uses' => 'nullable|integer|min:1',
                'uses_per_customer' => 'nullable|integer|min:1',
                'starts_at' => 'nullable|date',
                'ends_at' => 'nullable|date|after_or_equal:starts_at',
                'is_auto_apply' => 'boolean',
                'is_stackable' => 'boolean',
                'is_exclusive' => 'boolean',
                'customer_segment_id' => 'nullable|exists:customer_segments,id',
                'categories' => 'nullable|array',
                'categories.*' => 'exists:categories,id',
                'variants' => 'nullable|array',
                'variants.*' => 'exists:product_variants,id',
                'rewards' => 'nullable|array',
                'rewards.*.reward_qty' => 'nullable|integer|min:1',
                'rewards.*.same_as_buy_product' => 'nullable|boolean',
                'banner' => 'nullable|string',
                'show_at_start' => 'nullable|boolean',
                'banner_button_text' => 'nullable|string|max:50',
                'banner_button_link' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => $validator->errors()->first()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            DB::beginTransaction();

            // Create offer
            $offer = Offer::create($request->only([
                'name', 'code', 'status', 'offer_type', 'discount_value',
                'buy_qty', 'get_qty', 'min_cart_amount', 'max_cart_amount',
                'max_discount', 'max_uses', 'uses_per_customer', 'starts_at',
                'ends_at', 'is_auto_apply', 'is_stackable', 'is_exclusive',
                'customer_segment_id', 'banner', 'show_at_start',
                'banner_button_text', 'banner_button_link'
            ]));

            // Ensure only one offer has show_at_start = true
            if ($offer->show_at_start) {
                Offer::where('id', '!=', $offer->id)->update(['show_at_start' => false]);
            }

            // Sync categories
            if ($request->has('categories')) {
                $offer->categories()->sync($request->categories);
            }

            // Sync variants
            if ($request->has('variants')) {
                $offer->variants()->sync($request->variants);
            }

            // Create rewards
            if ($request->has('rewards')) {
                foreach ($request->rewards as $rewardData) {
                    OfferReward::create(array_merge($rewardData, ['offer_id' => $offer->id]));
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => new OfferResource($offer->load(['categories', 'variants', 'rewards'])),
                'message' => 'Offer created successfully'
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create offer: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified offer.
     */
    public function show($id)
    {
        try {
            $offer = Offer::with(['categories', 'variants.product', 'rewards.product', 'rewards.variant'])
                         ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => new OfferResource($offer),
                'message' => 'Offer retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Offer not found: ' . $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified offer.
     */
    public function update(Request $request, $id)
    {
        try {
            $offer = Offer::findOrFail($id);

            // Validate request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'code' => ['nullable', 'string', 'max:50', Rule::unique('offers')->ignore($id)->whereNull('deleted_at')],
                'status' => 'required|boolean',
                'offer_type' => 'required|in:percentage,fixed',
                'discount_value' => 'required_if:offer_type,percentage,fixed|numeric|min:0',
                'buy_qty' => 'nullable|integer|min:1',
                'get_qty' => 'nullable|integer|min:1',
                'min_cart_amount' => 'nullable|numeric|min:0',
                'max_cart_amount' => 'nullable|numeric|min:0',
                'max_discount' => 'nullable|numeric|min:0',
                'max_uses' => 'nullable|integer|min:1',
                'uses_per_customer' => 'nullable|integer|min:1',
                'starts_at' => 'nullable|date',
                'ends_at' => 'nullable|date|after_or_equal:starts_at',
                'is_auto_apply' => 'boolean',
                'is_stackable' => 'boolean',
                'is_exclusive' => 'boolean',
                'customer_segment_id' => 'nullable|exists:customer_segments,id',
                'categories' => 'nullable|array',
                'categories.*' => 'exists:categories,id',
                'variants' => 'nullable|array',
                'variants.*' => 'exists:product_variants,id',
                'rewards' => 'nullable|array',
                'banner' => 'nullable|string',
                'show_at_start' => 'nullable|boolean',
                'banner_button_text' => 'nullable|string|max:50',
                'banner_button_link' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => $validator->errors()->first()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            DB::beginTransaction();

            // Update offer
            $offer->update($request->only([
                'name', 'code', 'status', 'offer_type', 'discount_value',
                'buy_qty', 'get_qty', 'min_cart_amount', 'max_cart_amount',
                'max_discount', 'max_uses', 'uses_per_customer', 'starts_at',
                'ends_at', 'is_auto_apply', 'is_stackable', 'is_exclusive',
                'customer_segment_id', 'banner', 'show_at_start',
                'banner_button_text', 'banner_button_link'
            ]));

            // Ensure only one offer has show_at_start = true
            if ($offer->show_at_start) {
                Offer::where('id', '!=', $offer->id)->update(['show_at_start' => false]);
            }

            // Sync categories
            if ($request->has('categories')) {
                $offer->categories()->sync($request->categories);
            }

            // Sync variants
            if ($request->has('variants')) {
                $offer->variants()->sync($request->variants);
            }

            // Handle rewards
            if ($request->has('rewards')) {
                // Delete existing rewards
                $offer->rewards()->delete();

                // Create new rewards
                foreach ($request->rewards as $rewardData) {
                    OfferReward::create(array_merge($rewardData, ['offer_id' => $offer->id]));
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => new OfferResource($offer->load(['categories', 'variants', 'rewards'])),
                'message' => 'Offer updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update offer: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified offer.
     */
    public function destroy($id)
    {
        try {
            // Use withTrashed to also find soft-deleted offers
            $offer = Offer::withTrashed()->findOrFail($id);
            
            // Manually delete related data BEFORE forceDelete
            $offer->rewards()->delete();
            $offer->variants()->detach();
            $offer->categories()->detach();
            
            // Clear any carts referencing this offer
            \App\Models\Cart::where('offer_id', $id)->update(['offer_id' => null]);
            
            // Permanently delete the offer
            $offer->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Offer deleted permanently'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete offer: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Toggle offer status.
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $offer = Offer::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'status' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed'
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $offer->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'data' => new OfferResource($offer),
                'message' => 'Offer status updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update offer status: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Toggle auto apply status.
     */
    public function updateAutoApply(Request $request, $id)
    {
        try {
            $offer = Offer::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'is_auto_apply' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed'
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $offer->update(['is_auto_apply' => $request->is_auto_apply]);

            return response()->json([
                'success' => true,
                'data' => new OfferResource($offer),
                'message' => 'Offer auto-apply updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update offer auto-apply: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Bulk update offers.
     */
    public function bulkUpdate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'ids' => 'required|array',
                'ids.*' => 'exists:offers,id',
                'action' => 'required|in:activate,deactivate,auto_apply,disable_auto_apply,delete'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed'
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            DB::beginTransaction();

            switch ($request->action) {
                case 'activate':
                    Offer::whereIn('id', $request->ids)->update(['status' => 1]);
                    $message = 'Offers activated successfully';
                    break;

                case 'deactivate':
                    Offer::whereIn('id', $request->ids)->update(['status' => 0]);
                    $message = 'Offers deactivated successfully';
                    break;

                case 'auto_apply':
                    Offer::whereIn('id', $request->ids)->update(['is_auto_apply' => 1]);
                    $message = 'Offers set to auto-apply successfully';
                    break;

                case 'disable_auto_apply':
                    Offer::whereIn('id', $request->ids)->update(['is_auto_apply' => 0]);
                    $message = 'Offers auto-apply disabled successfully';
                    break;

                case 'delete':
                    Offer::withTrashed()->whereIn('id', $request->ids)->forceDelete();
                    $message = 'Offers permanently deleted successfully';
                    break;

                default:
                    throw new \Exception('Invalid action');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'affected_count' => count($request->ids)
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to perform bulk action: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Bulk delete offers.
     */
    public function bulkDelete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'ids' => 'required|array',
                'ids.*' => 'integer' // Changed from exists to integer as withTrashed is used
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed'
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Delete related data first
            OfferReward::whereIn('offer_id', $request->ids)->delete();
            DB::table('offer_variants')->whereIn('offer_id', $request->ids)->delete();
            DB::table('offer_categories')->whereIn('offer_id', $request->ids)->delete();
            
            // Clear any carts referencing these offers
            \App\Models\Cart::whereIn('offer_id', $request->ids)->update(['offer_id' => null]);

            $count = Offer::withTrashed()->whereIn('id', $request->ids)->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Offers permanently deleted successfully',
                'data' => [
                    'deleted_count' => $count
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete offers: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get dropdown list of offers.
     */
    public function dropdown()
    {
        try {
            $offers = Offer::where('status', 1)
                ->where(function($query) {
                    $query->whereNull('ends_at')
                          ->orWhere('ends_at', '>=', now());
                })
                ->where(function($query) {
                    $query->whereNull('starts_at')
                          ->orWhere('starts_at', '<=', now());
                })
                ->select('id', 'name', 'code')
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $offers,
                'message' => 'Offers dropdown retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve offers dropdown: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get offer types.
     */
    public function types()
    {
        $types = [
            'percentage' => 'Percentage Discount',
            'fixed' => 'Fixed Amount Discount',
            'bogo' => 'Buy One Get One',
            'buy_x_get_y' => 'Buy X Get Y',
            'free_shipping' => 'Free Shipping',
            'tiered' => 'Tiered Discount'
        ];

        return response()->json([
            'success' => true,
            'data' => $types,
            'message' => 'Offer types retrieved successfully'
        ]);
    }

    /**
     * Validate offer code.
     */
    public function validateCode(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|string|max:50',
                'exclude_id' => 'nullable|exists:offers,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed'
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $query = Offer::where('code', $request->code);

            if ($request->has('exclude_id')) {
                $query->where('id', '!=', $request->exclude_id);
            }

            $exists = $query->exists();

            return response()->json([
                'success' => true,
                'data' => [
                    'exists' => $exists,
                    'available' => !$exists
                ],
                'message' => $exists ? 'Code already exists' : 'Code is available'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to validate code: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
