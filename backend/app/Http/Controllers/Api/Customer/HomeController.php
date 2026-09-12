<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Testimonial;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\Banner;
use App\Models\HomeSection;
use App\Services\Customer\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(): JsonResponse
    {
        try {
            /* ------------------------------
             | BANNERS
             |------------------------------*/
            $banners = Banner::where('status', 1)->orderBy('sort_order')->get();

            /* ------------------------------
             | CATEGORIES
             |------------------------------*/
            $featuredCategories = Category::where('status', 1)
                ->where('featured', 1) 
                ->limit(10)
                ->get();

            /* ------------------------------
             | HOME SECTIONS
             |------------------------------*/
            $sections = HomeSection::with('category')->where('status', true)->orderBy('sort_order')->get();
            $dynamicSections = [];

            foreach ($sections as $section) {
                $products = [];
                if ($section->type === 'category' && $section->category_id) {
                    $products = $this->productService->getProducts(
                        [
                            'category_id' => $section->category_id,
                            'sort_by'     => 'featured',
                        ],
                        12,
                        1
                    )->items();
                } elseif ($section->type === 'custom_products' && !empty($section->product_ids)) {
                    $products = $this->productService->getProducts(
                        [
                            'product_ids' => $section->product_ids,
                        ],
                        12,
                        1
                    )->items();
                }

                $dynamicSections[] = [
                    'id' => $section->id,
                    'title' => $section->title,
                    'subtitle' => $section->subtitle,
                    'style' => $section->style,
                    'products' => collect($products)->filter(fn ($p) => is_array($p) && isset($p['id']))->values()->toArray()
                ];
            }

            /* ------------------------------
             | TESTIMONIALS
             |------------------------------*/
            $testimonials = Testimonial::where('is_active', true)
                ->latest()
                ->limit(100)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'banners' => $banners,
                    'featuredCategories' => $featuredCategories,
                    'dynamicSections' => $dynamicSections,
                    'testimonials' => $testimonials,
                ]
            ]);

        } catch (\Throwable $e) {
            Log::error('Home page error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load home data'
            ], 500);
        }
    }
}
