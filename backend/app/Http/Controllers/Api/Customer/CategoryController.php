<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Get a list of all active categories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $categories = Category::where('status', 1)
            ->whereNull('parent_id') // Get main categories
            ->orderBy('sort_order', 'asc')
            ->with('image') // Eager load image relationship
            ->get();

        $priceFromMap = [
            'keychain' => 'From ₹100 →',
            'flower' => 'From ₹200 →',
            'bouquet' => 'From ₹700 →',
            'soft-toys' => 'From ₹1,000 →',
            'bags' => 'From ₹4,800 →',
            'coin-purse' => 'From ₹250 →',
            'phone-cover' => 'From ₹1,000 →',
            'cup-tea-coffee' => 'From ₹500 →',
            'bookmark' => 'From ₹300 →',
            'hair-accessories' => 'From ₹200 →',
            'clothing' => 'From ₹2,800 →',
        ];

        $formattedCategories = $categories->map(function ($category) use ($priceFromMap) {
            $imageUrl = null;
            if ($category->image && $category->image->file_name) {
                if (str_starts_with($category->image->file_name, 'http')) {
                    $imageUrl = $category->image->file_name;
                } else {
                    $imageUrl = asset('storage/' . $category->image->file_name);
                }
            } else {
                $imageUrl = asset('images/categories/' . $category->slug . '.jpg');
            }

            $productCount = $category->products()->count();

            return [
                'id' => 'cat-' . $category->id,
                'db_id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'image' => $imageUrl,
                'featured' => (bool) $category->featured,
                'priceFrom' => $priceFromMap[$category->slug] ?? 'From ₹100 →',
                'itemCount' => $productCount > 0 ? $productCount : 10,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $formattedCategories
        ]);
    }
}
