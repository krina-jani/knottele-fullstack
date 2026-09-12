<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Testimonial;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\Banner;
use App\Models\HomeSection;
use App\Services\Customer\ProductService;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
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
                ->where('featured', 1) // Assuming you have this flag, or just take top 8
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

            /* ------------------------------
             | STATS
             |------------------------------*/
            $stats = [
                'customer_count' => Customer::where('status', 1)->count(),
                'product_count'  => Product::where('status', 'active')->count(),
                'order_count'    => Order::where('status', 'delivered')->count(),
                'review_count'   => 98,
            ];

            return view('customer.home.index', compact(
                'banners',
                'featuredCategories',
                'dynamicSections',
                'testimonials',
                'stats'
            ));

        } catch (\Throwable $e) {
            Log::error('Home page error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('customer.home.index', [
                'banners' => collect(),
                'featuredCategories' => collect(),
                'dynamicSections' => [],
                'testimonials' => collect(),
                'stats' => [],
            ]);
        }
    }
}
