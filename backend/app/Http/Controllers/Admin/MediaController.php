<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Category;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index()
    {
        return view('admin.media.index');
    }

    /**
     * Get Structured Website Media Manager Data
     */
    public function getManagerData(Request $request): JsonResponse
    {
        if (Media::count() === 0) {
            $this->syncLocalImagesToDatabase();
        }

        // Fetch existing hero slides dynamically from DB
        $heroMediaList = Media::where('page', 'homepage')
            ->where('section', 'hero')
            ->orderBy('sort_order', 'asc')
            ->get();

        $heroSlots = [];
        if ($heroMediaList->isNotEmpty()) {
            foreach ($heroMediaList as $idx => $hm) {
                $slideNum = $hm->sort_order ?: ($idx + 1);
                $deviceLabel = $hm->device === 'all' ? 'All Devices' : ucfirst($hm->device);
                $heroSlots[] = [
                    'slot' => $hm->slot ?: 'hero_slide_' . $hm->id,
                    'title' => $hm->title ?: "Hero Slide {$slideNum} ({$deviceLabel})",
                    'description' => $hm->description ?: 'Hero carousel slide visual and banner.',
                    'recommended_dimensions' => $hm->device === 'mobile' ? '768 × 1000' : '1920 × 700',
                    'device' => $hm->device ?: 'desktop',
                    'page' => 'homepage',
                    'section' => 'hero',
                    'sort_order' => $slideNum,
                    'is_custom_slide' => true,
                    'media_id' => $hm->id,
                ];
            }
        } else {
            $heroSlots = [
                [
                    'slot' => 'hero_slide_1_desktop',
                    'title' => 'Hero Slide 1 (Desktop)',
                    'description' => 'Primary panoramic hero slide on desktop screens.',
                    'recommended_dimensions' => '1920 × 700',
                    'device' => 'desktop',
                    'page' => 'homepage',
                    'section' => 'hero',
                    'sort_order' => 1,
                ],
                [
                    'slot' => 'hero_slide_1_mobile',
                    'title' => 'Hero Slide 1 (Mobile)',
                    'description' => 'Mobile-optimized vertical hero slide (optional fallback to desktop).',
                    'recommended_dimensions' => '768 × 1000',
                    'device' => 'mobile',
                    'page' => 'homepage',
                    'section' => 'hero',
                    'sort_order' => 1,
                ],
                [
                    'slot' => 'hero_slide_2_desktop',
                    'title' => 'Hero Slide 2 (Everyday Elegance)',
                    'description' => 'Second slide featuring handcrafted bags & wearable creations.',
                    'recommended_dimensions' => '1920 × 700',
                    'device' => 'desktop',
                    'page' => 'homepage',
                    'section' => 'hero',
                    'sort_order' => 2,
                ],
                [
                    'slot' => 'hero_slide_3_desktop',
                    'title' => 'Hero Slide 3 (Everlasting Blooms)',
                    'description' => 'Third slide featuring everlasting flower bouquets.',
                    'recommended_dimensions' => '1920 × 700',
                    'device' => 'desktop',
                    'page' => 'homepage',
                    'section' => 'hero',
                    'sort_order' => 3,
                ],
                [
                    'slot' => 'hero_slide_4_desktop',
                    'title' => 'Hero Slide 4 (Bespoke Creations)',
                    'description' => 'Fourth slide featuring customized & tailored gifts.',
                    'recommended_dimensions' => '1920 × 700',
                    'device' => 'desktop',
                    'page' => 'homepage',
                    'section' => 'hero',
                    'sort_order' => 4,
                ],
            ];
        }

        // 1. Defined Slots Configuration
        $sections = [
            [
                'id' => 'hero',
                'page' => 'homepage',
                'title' => 'Hero Banner',
                'description' => 'Main panoramic homepage hero carousel slides and visual banners.',
                'badge' => 'Carousel & Banner',
                'is_hero_section' => true,
                'slots' => $heroSlots,
            ],
            [
                'id' => 'categories',
                'page' => 'homepage',
                'title' => 'Shop by Category',
                'description' => 'Circular category thumbnails displayed on the homepage and catalog.',
                'badge' => 'Category-Driven',
                'is_category_section' => true,
            ],
            [
                'id' => 'custom_crochet',
                'page' => 'homepage',
                'title' => 'Custom Crochet Banner',
                'description' => 'Middle promotional banner ("Custom Crochet Just for You") with request button and hanging badge.',
                'badge' => 'Promotional Banner',
                'slots' => [
                    [
                        'slot' => 'custom_crochet_visual',
                        'title' => 'Custom Crochet Banner Visual',
                        'description' => 'Showcase image for "Custom Crochet Just for You" banner.',
                        'recommended_dimensions' => '1200 × 800',
                        'device' => 'all',
                        'page' => 'homepage',
                        'section' => 'custom_crochet',
                        'sort_order' => 1,
                    ],
                ]
            ],
            [
                'id' => 'brand_story',
                'page' => 'homepage',
                'title' => 'Brand Story ("Every Stitch Has a Story")',
                'description' => 'Artisan storytelling section with background craftsmanship visual and features.',
                'badge' => 'Storytelling',
                'slots' => [
                    [
                        'slot' => 'brand_story_desktop',
                        'title' => 'Brand Story Desktop Background',
                        'description' => 'Artisanal background image for Brand Story section.',
                        'recommended_dimensions' => '1920 × 700',
                        'device' => 'desktop',
                        'page' => 'homepage',
                        'section' => 'brand_story',
                        'sort_order' => 1,
                    ],
                ]
            ],
            [
                'id' => 'bestsellers',
                'page' => 'homepage',
                'title' => 'Best Sellers',
                'description' => 'Product-driven section. Images and pricing are managed via Products.',
                'badge' => 'Product-Driven',
                'is_product_section' => true,
            ],
            [
                'id' => 'trust_benefits',
                'page' => 'homepage',
                'title' => 'Trust & Benefits',
                'description' => 'Highlights (Handmade with Love, 100% Pure Cotton, Happiness Guaranteed).',
                'badge' => 'Icon & Content Driven',
                'is_content_section' => true,
            ],
            [
                'id' => 'custom_order',
                'page' => 'homepage',
                'title' => 'Custom Order ("Your Idea. Our Yarn.")',
                'description' => 'Manage headline titles, badge tagline, description, and button links for bespoke custom orders.',
                'badge' => 'Text & CTA Driven',
                'is_text_only_section' => true,
                'metadata' => (function() {
                    $m = Media::where('page', 'homepage')->where('section', 'custom_order')->first();
                    return [
                        'id' => $m ? $m->id : null,
                        'page' => 'homepage',
                        'section' => 'custom_order',
                        'slot' => 'default',
                        'badge' => $m && $m->tag_text ? $m->tag_text : 'Bespoke Handcrafting',
                        'tag_text' => $m && $m->tag_text ? $m->tag_text : 'Bespoke Handcrafting',
                        'title' => $m && $m->title ? $m->title : 'Your Idea.',
                        'title_line1' => $m && $m->title ? $m->title : 'Your Idea.',
                        'subtitle' => $m && $m->subtitle ? $m->subtitle : 'Our Yarn.',
                        'title_line2' => $m && $m->subtitle ? $m->subtitle : 'Our Yarn.',
                        'description' => $m && $m->description ? $m->description : "From custom color palettes and personalized initials to unique floral bouquets and character plushies — let's create something made especially for you.",
                        'cta_text' => $m && $m->cta_text ? $m->cta_text : 'Start a Custom Order',
                        'cta_link' => $m && $m->cta_link ? $m->cta_link : '/custom-order',
                        'is_active' => $m ? (bool)$m->is_active : true,
                    ];
                })(),
            ],
            [
                'id' => 'newsletter',
                'page' => 'homepage',
                'title' => 'Newsletter / Creative Journey',
                'description' => 'Manage newsletter heading, description, coupon code badge, and subscription settings.',
                'badge' => 'Text & Form Driven',
                'is_text_only_section' => true,
                'metadata' => (function() {
                    $m = Media::where('page', 'homepage')->where('section', 'newsletter')->first();
                    return [
                        'id' => $m ? $m->id : null,
                        'page' => 'homepage',
                        'section' => 'newsletter',
                        'slot' => 'default',
                        'title' => $m && $m->title ? $m->title : 'Join Our Creative Journey',
                        'subtitle' => $m && $m->subtitle ? $m->subtitle : 'Get updates on new products, offers and handmade stories.',
                        'description' => $m && $m->description ? $m->description : 'Get updates on new products, offers and handmade stories.',
                        'tag_text' => $m && $m->tag_text ? $m->tag_text : 'KNOTELLE10',
                        'cta_text' => $m && $m->cta_text ? $m->cta_text : 'Subscribe',
                        'cta_link' => $m && $m->cta_link ? $m->cta_link : '',
                        'is_active' => $m ? (bool)$m->is_active : true,
                    ];
                })(),
            ],
            [
                'id' => 'testimonials',
                'page' => 'homepage',
                'title' => 'Customer Reviews & Testimonials',
                'description' => 'Manage verified customer reviews, 5-star ratings, patron stories, and quotes displayed on the website carousel.',
                'badge' => 'Dynamic Reviews',
                'is_testimonial_section' => true,
                'testimonials' => Testimonial::orderBy('id', 'desc')->get(),
            ],
            [
                'id' => 'blog_reels',
                'page' => 'homepage',
                'title' => 'BLOG / VIDEOS & REELS',
                'description' => 'Manage website blogs, videos, reels and social-style content.',
                'badge' => 'Video & Reels Dynamic Stream',
                'is_blog_reels_section' => true,
                'metadata' => (function() {
                    $m = Media::where('page', 'homepage')->where('section', 'blog_reels')->where('slot', 'section_settings')->first();
                    return [
                        'id' => $m ? $m->id : null,
                        'page' => 'homepage',
                        'section' => 'blog_reels',
                        'slot' => 'section_settings',
                        'title' => $m && $m->title ? $m->title : 'Behind the Stitches',
                        'subtitle' => $m && $m->subtitle ? $m->subtitle : 'Watch our artisans hand-craft each creation, styling guides, and cozy studio ASMR unboxings.',
                        'tag_text' => $m && $m->tag_text ? $m->tag_text : 'Studio Journal & Video Reels',
                        'cta_text' => $m && $m->cta_text ? $m->cta_text : 'Follow @knotelleindia',
                        'cta_link' => $m && $m->cta_link ? $m->cta_link : 'https://instagram.com/knotelleindia',
                        'is_active' => $m ? (bool)$m->is_active : true,
                    ];
                })(),
                'items' => Media::where('page', 'homepage')
                    ->where('section', 'blog_reels')
                    ->where('slot', '!=', 'section_settings')
                    ->orderBy('sort_order', 'asc')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'title' => $item->title,
                            'tagline' => $item->tag_text ?: $item->subtitle,
                            'subtitle' => $item->subtitle,
                            'description' => $item->description,
                            'content_type' => $item->content_type ?: 'reel',
                            'category' => $item->category_name ?: 'Studio ASMR',
                            'category_name' => $item->category_name ?: 'Studio ASMR',
                            'thumbnail' => $item->desktop_image_url ?: $item->url,
                            'thumbnail_url' => $item->desktop_image_url ?: $item->url,
                            'mobile_thumbnail' => $item->mobile_image_url ?: ($item->desktop_image_url ?: $item->url),
                            'video_url' => $item->video_stream_url ?: $item->video_url,
                            'video_path' => $item->video_url,
                            'duration' => $item->duration ?: '00:48',
                            'audio_name' => $item->audio_name ?: 'Original Sound',
                            'audio_track' => $item->audio_name ?: 'Original Sound',
                            'likes' => $item->likes_count ?: 0,
                            'likes_formatted' => $item->formatted_likes,
                            'comments' => $item->comments_count ?: 0,
                            'views' => $item->views_count ?: 0,
                            'views_formatted' => $item->formatted_views,
                            'is_featured' => (bool)$item->is_featured,
                            'is_active' => (bool)$item->is_active,
                            'status' => (bool)$item->is_active,
                            'sort_order' => (int)$item->sort_order,
                            'file_size_formatted' => $this->formatSize($item->file_size ?: 0),
                            'updated_at_formatted' => $item->updated_at ? $item->updated_at->format('M d, Y') : '',
                        ];
                    }),
            ],
            [
                'id' => 'footer',
                'page' => 'homepage',
                'title' => 'Footer Artwork',
                'description' => 'Full-width panoramic boutique footer artwork and background illustration.',
                'badge' => 'Footer Background',
                'slots' => [
                    [
                        'slot' => 'footer_bg',
                        'title' => 'Footer Panoramic Background',
                        'description' => 'Full-width panoramic background artwork image in footer across the entire store.',
                        'recommended_dimensions' => '1920 × 600',
                        'device' => 'all',
                        'page' => 'homepage',
                        'section' => 'footer',
                        'sort_order' => 1,
                    ],
                ]
            ],
            [
                'id' => 'global_assets',
                'page' => 'global',
                'title' => 'Global Site Assets',
                'description' => 'Brand logos, favicons, and default fallback images used across all pages.',
                'badge' => 'Site-Wide',
                'slots' => [
                    [
                        'slot' => 'main_logo',
                        'title' => 'Primary Boutique Logo (Crown)',
                        'description' => 'Main logo used in navbar, footer, and branding.',
                        'recommended_dimensions' => '300 × 120',
                        'device' => 'all',
                        'page' => 'global',
                        'section' => 'global_assets',
                        'sort_order' => 1,
                    ],
                    [
                        'slot' => 'mobile_logo',
                        'title' => 'Mobile Navbar Logo',
                        'description' => 'Compact logo for small screens (defaults to primary logo).',
                        'recommended_dimensions' => '180 × 80',
                        'device' => 'mobile',
                        'page' => 'global',
                        'section' => 'global_assets',
                        'sort_order' => 2,
                    ],
                    [
                        'slot' => 'favicon',
                        'title' => 'Browser Favicon',
                        'description' => 'Browser tab favicon icon.',
                        'recommended_dimensions' => '64 × 64',
                        'device' => 'all',
                        'page' => 'global',
                        'section' => 'global_assets',
                        'sort_order' => 3,
                    ],
                ]
            ],

            // SHOP PAGE SECTIONS
            [
                'id' => 'shop_banner',
                'page' => 'shop',
                'title' => 'Shop Page Header & Banner',
                'description' => 'Main panoramic visual banner, headline, subtitle, and badge at top of the catalog.',
                'badge' => 'Catalog Header',
                'slots' => [
                    [
                        'slot' => 'shop_banner_desktop',
                        'title' => 'Shop Catalog Desktop Banner',
                        'description' => 'Top panoramic catalog banner with headline, tagline badge, and decorative artwork.',
                        'recommended_dimensions' => '1920 × 400',
                        'device' => 'desktop',
                        'page' => 'shop',
                        'section' => 'shop_banner',
                        'sort_order' => 1,
                    ],
                    [
                        'slot' => 'shop_banner_mobile',
                        'title' => 'Shop Catalog Mobile Banner',
                        'description' => 'Mobile-optimized header visual.',
                        'recommended_dimensions' => '768 × 500',
                        'device' => 'mobile',
                        'page' => 'shop',
                        'section' => 'shop_banner',
                        'sort_order' => 1,
                    ],
                ]
            ],
            [
                'id' => 'shop_promo',
                'page' => 'shop',
                'title' => 'Shop In-Catalog Promo Banner',
                'description' => 'Mid-catalog promotional callout for custom gifts or seasonal highlights.',
                'badge' => 'Promotional',
                'slots' => [
                    [
                        'slot' => 'shop_promo_banner',
                        'title' => 'In-Catalog Promotional Banner',
                        'description' => 'Middle banner across the product listing.',
                        'recommended_dimensions' => '1200 × 300',
                        'device' => 'all',
                        'page' => 'shop',
                        'section' => 'shop_promo',
                        'sort_order' => 1,
                    ],
                ]
            ],

            // CUSTOM ORDER PAGE SECTIONS
            [
                'id' => 'custom_order_header',
                'page' => 'custom_order',
                'title' => 'Custom Order Header & Banner',
                'description' => 'Top bespoke order banner with headline ("Your Idea. Our Yarn.") and artisan introduction.',
                'badge' => 'Page Header',
                'slots' => [
                    [
                        'slot' => 'custom_order_hero_desktop',
                        'title' => 'Custom Order Desktop Banner',
                        'description' => 'Artisanal header banner visual for bespoke creation request form.',
                        'recommended_dimensions' => '1920 × 500',
                        'device' => 'desktop',
                        'page' => 'custom_order',
                        'section' => 'custom_order_header',
                        'sort_order' => 1,
                    ],
                    [
                        'slot' => 'custom_order_hero_mobile',
                        'title' => 'Custom Order Mobile Banner',
                        'description' => 'Mobile header visual for bespoke creation request form.',
                        'recommended_dimensions' => '768 × 600',
                        'device' => 'mobile',
                        'page' => 'custom_order',
                        'section' => 'custom_order_header',
                        'sort_order' => 1,
                    ],
                ]
            ],
            [
                'id' => 'custom_order_showcase',
                'page' => 'custom_order',
                'title' => 'Custom Order Craft Process Visual',
                'description' => 'Handcrafted process photography and quality craftsmanship guarantee visual.',
                'badge' => 'Process Showcase',
                'slots' => [
                    [
                        'slot' => 'custom_order_process_visual',
                        'title' => 'Craft Process Showcase Image',
                        'description' => 'Photography illustrating our custom artisan hand-stitching process.',
                        'recommended_dimensions' => '1000 × 700',
                        'device' => 'all',
                        'page' => 'custom_order',
                        'section' => 'custom_order_showcase',
                        'sort_order' => 1,
                    ],
                ]
            ],

            // ABOUT PAGE SECTIONS
            [
                'id' => 'about_story',
                'page' => 'about',
                'title' => 'About Page Atelier & Story',
                'description' => 'The KNOTELLE Story ("Every Loop Tells a Story") artisan photography and mission.',
                'badge' => 'Story & Atelier',
                'slots' => [
                    [
                        'slot' => 'about_hero_image',
                        'title' => 'Main Artisan Crafting Visual',
                        'description' => 'Primary showcase photo of artisan stitching with wooden hook.',
                        'recommended_dimensions' => '1000 × 1100',
                        'device' => 'all',
                        'page' => 'about',
                        'section' => 'about_story',
                        'sort_order' => 1,
                    ],
                    [
                        'slot' => 'about_studio_image',
                        'title' => 'Bengaluru Workshop & Studio',
                        'description' => 'Atelier workshop photo and yarn gallery.',
                        'recommended_dimensions' => '1200 × 600',
                        'device' => 'all',
                        'page' => 'about',
                        'section' => 'about_story',
                        'sort_order' => 2,
                    ],
                ]
            ],

            // CONTACT PAGE SECTIONS
            [
                'id' => 'contact_header',
                'page' => 'contact',
                'title' => 'Contact Page Banner & Atelier',
                'description' => 'Header visual ("Let\'s Connect"), studio info, and response guarantee.',
                'badge' => 'Contact & Studio',
                'slots' => [
                    [
                        'slot' => 'contact_banner_image',
                        'title' => 'Contact Header Banner Visual',
                        'description' => 'Decorative top banner with headline and tagline.',
                        'recommended_dimensions' => '1920 × 400',
                        'device' => 'desktop',
                        'page' => 'contact',
                        'section' => 'contact_header',
                        'sort_order' => 1,
                    ],
                    [
                        'slot' => 'contact_studio_image',
                        'title' => 'Studio Storefront Photo',
                        'description' => 'Indiranagar studio physical location photo.',
                        'recommended_dimensions' => '800 × 600',
                        'device' => 'all',
                        'page' => 'contact',
                        'section' => 'contact_header',
                        'sort_order' => 2,
                    ],
                ]
            ],
        ];

        // 2. Fetch assigned media grouped by slot
        $allMedia = Media::whereNotNull('slot')->get();
        $mediaBySlot = [];

        foreach ($allMedia as $m) {
            if ($m->page && $m->section && $m->slot) {
                $key = "{$m->page}_{$m->section}_{$m->slot}";
                if (!isset($mediaBySlot[$key]) || $m->is_active) {
                    $mediaBySlot[$key] = [
                        'id' => $m->id,
                        'url' => $m->url,
                        'file_name' => $m->file_name,
                        'desktop_image' => $m->desktop_image_url ?: $m->url,
                        'mobile_image' => $m->mobile_image_url ?: ($m->desktop_image_url ?: $m->url),
                        'mobile_image_path' => $m->mobile_image_path,
                        'title' => $m->title,
                        'tagline' => $m->tag_text ?: $m->subtitle,
                        'subtitle' => $m->subtitle,
                        'description' => $m->description,
                        'cta_text' => $m->cta_text,
                        'cta_link' => $m->cta_link,
                        'primary_button_text' => $m->cta_text ?: 'Shop Now',
                        'primary_button_link' => $m->cta_link ?: '/shop',
                        'secondary_cta_text' => $m->secondary_cta_text,
                        'secondary_cta_link' => $m->secondary_cta_link,
                        'secondary_button_text' => $m->secondary_cta_text ?: 'Explore Collections',
                        'secondary_button_link' => $m->secondary_cta_link ?: '/shop',
                        'tag_text' => $m->tag_text,
                        'alt_text' => $m->alt_text,
                        'device' => $m->device,
                        'sort_order' => $m->sort_order,
                        'size_formatted' => $this->formatSize($m->file_size ?: 0),
                        'mime_type' => $m->mime_type,
                        'is_active' => (bool)$m->is_active,
                        'status' => (bool)$m->is_active,
                        'updated_at_formatted' => $m->updated_at ? $m->updated_at->format('M d, Y') : '',
                    ];
                }
            }
        }

        // Attach media to slots
        foreach ($sections as &$sec) {
            if (isset($sec['slots'])) {
                foreach ($sec['slots'] as &$slotDef) {
                    $key = "{$slotDef['page']}_{$slotDef['section']}_{$slotDef['slot']}";
                    if (isset($mediaBySlot[$key])) {
                        $slotDef['media'] = $mediaBySlot[$key];
                    } elseif (isset($slotDef['media_id'])) {
                        $found = $allMedia->firstWhere('id', $slotDef['media_id']);
                        if ($found) {
                            $slotDef['media'] = [
                                'id' => $found->id,
                                'url' => $found->url,
                                'file_name' => $found->file_name,
                                'desktop_image' => $found->desktop_image_url ?: $found->url,
                                'mobile_image' => $found->mobile_image_url ?: ($found->desktop_image_url ?: $found->url),
                                'mobile_image_path' => $found->mobile_image_path,
                                'title' => $found->title,
                                'tagline' => $found->tag_text ?: $found->subtitle,
                                'subtitle' => $found->subtitle,
                                'description' => $found->description,
                                'cta_text' => $found->cta_text,
                                'cta_link' => $found->cta_link,
                                'primary_button_text' => $found->cta_text ?: 'Shop Now',
                                'primary_button_link' => $found->cta_link ?: '/shop',
                                'secondary_cta_text' => $found->secondary_cta_text,
                                'secondary_cta_link' => $found->secondary_cta_link,
                                'secondary_button_text' => $found->secondary_cta_text ?: 'Explore Collections',
                                'secondary_button_link' => $found->secondary_cta_link ?: '/shop',
                                'tag_text' => $found->tag_text,
                                'alt_text' => $found->alt_text,
                                'device' => $found->device,
                                'sort_order' => $found->sort_order,
                                'size_formatted' => $this->formatSize($found->file_size ?: 0),
                                'mime_type' => $found->mime_type,
                                'is_active' => (bool)$found->is_active,
                                'status' => (bool)$found->is_active,
                                'updated_at_formatted' => $found->updated_at ? $found->updated_at->format('M d, Y') : '',
                            ];
                        }
                    }
                }
            }
        }

        // 3. Fetch Categories
        $categories = Category::with('image')->orderBy('sort_order', 'asc')->get()->map(function ($cat) {
            $imageUrl = null;
            if ($cat->image && $cat->image->file_path) {
                $imageUrl = $cat->image->url;
            } else {
                $imageUrl = asset('images/categories/' . $cat->slug . '.jpg');
            }

            return [
                'id' => $cat->id,
                'name' => $cat->name,
                'slug' => $cat->slug,
                'image_id' => $cat->image_id,
                'image_url' => $imageUrl,
                'status' => (bool)$cat->status,
                'sort_order' => $cat->sort_order ?: 1,
                'product_count' => $cat->products()->where('status', 'active')->count(),
            ];
        });

        // 4. Fetch Best Sellers from Products
        $bestSellers = Product::with(['defaultVariant.images'])
            ->where('status', 'active')
            ->where(function ($q) {
                $q->where('is_bestseller', true)->orWhere('is_featured', true);
            })
            ->limit(8)
            ->get()
            ->map(function ($p) {
                $img = null;
                if ($p->defaultVariant && $p->defaultVariant->images->isNotEmpty()) {
                    $img = $p->defaultVariant->images->first()->url;
                }
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'price' => $p->defaultVariant ? $p->defaultVariant->price : 0,
                    'image' => $img ?: asset('images/logo/Logo_1.png'),
                    'is_bestseller' => (bool)$p->is_bestseller,
                    'is_featured' => (bool)$p->is_featured,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'sections' => $sections,
                'categories' => $categories,
                'best_sellers' => $bestSellers,
                'total_media' => $allMedia->count(),
            ]
        ]);
    }

    /**
     * Assign or Upload image directly to a slot
     */
    public function assignSlot(Request $request): JsonResponse
    {
        $request->validate([
            'page' => 'required|string',
            'section' => 'required|string',
            'slot' => 'required|string',
            'device' => 'nullable|string',
            'media_id' => 'nullable',
            'file' => 'nullable|file|max:20480',
            'title' => 'nullable|string',
            'subtitle' => 'nullable|string',
            'description' => 'nullable|string',
            'cta_text' => 'nullable|string',
            'cta_link' => 'nullable|string',
            'tag_text' => 'nullable|string',
            'alt_text' => 'nullable|string',
        ]);

        $page = $request->input('page');
        $section = $request->input('section');
        $slot = $request->input('slot');
        $device = $request->input('device', 'all');
        $title = $request->input('title');
        $subtitle = $request->input('subtitle');
        $description = $request->input('description');
        $ctaText = $request->input('cta_text');
        $ctaLink = $request->input('cta_link');
        $tagText = $request->input('tag_text');
        $altText = $request->input('alt_text');

        // If file uploaded directly
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
            $uniqueName = "{$section}_{$slot}_" . time() . '_' . Str::random(4) . '.' . $extension;

            $targetFolder = 'images/' . ($section === 'hero' ? 'hero' : ($section === 'footer' ? 'footer' : ($section === 'categories' ? 'categories' : 'homepage')));
            $targetDirectory = public_path($targetFolder);
            if (!File::isDirectory($targetDirectory)) {
                File::makeDirectory($targetDirectory, 0755, true, true);
            }

            $file->move($targetDirectory, $uniqueName);
            $relPath = $targetFolder . '/' . $uniqueName;
            $fileSize = file_exists($targetDirectory . DIRECTORY_SEPARATOR . $uniqueName) ? filesize($targetDirectory . DIRECTORY_SEPARATOR . $uniqueName) : 0;

            // Deactivate existing media on this slot
            Media::where('page', $page)
                ->where('section', $section)
                ->where('slot', $slot)
                ->update(['is_active' => false]);

            $media = Media::create([
                'file_name' => $originalName,
                'file_path' => $relPath,
                'disk' => 'local',
                'mime_type' => 'image/' . ($extension === 'png' ? 'png' : ($extension === 'webp' ? 'webp' : ($extension === 'svg' ? 'svg+xml' : 'jpeg'))),
                'file_type' => 'image',
                'file_size' => $fileSize,
                'alt_text' => $altText ?: $title ?: pathinfo($originalName, PATHINFO_FILENAME),
                'page' => $page,
                'section' => $section,
                'slot' => $slot,
                'device' => $device,
                'title' => $title ?: ucfirst(str_replace('_', ' ', $slot)),
                'subtitle' => $subtitle,
                'description' => $description,
                'cta_text' => $ctaText,
                'cta_link' => $ctaLink,
                'tag_text' => $tagText,
                'is_active' => true,
                'uploaded_by' => auth()->id() ?: 1,
                'uploader_type' => 'admin',
            ]);

            if ($section === 'categories' || str_starts_with($slot, 'category_')) {
                $catSlug = str_replace('category_', '', $slot);
                Category::where('slug', $catSlug)->update(['image_id' => $media->id]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded and applied to slot successfully!',
                'data' => [
                    'media_id' => $media->id,
                    'url' => $media->url,
                    'slot' => $slot,
                ]
            ]);
        }

        // If assigning an existing media record or updating without a new file
        $media = null;
        if ($request->filled('media_id') && is_numeric($request->input('media_id'))) {
            $media = Media::find($request->input('media_id'));
        }

        if (!$media) {
            $media = Media::where('page', $page)
                ->where('section', $section)
                ->where('slot', $slot)
                ->first();
        }

        if ($media) {
            Media::where('page', $page)
                ->where('section', $section)
                ->where('slot', $slot)
                ->where('id', '!=', $media->id)
                ->update(['is_active' => false]);

            $media->update([
                'page' => $page,
                'section' => $section,
                'slot' => $slot,
                'device' => $device,
                'title' => $title ?: $media->title ?: ucfirst(str_replace('_', ' ', $slot)),
                'subtitle' => $subtitle !== null ? $subtitle : $media->subtitle,
                'description' => $description !== null ? $description : $media->description,
                'cta_text' => $ctaText !== null ? $ctaText : $media->cta_text,
                'cta_link' => $ctaLink !== null ? $ctaLink : $media->cta_link,
                'tag_text' => $tagText !== null ? $tagText : $media->tag_text,
                'alt_text' => $altText !== null ? $altText : $media->alt_text,
                'is_active' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Slot configured and updated successfully!',
                'data' => [
                    'media_id' => $media->id,
                    'url' => $media->url,
                    'slot' => $slot,
                ]
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Please select a file to upload.'], 400);
    }

    /**
     * Update Media Metadata & Rich Section Content
     */
    public function updateMetadata(Request $request, $id = null): JsonResponse
    {
        $request->validate([
            'title' => 'nullable|string',
            'subtitle' => 'nullable|string',
            'description' => 'nullable|string',
            'cta_text' => 'nullable|string',
            'cta_link' => 'nullable|string',
            'tag_text' => 'nullable|string',
            'alt_text' => 'nullable|string',
            'device' => 'nullable|string|in:all,desktop,mobile,tablet',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable',
            'page' => 'nullable|string',
            'section' => 'nullable|string',
            'slot' => 'nullable|string',
        ]);

        $media = null;
        if ($id && is_numeric($id) && (int)$id > 0) {
            $media = Media::find($id);
        }

        if (!$media && $request->filled('slot')) {
            $media = Media::where('page', $request->input('page', 'homepage'))
                ->where('section', $request->input('section'))
                ->where('slot', $request->input('slot'))
                ->first();
        }

        if (!$media && $request->filled('section')) {
            $media = Media::where('page', $request->input('page', 'homepage'))
                ->where('section', $request->input('section'))
                ->first();
        }

        if (!$media && ($request->filled('slot') || $request->filled('section'))) {
            $media = Media::create([
                'file_name' => ($request->input('slot') ?: $request->input('section')) . '.png',
                'file_path' => 'images/logo/Logo_1.png',
                'disk' => 'local',
                'mime_type' => 'image/png',
                'file_type' => 'image',
                'page' => $request->input('page', 'homepage'),
                'section' => $request->input('section', 'homepage'),
                'slot' => $request->input('slot') ?: 'default',
                'device' => $request->input('device', 'all'),
                'title' => $request->input('title', ucfirst(str_replace('_', ' ', $request->input('slot') ?: $request->input('section')))),
                'is_active' => true,
                'uploaded_by' => auth()->id() ?: 1,
                'uploader_type' => 'admin',
            ]);
        }

        if (!$media) {
            return response()->json(['success' => false, 'message' => 'Media record not found.'], 404);
        }

        $updateData = [];
        if ($request->has('title')) $updateData['title'] = $request->input('title');
        if ($request->has('subtitle')) $updateData['subtitle'] = $request->input('subtitle');
        if ($request->has('description')) $updateData['description'] = $request->input('description');
        if ($request->has('cta_text')) $updateData['cta_text'] = $request->input('cta_text');
        if ($request->has('cta_link')) $updateData['cta_link'] = $request->input('cta_link');
        if ($request->has('tag_text')) $updateData['tag_text'] = $request->input('tag_text');
        if ($request->has('alt_text')) $updateData['alt_text'] = $request->input('alt_text');
        if ($request->has('device')) $updateData['device'] = $request->input('device');
        if ($request->has('sort_order')) $updateData['sort_order'] = $request->input('sort_order');
        if ($request->has('is_active')) $updateData['is_active'] = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN);

        $media->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Section options and metadata saved successfully!',
            'data' => $media
        ]);
    }

    /**
     * Detach Media from Slot safely (does NOT physically delete file)
     */
    public function detachSlot(Request $request): JsonResponse
    {
        $request->validate([
            'page' => 'required|string',
            'section' => 'required|string',
            'slot' => 'required|string',
        ]);

        Media::where('page', $request->input('page'))
            ->where('section', $request->input('section'))
            ->where('slot', $request->input('slot'))
            ->update([
                'slot' => null,
                'is_active' => false,
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Image detached from slot successfully. Fallback image will be used.'
        ]);
    }

    /**
     * Toggle Media Active Status
     */
    public function toggleStatus($id): JsonResponse
    {
        $media = Media::findOrFail($id);
        $media->is_active = !$media->is_active;
        $media->save();

        return response()->json([
            'success' => true,
            'message' => 'Status changed to ' . ($media->is_active ? 'Active' : 'Inactive'),
            'is_active' => $media->is_active
        ]);
    }

    /**
     * Update Category Image / Metadata from Media Manager
     */
    public function updateCategory(Request $request, $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
            $uniqueName = "{$category->slug}_" . time() . '.' . $extension;

            $targetDirectory = public_path('images/categories');
            if (!File::isDirectory($targetDirectory)) {
                File::makeDirectory($targetDirectory, 0755, true, true);
            }

            $file->move($targetDirectory, $uniqueName);
            $relPath = 'images/categories/' . $uniqueName;

            $media = Media::create([
                'file_name' => $originalName,
                'file_path' => $relPath,
                'disk' => 'local',
                'mime_type' => 'image/' . ($extension === 'png' ? 'png' : 'jpeg'),
                'file_type' => 'image',
                'file_size' => filesize($targetDirectory . DIRECTORY_SEPARATOR . $uniqueName),
                'alt_text' => $category->name . ' Category',
                'page' => 'homepage',
                'section' => 'categories',
                'slot' => 'category_' . $category->slug,
                'device' => 'all',
                'title' => $category->name . ' Category Image',
                'is_active' => true,
                'uploaded_by' => auth()->id() ?: 1,
                'uploader_type' => 'admin',
            ]);

            $category->image_id = $media->id;
        } elseif ($request->filled('media_id')) {
            $category->image_id = $request->input('media_id');
        }

        if ($request->has('sort_order')) {
            $category->sort_order = (int) $request->input('sort_order');
        }

        if ($request->has('status')) {
            $category->status = filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
        }

        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully!',
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'image_url' => $category->image ? $category->image->url : asset('images/categories/' . $category->slug . '.jpg'),
                'status' => (bool)$category->status,
                'sort_order' => $category->sort_order,
            ]
        ]);
    }

    /**
     * Add a new Hero Carousel Slide
     */
    public function addHeroSlide(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string',
            'tagline' => 'nullable|string',
            'subtitle' => 'nullable|string',
            'description' => 'nullable|string',
            'primary_button_text' => 'nullable|string',
            'primary_button_link' => 'nullable|string',
            'secondary_button_text' => 'nullable|string',
            'secondary_button_link' => 'nullable|string',
            'cta_text' => 'nullable|string',
            'cta_link' => 'nullable|string',
            'tag_text' => 'nullable|string',
            'alt_text' => 'nullable|string',
            'status' => 'nullable',
            'is_active' => 'nullable',
            'sort_order' => 'nullable',
            'file' => 'nullable|file|max:20480',
            'desktop_image' => 'nullable|file|max:20480',
            'mobile_file' => 'nullable|file|max:20480',
            'mobile_image' => 'nullable|file|max:20480',
            'desktop_media_id' => 'nullable',
            'mobile_media_id' => 'nullable',
        ]);

        $maxSort = Media::where('page', 'homepage')->where('section', 'hero')->max('sort_order') ?: 0;
        $sortOrder = $request->filled('sort_order') && (int)$request->input('sort_order') > 0
            ? (int)$request->input('sort_order')
            : ($maxSort + 1);

        $title = $request->input('title') ?: ('Hero Slide ' . $sortOrder);
        $tagline = $request->input('tagline') ?: ($request->input('tag_text') ?: ($request->input('subtitle') ?: 'Good Things Are Handmade'));
        $description = $request->input('description') ?: ($request->input('subtitle') ?: '');
        $primaryBtnText = $request->input('primary_button_text') ?: ($request->input('cta_text') ?: 'Shop Now');
        $primaryBtnLink = $request->input('primary_button_link') ?: ($request->input('cta_link') ?: '/shop');
        $secondaryBtnText = $request->input('secondary_button_text') ?: ($request->input('secondary_cta_text') ?: 'Explore Collections');
        $secondaryBtnLink = $request->input('secondary_button_link') ?: ($request->input('secondary_cta_link') ?: '/shop');
        $altText = $request->input('alt_text') ?: $title;
        $isActive = $request->has('status') 
            ? filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN)
            : ($request->has('is_active') ? filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN) : true);

        // Handle Desktop Image
        $desktopRelPath = 'images/hero/hero-enhanced.jpg';
        $originalName = 'hero-slide.jpg';
        $mimeType = 'image/jpeg';
        $fileSize = 0;

        $targetFolder = 'images/hero';
        $targetDirectory = public_path($targetFolder);
        if (!File::isDirectory($targetDirectory)) {
            File::makeDirectory($targetDirectory, 0755, true, true);
        }

        $desktopFile = $request->file('file') ?: $request->file('desktop_image');
        if ($desktopFile) {
            $originalName = $desktopFile->getClientOriginalName();
            $ext = strtolower($desktopFile->getClientOriginalExtension() ?: 'jpg');
            $uniqueName = 'hero_desktop_' . $sortOrder . '_' . time() . '.' . $ext;
            $desktopFile->move($targetDirectory, $uniqueName);
            $desktopRelPath = $targetFolder . '/' . $uniqueName;
            $mimeType = 'image/' . ($ext === 'png' ? 'png' : ($ext === 'webp' ? 'webp' : ($ext === 'svg' ? 'svg+xml' : 'jpeg')));
            $fileSize = file_exists($targetDirectory . '/' . $uniqueName) ? filesize($targetDirectory . '/' . $uniqueName) : 0;
        } elseif ($request->filled('desktop_media_id') && is_numeric($request->input('desktop_media_id'))) {
            $existingMedia = Media::find($request->input('desktop_media_id'));
            if ($existingMedia) {
                $desktopRelPath = $existingMedia->file_path;
                $originalName = $existingMedia->file_name;
                $mimeType = $existingMedia->mime_type;
                $fileSize = $existingMedia->file_size;
            }
        } elseif ($request->filled('desktop_image_path')) {
            $desktopRelPath = $request->input('desktop_image_path');
        }

        // Handle Mobile Image
        $mobileRelPath = null;
        $mobileFile = $request->file('mobile_file') ?: $request->file('mobile_image');
        if ($mobileFile) {
            $mobileExt = strtolower($mobileFile->getClientOriginalExtension() ?: 'jpg');
            $mobileUniqueName = 'hero_mobile_' . $sortOrder . '_' . time() . '.' . $mobileExt;
            $mobileFile->move($targetDirectory, $mobileUniqueName);
            $mobileRelPath = $targetFolder . '/' . $mobileUniqueName;
        } elseif ($request->filled('mobile_media_id') && is_numeric($request->input('mobile_media_id'))) {
            $existingMobileMedia = Media::find($request->input('mobile_media_id'));
            if ($existingMobileMedia) {
                $mobileRelPath = $existingMobileMedia->file_path;
            }
        } elseif ($request->filled('mobile_image_path')) {
            $mobileRelPath = $request->input('mobile_image_path');
        }

        $slotName = 'hero_slide_' . $sortOrder . '_' . time();

        $media = Media::create([
            'file_name' => $originalName,
            'file_path' => $desktopRelPath,
            'mobile_image_path' => $mobileRelPath,
            'disk' => 'local',
            'mime_type' => $mimeType,
            'file_type' => 'image',
            'file_size' => $fileSize,
            'alt_text' => $altText,
            'page' => 'homepage',
            'section' => 'hero',
            'slot' => $slotName,
            'device' => 'all',
            'title' => $title,
            'subtitle' => $description,
            'description' => $description,
            'cta_text' => $primaryBtnText,
            'cta_link' => $primaryBtnLink,
            'secondary_cta_text' => $secondaryBtnText,
            'secondary_cta_link' => $secondaryBtnLink,
            'tag_text' => $tagline,
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
            'uploaded_by' => auth()->id() ?: 1,
            'uploader_type' => 'admin',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Hero slide created successfully.',
            'data' => [
                'id' => $media->id,
                'media_id' => $media->id,
                'title' => $media->title,
                'tagline' => $media->tag_text,
                'url' => $media->url,
                'desktop_image' => $media->desktop_image_url,
                'mobile_image' => $media->mobile_image_url,
                'status' => (bool)$media->is_active,
                'sort_order' => $media->sort_order,
                'slot' => $slotName,
            ]
        ]);
    }

    /**
     * Get a single Hero Slide data for editing
     */
    public function getHeroSlide($id): JsonResponse
    {
        $media = Media::where('page', 'homepage')
            ->where('section', 'hero')
            ->where('id', $id)
            ->first();

        if (!$media) {
            $media = Media::find($id);
        }

        if (!$media) {
            return response()->json(['success' => false, 'message' => 'Hero slide not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $media->id,
                'title' => $media->title,
                'tagline' => $media->tag_text ?: $media->subtitle,
                'subtitle' => $media->subtitle,
                'description' => $media->description,
                'primary_button_text' => $media->cta_text ?: 'Shop Now',
                'primary_button_link' => $media->cta_link ?: '/shop',
                'secondary_button_text' => $media->secondary_cta_text ?: 'Explore Collections',
                'secondary_button_link' => $media->secondary_cta_link ?: '/shop',
                'cta_text' => $media->cta_text,
                'cta_link' => $media->cta_link,
                'secondary_cta_text' => $media->secondary_cta_text,
                'secondary_cta_link' => $media->secondary_cta_link,
                'tag_text' => $media->tag_text,
                'alt_text' => $media->alt_text,
                'desktop_image' => $media->desktop_image_url,
                'mobile_image' => $media->mobile_image_url,
                'desktop_image_path' => $media->file_path,
                'mobile_image_path' => $media->mobile_image_path,
                'status' => (bool)$media->is_active,
                'is_active' => (bool)$media->is_active,
                'sort_order' => (int)$media->sort_order,
            ]
        ]);
    }

    /**
     * Update an existing Hero Slide
     */
    public function updateHeroSlide(Request $request, $id): JsonResponse
    {
        $media = Media::where('page', 'homepage')
            ->where('section', 'hero')
            ->where('id', $id)
            ->first();

        if (!$media) {
            $media = Media::find($id);
        }

        if (!$media) {
            return response()->json(['success' => false, 'message' => 'Hero slide not found.'], 404);
        }

        $request->validate([
            'title' => 'required|string',
            'tagline' => 'nullable|string',
            'subtitle' => 'nullable|string',
            'description' => 'nullable|string',
            'primary_button_text' => 'nullable|string',
            'primary_button_link' => 'nullable|string',
            'secondary_button_text' => 'nullable|string',
            'secondary_button_link' => 'nullable|string',
            'cta_text' => 'nullable|string',
            'cta_link' => 'nullable|string',
            'tag_text' => 'nullable|string',
            'alt_text' => 'nullable|string',
            'status' => 'nullable',
            'is_active' => 'nullable',
            'sort_order' => 'nullable',
            'file' => 'nullable|file|max:20480',
            'desktop_image' => 'nullable|file|max:20480',
            'mobile_file' => 'nullable|file|max:20480',
            'mobile_image' => 'nullable|file|max:20480',
        ]);

        $targetFolder = 'images/hero';
        $targetDirectory = public_path($targetFolder);
        if (!File::isDirectory($targetDirectory)) {
            File::makeDirectory($targetDirectory, 0755, true, true);
        }

        $desktopFile = $request->file('file') ?: $request->file('desktop_image');
        if ($desktopFile) {
            $ext = strtolower($desktopFile->getClientOriginalExtension() ?: 'jpg');
            $uniqueName = 'hero_desktop_' . ($media->sort_order ?: $media->id) . '_' . time() . '.' . $ext;
            $desktopFile->move($targetDirectory, $uniqueName);
            $media->file_path = $targetFolder . '/' . $uniqueName;
            $media->file_name = $desktopFile->getClientOriginalName();
            $media->mime_type = 'image/' . ($ext === 'png' ? 'png' : ($ext === 'webp' ? 'webp' : ($ext === 'svg' ? 'svg+xml' : 'jpeg')));
            $media->file_size = file_exists($targetDirectory . '/' . $uniqueName) ? filesize($targetDirectory . '/' . $uniqueName) : 0;
        } elseif ($request->filled('desktop_media_id') && is_numeric($request->input('desktop_media_id'))) {
            $chosen = Media::find($request->input('desktop_media_id'));
            if ($chosen) {
                $media->file_path = $chosen->file_path;
                $media->file_name = $chosen->file_name;
                $media->mime_type = $chosen->mime_type;
                $media->file_size = $chosen->file_size;
            }
        }

        $mobileFile = $request->file('mobile_file') ?: $request->file('mobile_image');
        if ($mobileFile) {
            $mobileExt = strtolower($mobileFile->getClientOriginalExtension() ?: 'jpg');
            $mobileUniqueName = 'hero_mobile_' . ($media->sort_order ?: $media->id) . '_' . time() . '.' . $mobileExt;
            $mobileFile->move($targetDirectory, $mobileUniqueName);
            $media->mobile_image_path = $targetFolder . '/' . $mobileUniqueName;
        } elseif ($request->filled('mobile_media_id') && is_numeric($request->input('mobile_media_id'))) {
            $chosenMobile = Media::find($request->input('mobile_media_id'));
            if ($chosenMobile) {
                $media->mobile_image_path = $chosenMobile->file_path;
            }
        }

        if ($request->has('title')) $media->title = $request->input('title');
        if ($request->has('tagline')) $media->tag_text = $request->input('tagline');
        elseif ($request->has('tag_text')) $media->tag_text = $request->input('tag_text');
        
        if ($request->has('description')) {
            $media->description = $request->input('description');
            $media->subtitle = $request->input('description');
        } elseif ($request->has('subtitle')) {
            $media->subtitle = $request->input('subtitle');
            $media->description = $request->input('subtitle');
        }

        if ($request->has('primary_button_text')) $media->cta_text = $request->input('primary_button_text');
        elseif ($request->has('cta_text')) $media->cta_text = $request->input('cta_text');

        if ($request->has('primary_button_link')) $media->cta_link = $request->input('primary_button_link');
        elseif ($request->has('cta_link')) $media->cta_link = $request->input('cta_link');

        if ($request->has('secondary_button_text')) $media->secondary_cta_text = $request->input('secondary_button_text');
        elseif ($request->has('secondary_cta_text')) $media->secondary_cta_text = $request->input('secondary_cta_text');

        if ($request->has('secondary_button_link')) $media->secondary_cta_link = $request->input('secondary_button_link');
        elseif ($request->has('secondary_cta_link')) $media->secondary_cta_link = $request->input('secondary_cta_link');

        if ($request->has('alt_text')) $media->alt_text = $request->input('alt_text');

        if ($request->has('sort_order') && (int)$request->input('sort_order') > 0) {
            $media->sort_order = (int)$request->input('sort_order');
        }

        if ($request->has('status')) {
            $media->is_active = filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN);
        } elseif ($request->has('is_active')) {
            $media->is_active = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN);
        }

        $media->save();

        return response()->json([
            'success' => true,
            'message' => 'Hero slide updated successfully.',
            'data' => [
                'id' => $media->id,
                'title' => $media->title,
                'tagline' => $media->tag_text,
                'url' => $media->url,
                'desktop_image' => $media->desktop_image_url,
                'mobile_image' => $media->mobile_image_url,
                'status' => (bool)$media->is_active,
                'sort_order' => $media->sort_order,
            ]
        ]);
    }

    /**
     * Delete a Hero Slide
     */
    public function deleteHeroSlide($id): JsonResponse
    {
        $media = Media::where('page', 'homepage')
            ->where('section', 'hero')
            ->where('id', $id)
            ->first();

        if (!$media) {
            $media = Media::find($id);
        }

        if (!$media) {
            return response()->json([
                'success' => false,
                'message' => 'Hero slide not found.'
            ], 404);
        }

        // Delete physical file if custom upload
        if (str_starts_with($media->file_path, 'images/hero/') && file_exists(public_path($media->file_path))) {
            @unlink(public_path($media->file_path));
        }

        if ($media->mobile_image_path && str_starts_with($media->mobile_image_path, 'images/hero/') && file_exists(public_path($media->mobile_image_path))) {
            @unlink(public_path($media->mobile_image_path));
        }

        $media->delete();

        return response()->json([
            'success' => true,
            'message' => 'Hero slide deleted successfully!'
        ]);
    }

    /**
     * Get Media Data for Modals/AJAX (directly from MySQL Database)
     */
    public function getData(Request $request)
    {
        $query = Media::query()->latest('id');

        // Page/Section filters
        if ($request->filled('page') && $request->page !== 'all') {
            $query->where('page', $request->page);
        }
        if ($request->filled('section') && $request->section !== 'all') {
            $query->where('section', $request->section);
        }
        if ($request->filled('device') && $request->device !== 'all') {
            $query->whereIn('device', [$request->device, 'all']);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('file_name', 'like', "%{$search}%")
                  ->orWhere('alt_text', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('section', 'like', "%{$search}%")
                  ->orWhere('slot', 'like', "%{$search}%")
                  ->orWhere('file_path', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('file_type', $request->type);
        }

        $perPage = (int) $request->input('per_page', 24);
        $mediaPaginated = $query->paginate($perPage);

        $data = $mediaPaginated->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'url' => $item->url,
                'thumbnail_url' => $item->thumb_url,
                'thumb_url' => $item->thumb_url,
                'full_url' => $item->full_url,
                'file_path' => $item->file_path,
                'file_name' => $item->file_name,
                'filename' => $item->file_name,
                'name' => $item->title ?: $item->file_name,
                'title' => $item->title,
                'page' => $item->page,
                'section' => $item->section,
                'slot' => $item->slot,
                'device' => $item->device,
                'is_active' => (bool)$item->is_active,
                'sort_order' => $item->sort_order,
                'mime_type' => $item->mime_type ?: 'image/jpeg',
                'size' => $item->file_size ?: 0,
                'size_formatted' => $this->formatSize($item->file_size ?: 0),
                'is_image' => str_starts_with($item->mime_type ?? 'image/', 'image/'),
                'created_at' => $item->created_at ? $item->created_at->toIso8601String() : now()->toIso8601String(),
                'created_at_formatted' => $item->created_at ? $item->created_at->format('M d, Y') : '',
                'alt_text' => $item->alt_text,
            ];
        });

        return response()->json([
            'current_page' => $mediaPaginated->currentPage(),
            'data' => $data,
            'last_page' => $mediaPaginated->lastPage(),
            'total' => $mediaPaginated->total(),
            'per_page' => $mediaPaginated->perPage(),
            'from' => $mediaPaginated->firstItem(),
            'to' => $mediaPaginated->lastItem(),
            'prev_page_url' => $mediaPaginated->previousPageUrl(),
            'next_page_url' => $mediaPaginated->nextPageUrl(),
            'links' => $mediaPaginated->linkCollection()->toArray(),
        ]);
    }

    /**
     * Upload Media from Desktop/Mobile Gallery
     */
    public function upload(Request $request)
    {
        $request->validate([
            'files' => 'nullable|array',
            'files.*' => 'file|max:20480',
            'file' => 'nullable|file|max:20480',
            'page' => 'nullable|string',
            'section' => 'nullable|string',
            'slot' => 'nullable|string',
            'device' => 'nullable|string',
        ]);

        $files = [];
        if ($request->hasFile('files')) {
            $files = $request->file('files');
        } elseif ($request->hasFile('file')) {
            $files = [$request->file('file')];
        }

        if (empty($files)) {
            return response()->json(['success' => false, 'message' => 'No files were uploaded.'], 400);
        }

        $page = $request->input('page', 'homepage');
        $section = $request->input('section', 'unassigned');
        $slot = $request->input('slot', 'default');
        $device = $request->input('device', 'all');

        $uploadedMedia = [];
        $errors = [];

        $targetFolder = 'images/' . ($section === 'hero' ? 'hero' : ($section === 'categories' ? 'categories' : ($section === 'footer' ? 'footer' : 'products')));
        $targetDirectory = public_path($targetFolder);
        if (!File::isDirectory($targetDirectory)) {
            File::makeDirectory($targetDirectory, 0755, true, true);
        }

        foreach ($files as $file) {
            try {
                $originalName = $file->getClientOriginalName();
                $extension = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'jpg');
                $rawName = pathinfo($originalName, PATHINFO_FILENAME);
                $cleanSlug = Str::slug($rawName);
                $uniqueName = ($cleanSlug ?: 'knotelle-media') . '_' . time() . '_' . Str::random(4) . '.' . $extension;

                $file->move($targetDirectory, $uniqueName);
                $relPath = $targetFolder . '/' . $uniqueName;
                $fullDiskPath = $targetDirectory . DIRECTORY_SEPARATOR . $uniqueName;
                $fileSize = file_exists($fullDiskPath) ? filesize($fullDiskPath) : 0;

                $media = Media::create([
                    'file_name' => $originalName,
                    'file_path' => $relPath,
                    'disk' => 'local',
                    'mime_type' => 'image/' . ($extension === 'png' ? 'png' : ($extension === 'webp' ? 'webp' : 'jpeg')),
                    'file_type' => 'image',
                    'file_size' => $fileSize,
                    'alt_text' => $rawName,
                    'page' => $page,
                    'section' => $section,
                    'slot' => $slot,
                    'device' => $device,
                    'title' => $rawName,
                    'is_active' => true,
                    'uploaded_by' => auth()->id() ?: 1,
                    'uploader_type' => 'admin',
                ]);

                $uploadedMedia[] = [
                    'id' => $media->id,
                    'url' => $media->url,
                    'thumb_url' => $media->thumb_url,
                    'thumbnail_url' => $media->thumbnail_url,
                    'full_url' => $media->full_url,
                    'file_name' => $originalName,
                    'size_formatted' => $this->formatSize($fileSize),
                ];
            } catch (\Exception $e) {
                $errors[] = "Failed to upload {$file->getClientOriginalName()}: " . $e->getMessage();
            }
        }

        $success = count($uploadedMedia) > 0;

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Files uploaded successfully!' : 'Failed to upload files',
            'data' => $success ? $uploadedMedia[0] : null,
            'all_uploaded' => $uploadedMedia,
            'errors' => $errors,
        ]);
    }

    /**
     * Delete Media with Reference Checking
     */
    public function destroy($id)
    {
        $media = Media::findOrFail($id);

        // Check if media is referenced by categories
        if ($media->categories()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete media: It is linked as a primary category image. Please reassign the category first.'
            ], 400);
        }

        // Check if referenced by product variants
        if ($media->variantImages()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete media: It is assigned to one or more Product Variants. Please remove it from the product first.'
            ], 400);
        }

        // Delete physical file if created inside upload folders
        if (str_starts_with($media->file_path, 'images/products/') && file_exists(public_path($media->file_path))) {
            @unlink(public_path($media->file_path));
        }

        $media->delete();

        return response()->json(['success' => true, 'message' => 'Media deleted successfully']);
    }

    /**
     * Helper to auto-sync local Knotelle directory images into database
     */
    public function syncLocalImagesToDatabase(): void
    {
        $folders = [
            'images/products' => 'products',
            'images/categories' => 'categories',
            'images/logo' => 'global_assets',
            'images/hero' => 'hero',
            'images/homepage' => 'homepage',
            'images/footer' => 'footer',
        ];

        foreach ($folders as $relFolder => $group) {
            $absPath = public_path($relFolder);
            if (!File::isDirectory($absPath)) {
                continue;
            }

            $files = File::files($absPath);
            foreach ($files as $file) {
                $fileName = $file->getFilename();
                $filePath = $relFolder . '/' . $fileName;

                $exists = Media::where('file_path', $filePath)->first();
                if (!$exists) {
                    $ext = strtolower($file->getExtension());
                    $mime = ($ext === 'png') ? 'image/png' : (($ext === 'webp') ? 'image/webp' : 'image/jpeg');

                    Media::create([
                        'file_name' => $fileName,
                        'file_path' => $filePath,
                        'disk' => 'local',
                        'mime_type' => $mime,
                        'file_type' => 'image',
                        'file_size' => $file->getSize(),
                        'alt_text' => pathinfo($fileName, PATHINFO_FILENAME),
                        'page' => ($group === 'global_assets') ? 'global' : 'homepage',
                        'section' => $group,
                        'slot' => pathinfo($fileName, PATHINFO_FILENAME),
                        'device' => 'all',
                        'title' => pathinfo($fileName, PATHINFO_FILENAME),
                        'is_active' => true,
                        'uploaded_by' => 1,
                        'uploader_type' => 'admin',
                    ]);
                }
            }
        }
    }

    private function formatSize(int $bytes): string
    {
        if ($bytes <= 0) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB'];
        $pow = floor(log($bytes, 1024));
        return round($bytes / (1024 ** $pow), 1) . ' ' . $units[$pow];
    }

    /**
     * Create or Update Customer Review / Testimonial
     */
    public function saveTestimonial(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'nullable',
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'message' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'is_active' => 'nullable',
        ]);

        $id = $request->input('id');
        $testimonial = ($id && is_numeric($id)) ? Testimonial::find($id) : new Testimonial();

        if (!$testimonial && $id) {
            return response()->json(['success' => false, 'message' => 'Review not found.'], 404);
        }

        if (!$testimonial) {
            $testimonial = new Testimonial();
        }

        $testimonial->name = trim($request->input('name'));
        $testimonial->designation = trim($request->input('designation')) ?: 'Verified Patron';
        $testimonial->message = trim($request->input('message'));
        $testimonial->rating = (int)$request->input('rating', 5);
        $testimonial->image = null; // Removed image option per design specification

        if ($request->has('is_active')) {
            $val = $request->input('is_active');
            $testimonial->is_active = ($val === '1' || $val === 1 || $val === true || $val === 'true' || $val === 'on');
        } else {
            $testimonial->is_active = false;
        }

        $testimonial->save();

        return response()->json([
            'success' => true,
            'message' => $id ? 'Customer review updated successfully!' : 'Customer review added successfully!',
            'data' => $testimonial,
        ]);
    }

    /**
     * Get single testimonial for editing
     */
    public function getTestimonial($id): JsonResponse
    {
        $testimonial = Testimonial::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $testimonial,
        ]);
    }

    /**
     * Delete customer review
     */
    public function deleteTestimonial($id): JsonResponse
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();
        return response()->json([
            'success' => true,
            'message' => 'Customer review deleted successfully.',
        ]);
    }

    /**
     * Toggle customer review active status
     */
    public function toggleTestimonial($id): JsonResponse
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->is_active = !$testimonial->is_active;
        $testimonial->save();
        return response()->json([
            'success' => true,
            'message' => 'Customer review status updated.',
            'is_active' => (bool)$testimonial->is_active,
        ]);
    }

    /**
     * Create / Add a new Video / Reel / Blog Video
     */
    public function addVideoReel(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content_type' => 'nullable|string|in:video,reel,blog_video',
            'category' => 'nullable|string|max:150',
            'category_name' => 'nullable|string|max:150',
            'tagline' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|string|max:50',
            'audio_name' => 'nullable|string|max:255',
            'likes' => 'nullable|integer|min:0',
            'comments' => 'nullable|integer|min:0',
            'views' => 'nullable|integer|min:0',
            'is_featured' => 'nullable',
            'status' => 'nullable',
            'is_active' => 'nullable',
            'sort_order' => 'nullable|integer|min:1',
            'video_file' => 'nullable|file|mimetypes:video/mp4,video/webm,video/quicktime,video/ogg,video/x-matroska,video/x-msvideo|max:102400',
            'video_url' => 'nullable|string|max:500',
            'thumbnail_file' => 'nullable|file|image|max:20480',
            'thumbnail_media_id' => 'nullable',
            'thumbnail_url' => 'nullable|string|max:500',
            'mobile_thumbnail_file' => 'nullable|file|image|max:20480',
        ]);

        $maxSort = Media::where('page', 'homepage')->where('section', 'blog_reels')->where('slot', '!=', 'section_settings')->max('sort_order') ?: 0;
        $sortOrder = $request->filled('sort_order') && (int)$request->input('sort_order') > 0
            ? (int)$request->input('sort_order')
            : ($maxSort + 1);

        $contentType = $request->input('content_type', 'reel');
        $categoryName = $request->input('category_name') ?: ($request->input('category') ?: 'Studio ASMR');
        $title = $request->input('title');
        $tagline = $request->input('tagline') ?: ($request->input('tag_text') ?: ($request->input('subtitle') ?: ''));
        $description = $request->input('description') ?: ($request->input('subtitle') ?: '');
        $duration = $request->input('duration') ?: '00:48';
        $audioName = $request->input('audio_name') ?: 'Original Audio';
        $likes = (int)$request->input('likes', 0);
        $comments = (int)$request->input('comments', 0);
        $views = (int)$request->input('views', 0);
        $isFeatured = $request->has('is_featured') ? filter_var($request->input('is_featured'), FILTER_VALIDATE_BOOLEAN) : false;
        $isActive = $request->has('status') 
            ? filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN)
            : ($request->has('is_active') ? filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN) : true);

        // 1. Handle Video Upload / URL
        $videoUrl = $request->input('video_url');
        $mimeType = 'video/mp4';
        $fileSize = 0;
        $originalFileName = 'video_' . time() . '.mp4';

        if ($request->hasFile('video_file')) {
            $vFile = $request->file('video_file');
            $vOriginal = $vFile->getClientOriginalName();
            $vExt = strtolower($vFile->getClientOriginalExtension() ?: 'mp4');
            $vUniqueName = 'reel_' . $sortOrder . '_' . time() . '_' . Str::random(4) . '.' . $vExt;
            
            $targetDir = public_path('videos/reels');
            if (!File::isDirectory($targetDir)) {
                File::makeDirectory($targetDir, 0755, true, true);
            }

            $vFile->move($targetDir, $vUniqueName);
            $videoUrl = 'videos/reels/' . $vUniqueName;
            $mimeType = $vFile->getClientMimeType() ?: ('video/' . ($vExt === 'mov' ? 'quicktime' : $vExt));
            $fileSize = file_exists($targetDir . '/' . $vUniqueName) ? filesize($targetDir . '/' . $vUniqueName) : 0;
            $originalFileName = $vOriginal;
        }

        // 2. Handle Thumbnail Image Upload / Media Library
        $thumbnailPath = 'images/homepage/middleimg.png';
        $thumbDir = public_path('images/reels');
        if (!File::isDirectory($thumbDir)) {
            File::makeDirectory($thumbDir, 0755, true, true);
        }

        if ($request->hasFile('thumbnail_file')) {
            $tFile = $request->file('thumbnail_file');
            $tExt = strtolower($tFile->getClientOriginalExtension() ?: 'jpg');
            $tUniqueName = 'cover_' . $sortOrder . '_' . time() . '_' . Str::random(4) . '.' . $tExt;
            $tFile->move($thumbDir, $tUniqueName);
            $thumbnailPath = 'images/reels/' . $tUniqueName;
        } elseif ($request->filled('thumbnail_media_id') && is_numeric($request->input('thumbnail_media_id'))) {
            $chosen = Media::find($request->input('thumbnail_media_id'));
            if ($chosen) {
                $thumbnailPath = $chosen->file_path;
            }
        } elseif ($request->filled('thumbnail_url')) {
            $thumbnailPath = $request->input('thumbnail_url');
        }

        // 3. Handle Mobile Thumbnail
        $mobileThumbPath = null;
        if ($request->hasFile('mobile_thumbnail_file')) {
            $mFile = $request->file('mobile_thumbnail_file');
            $mExt = strtolower($mFile->getClientOriginalExtension() ?: 'jpg');
            $mUniqueName = 'mobile_cover_' . $sortOrder . '_' . time() . '_' . Str::random(4) . '.' . $mExt;
            $mFile->move($thumbDir, $mUniqueName);
            $mobileThumbPath = 'images/reels/' . $mUniqueName;
        }

        $slotName = 'reel_' . $sortOrder . '_' . time();

        $media = Media::create([
            'file_name' => $originalFileName,
            'file_path' => $thumbnailPath,
            'mobile_image_path' => $mobileThumbPath,
            'video_url' => $videoUrl,
            'disk' => 'local',
            'mime_type' => $mimeType,
            'file_type' => 'video',
            'content_type' => $contentType,
            'category_name' => $categoryName,
            'file_size' => $fileSize,
            'duration' => $duration,
            'audio_name' => $audioName,
            'likes_count' => $likes,
            'comments_count' => $comments,
            'views_count' => $views,
            'alt_text' => $title,
            'page' => 'homepage',
            'section' => 'blog_reels',
            'slot' => $slotName,
            'device' => 'all',
            'title' => $title,
            'subtitle' => $tagline,
            'description' => $description,
            'tag_text' => $tagline,
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
            'is_featured' => $isFeatured,
            'uploaded_by' => auth()->id() ?: 1,
            'uploader_type' => 'admin',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Video / Reel content created successfully!',
            'data' => [
                'id' => $media->id,
                'title' => $media->title,
                'content_type' => $media->content_type,
                'category' => $media->category_name,
                'video_url' => $media->video_stream_url,
                'thumbnail' => $media->desktop_image_url,
                'mobile_thumbnail' => $media->mobile_image_url,
                'duration' => $media->duration,
                'likes' => $media->likes_count,
                'comments' => $media->comments_count,
                'views' => $media->views_count,
                'is_featured' => (bool)$media->is_featured,
                'status' => (bool)$media->is_active,
                'sort_order' => $media->sort_order,
            ]
        ]);
    }

    /**
     * Get single Video / Reel for editing
     */
    public function getVideoReel($id): JsonResponse
    {
        $media = Media::where('page', 'homepage')
            ->where('section', 'blog_reels')
            ->where('id', $id)
            ->first();

        if (!$media) {
            $media = Media::find($id);
        }

        if (!$media) {
            return response()->json(['success' => false, 'message' => 'Video/Reel not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $media->id,
                'title' => $media->title,
                'tagline' => $media->tag_text ?: $media->subtitle,
                'subtitle' => $media->subtitle,
                'description' => $media->description,
                'content_type' => $media->content_type ?: 'reel',
                'category' => $media->category_name ?: 'Studio ASMR',
                'category_name' => $media->category_name ?: 'Studio ASMR',
                'thumbnail' => $media->desktop_image_url ?: $media->url,
                'thumbnail_url' => $media->desktop_image_url ?: $media->url,
                'thumbnail_path' => $media->file_path,
                'mobile_thumbnail' => $media->mobile_image_url ?: ($media->desktop_image_url ?: $media->url),
                'mobile_thumbnail_path' => $media->mobile_image_path,
                'video_url' => $media->video_stream_url ?: $media->video_url,
                'video_path' => $media->video_url,
                'duration' => $media->duration ?: '00:48',
                'audio_name' => $media->audio_name ?: 'Original Audio',
                'likes' => (int)$media->likes_count,
                'comments' => (int)$media->comments_count,
                'views' => (int)$media->views_count,
                'is_featured' => (bool)$media->is_featured,
                'status' => (bool)$media->is_active,
                'is_active' => (bool)$media->is_active,
                'sort_order' => (int)$media->sort_order,
            ]
        ]);
    }

    /**
     * Update an existing Video / Reel
     */
    public function updateVideoReel(Request $request, $id): JsonResponse
    {
        $media = Media::where('page', 'homepage')
            ->where('section', 'blog_reels')
            ->where('id', $id)
            ->first();

        if (!$media) {
            $media = Media::find($id);
        }

        if (!$media) {
            return response()->json(['success' => false, 'message' => 'Video/Reel not found.'], 404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content_type' => 'nullable|string|in:video,reel,blog_video',
            'category' => 'nullable|string|max:150',
            'category_name' => 'nullable|string|max:150',
            'tagline' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|string|max:50',
            'audio_name' => 'nullable|string|max:255',
            'likes' => 'nullable|integer|min:0',
            'comments' => 'nullable|integer|min:0',
            'views' => 'nullable|integer|min:0',
            'is_featured' => 'nullable',
            'status' => 'nullable',
            'is_active' => 'nullable',
            'sort_order' => 'nullable|integer|min:1',
            'video_file' => 'nullable|file|mimetypes:video/mp4,video/webm,video/quicktime,video/ogg,video/x-matroska,video/x-msvideo|max:102400',
            'video_url' => 'nullable|string|max:500',
            'thumbnail_file' => 'nullable|file|image|max:20480',
            'thumbnail_media_id' => 'nullable',
            'thumbnail_url' => 'nullable|string|max:500',
            'mobile_thumbnail_file' => 'nullable|file|image|max:20480',
        ]);

        // 1. Video Replacement
        if ($request->hasFile('video_file')) {
            $vFile = $request->file('video_file');
            $vExt = strtolower($vFile->getClientOriginalExtension() ?: 'mp4');
            $vUniqueName = 'reel_' . ($media->sort_order ?: $media->id) . '_' . time() . '_' . Str::random(4) . '.' . $vExt;
            
            $targetDir = public_path('videos/reels');
            if (!File::isDirectory($targetDir)) {
                File::makeDirectory($targetDir, 0755, true, true);
            }

            // Remove previous custom video if local
            if ($media->video_url && str_starts_with($media->video_url, 'videos/reels/') && file_exists(public_path($media->video_url))) {
                @unlink(public_path($media->video_url));
            }

            $vFile->move($targetDir, $vUniqueName);
            $media->video_url = 'videos/reels/' . $vUniqueName;
            $media->mime_type = $vFile->getClientMimeType() ?: ('video/' . ($vExt === 'mov' ? 'quicktime' : $vExt));
            $media->file_size = file_exists($targetDir . '/' . $vUniqueName) ? filesize($targetDir . '/' . $vUniqueName) : 0;
            $media->file_name = $vFile->getClientOriginalName();
        } elseif ($request->filled('video_url')) {
            $media->video_url = $request->input('video_url');
        }

        // 2. Thumbnail Replacement
        $thumbDir = public_path('images/reels');
        if (!File::isDirectory($thumbDir)) {
            File::makeDirectory($thumbDir, 0755, true, true);
        }

        if ($request->hasFile('thumbnail_file')) {
            $tFile = $request->file('thumbnail_file');
            $tExt = strtolower($tFile->getClientOriginalExtension() ?: 'jpg');
            $tUniqueName = 'cover_' . ($media->sort_order ?: $media->id) . '_' . time() . '_' . Str::random(4) . '.' . $tExt;
            
            if ($media->file_path && str_starts_with($media->file_path, 'images/reels/') && file_exists(public_path($media->file_path))) {
                @unlink(public_path($media->file_path));
            }

            $tFile->move($thumbDir, $tUniqueName);
            $media->file_path = 'images/reels/' . $tUniqueName;
        } elseif ($request->filled('thumbnail_media_id') && is_numeric($request->input('thumbnail_media_id'))) {
            $chosen = Media::find($request->input('thumbnail_media_id'));
            if ($chosen) {
                $media->file_path = $chosen->file_path;
            }
        } elseif ($request->filled('thumbnail_url')) {
            $media->file_path = $request->input('thumbnail_url');
        }

        // 3. Mobile Thumbnail Replacement
        if ($request->hasFile('mobile_thumbnail_file')) {
            $mFile = $request->file('mobile_thumbnail_file');
            $mExt = strtolower($mFile->getClientOriginalExtension() ?: 'jpg');
            $mUniqueName = 'mobile_cover_' . ($media->sort_order ?: $media->id) . '_' . time() . '_' . Str::random(4) . '.' . $mExt;

            if ($media->mobile_image_path && str_starts_with($media->mobile_image_path, 'images/reels/') && file_exists(public_path($media->mobile_image_path))) {
                @unlink(public_path($media->mobile_image_path));
            }

            $mFile->move($thumbDir, $mUniqueName);
            $media->mobile_image_path = 'images/reels/' . $mUniqueName;
        }

        // Content Updates
        if ($request->has('title')) $media->title = $request->input('title');
        if ($request->has('content_type')) $media->content_type = $request->input('content_type');
        if ($request->has('category_name')) $media->category_name = $request->input('category_name');
        elseif ($request->has('category')) $media->category_name = $request->input('category');

        if ($request->has('tagline')) {
            $media->tag_text = $request->input('tagline');
            $media->subtitle = $request->input('tagline');
        } elseif ($request->has('subtitle')) {
            $media->subtitle = $request->input('subtitle');
            $media->tag_text = $request->input('subtitle');
        }

        if ($request->has('description')) $media->description = $request->input('description');
        if ($request->has('duration')) $media->duration = $request->input('duration');
        if ($request->has('audio_name')) $media->audio_name = $request->input('audio_name');
        if ($request->has('likes')) $media->likes_count = (int)$request->input('likes');
        if ($request->has('comments')) $media->comments_count = (int)$request->input('comments');
        if ($request->has('views')) $media->views_count = (int)$request->input('views');
        
        if ($request->has('is_featured')) {
            $media->is_featured = filter_var($request->input('is_featured'), FILTER_VALIDATE_BOOLEAN);
        }

        if ($request->has('status')) {
            $media->is_active = filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN);
        } elseif ($request->has('is_active')) {
            $media->is_active = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN);
        }

        if ($request->has('sort_order') && (int)$request->input('sort_order') > 0) {
            $media->sort_order = (int)$request->input('sort_order');
        }

        $media->save();

        return response()->json([
            'success' => true,
            'message' => 'Video / Reel updated successfully!',
            'data' => [
                'id' => $media->id,
                'title' => $media->title,
                'content_type' => $media->content_type,
                'category' => $media->category_name,
                'video_url' => $media->video_stream_url,
                'thumbnail' => $media->desktop_image_url,
                'mobile_thumbnail' => $media->mobile_image_url,
                'duration' => $media->duration,
                'likes' => $media->likes_count,
                'comments' => $media->comments_count,
                'views' => $media->views_count,
                'is_featured' => (bool)$media->is_featured,
                'status' => (bool)$media->is_active,
                'sort_order' => $media->sort_order,
            ]
        ]);
    }

    /**
     * Delete a Video / Reel
     */
    public function deleteVideoReel($id): JsonResponse
    {
        $media = Media::where('page', 'homepage')
            ->where('section', 'blog_reels')
            ->where('id', $id)
            ->first();

        if (!$media) {
            $media = Media::find($id);
        }

        if (!$media) {
            return response()->json([
                'success' => false,
                'message' => 'Video/Reel not found.'
            ], 404);
        }

        // Delete physical video if stored in dedicated custom upload folder
        if ($media->video_url && str_starts_with($media->video_url, 'videos/reels/') && file_exists(public_path($media->video_url))) {
            $otherUsing = Media::where('video_url', $media->video_url)->where('id', '!=', $media->id)->exists();
            if (!$otherUsing) {
                @unlink(public_path($media->video_url));
            }
        }

        // Delete physical thumbnail if custom uploaded
        if ($media->file_path && str_starts_with($media->file_path, 'images/reels/') && file_exists(public_path($media->file_path))) {
            $otherUsingImg = Media::where('file_path', $media->file_path)->where('id', '!=', $media->id)->exists();
            if (!$otherUsingImg) {
                @unlink(public_path($media->file_path));
            }
        }

        $media->delete();

        return response()->json([
            'success' => true,
            'message' => 'Video / Reel deleted successfully!'
        ]);
    }

    /**
     * Toggle Video / Reel Active Status
     */
    public function toggleVideoReelStatus($id): JsonResponse
    {
        $media = Media::findOrFail($id);
        $media->is_active = !$media->is_active;
        $media->save();

        return response()->json([
            'success' => true,
            'message' => 'Video / Reel status changed to ' . ($media->is_active ? 'Active' : 'Inactive'),
            'is_active' => (bool)$media->is_active
        ]);
    }

    /**
     * Update Section Header Settings for Blog / Videos & Reels
     */
    public function updateBlogReelsSettings(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'tag_text' => 'nullable|string|max:150',
            'cta_text' => 'nullable|string|max:100',
            'cta_link' => 'nullable|string|max:255',
            'is_active' => 'nullable',
        ]);

        $settings = Media::where('page', 'homepage')
            ->where('section', 'blog_reels')
            ->where('slot', 'section_settings')
            ->first();

        if (!$settings) {
            $settings = new Media();
            $settings->page = 'homepage';
            $settings->section = 'blog_reels';
            $settings->slot = 'section_settings';
            $settings->file_name = 'blog_reels_settings.png';
            $settings->file_path = 'images/logo/Logo_1.png';
            $settings->disk = 'local';
            $settings->mime_type = 'image/png';
            $settings->file_type = 'image';
            $settings->uploaded_by = auth()->id() ?: 1;
            $settings->uploader_type = 'admin';
        }

        $settings->title = $request->input('title', 'Behind the Stitches');
        $settings->subtitle = $request->input('subtitle', 'Watch our artisans hand-craft each creation, styling guides, and cozy studio ASMR unboxings.');
        $settings->description = $request->input('subtitle');
        $settings->tag_text = $request->input('tag_text', 'Studio Journal & Video Reels');
        $settings->cta_text = $request->input('cta_text', 'Follow @knotelleindia');
        $settings->cta_link = $request->input('cta_link', 'https://instagram.com/knotelleindia');
        $settings->is_active = $request->has('is_active') ? filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN) : true;
        $settings->save();

        return response()->json([
            'success' => true,
            'message' => 'Behind the Stitches section settings updated successfully!',
            'data' => [
                'title' => $settings->title,
                'subtitle' => $settings->subtitle,
                'tag_text' => $settings->tag_text,
                'cta_text' => $settings->cta_text,
                'cta_link' => $settings->cta_link,
                'is_active' => (bool)$settings->is_active,
            ]
        ]);
    }
}
