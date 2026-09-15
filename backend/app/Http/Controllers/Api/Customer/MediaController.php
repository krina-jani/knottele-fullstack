<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Category;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Get structured website media for customer storefront.
     */
    public function index(Request $request): JsonResponse
    {
        // 1. Hero Slides
        $heroMedia = Media::where('page', 'homepage')
            ->where('section', 'hero')
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $heroSlides = [];
        $heroDesktop = null;
        $heroMobile = null;

        if ($heroMedia->isNotEmpty()) {
            foreach ($heroMedia as $idx => $m) {
                $desktopUrl = $m->desktop_image_url ?: $m->url;
                $mobileUrl = $m->mobile_image_url ?: $desktopUrl;
                $tagline = $m->tag_text ?: ($m->subtitle ?: 'Good Things Are Handmade');
                $primaryBtnText = $m->cta_text ?: 'Shop Now';
                $primaryBtnLink = $m->cta_link ?: '/shop';
                $secondaryBtnText = $m->secondary_cta_text ?: 'Explore Collections';
                $secondaryBtnLink = $m->secondary_cta_link ?: '/shop';

                $slideData = [
                    'id' => $m->id,
                    'title' => $m->title ?: 'Hero Slide ' . ($idx + 1),
                    'tagline' => $tagline,
                    'subtitle' => $m->subtitle ?: ($m->description ?: ''),
                    'description' => $m->description ?: ($m->subtitle ?: ''),
                    'primary_button_text' => $primaryBtnText,
                    'primary_button_link' => $primaryBtnLink,
                    'secondary_button_text' => $secondaryBtnText,
                    'secondary_button_link' => $secondaryBtnLink,
                    'cta_text' => $primaryBtnText,
                    'cta_link' => $primaryBtnLink,
                    'secondary_cta_text' => $secondaryBtnText,
                    'secondary_cta_link' => $secondaryBtnLink,
                    'tag_text' => $tagline,
                    'alt' => $m->alt_text ?: ($m->title ?: 'Knotelle Handmade Boutique'),
                    'desktop' => $desktopUrl,
                    'desktop_image' => $desktopUrl,
                    'mobile' => $mobileUrl,
                    'mobile_image' => $mobileUrl,
                    'status' => (bool)$m->is_active,
                    'is_active' => (bool)$m->is_active,
                    'sort_order' => (int)$m->sort_order,
                ];
                $heroSlides[] = $slideData;

                if (!$heroDesktop) {
                    $heroDesktop = $desktopUrl;
                }
                if (!$heroMobile) {
                    $heroMobile = $mobileUrl;
                }
            }
        }

        // Fallbacks for Hero if empty
        if (empty($heroSlides)) {
            $fallbackSlides = [
                ['id' => 1, 'desktop' => asset('images/hero/hero-enhanced.jpg'), 'desktop_image' => asset('images/hero/hero-enhanced.jpg'), 'mobile' => asset('images/hero/hero-enhanced.jpg'), 'mobile_image' => asset('images/hero/hero-enhanced.jpg'), 'title' => 'Little Stitches Big Happiness', 'tagline' => 'Good Things Are Handmade', 'subtitle' => 'Handmade crochet products that add warmth to your everyday life.', 'description' => 'Handmade crochet products that add warmth to your everyday life.', 'primary_button_text' => 'Shop All Collections', 'primary_button_link' => '/shop', 'secondary_button_text' => 'Explore Collections', 'secondary_button_link' => '/shop', 'cta_text' => 'Shop All Collections', 'cta_link' => '/shop', 'tag_text' => 'Good Things Are Handmade', 'alt' => 'Little Stitches Big Happiness', 'status' => true, 'is_active' => true, 'sort_order' => 1],
                ['id' => 2, 'desktop' => asset('images/hero/2image.png'), 'desktop_image' => asset('images/hero/2image.png'), 'mobile' => asset('images/hero/2image.png'), 'mobile_image' => asset('images/hero/2image.png'), 'title' => 'Everyday Elegance Handcrafted Bags', 'tagline' => 'Every Stitch Has A Story', 'subtitle' => 'Artisanal granny square bags & wearable creations woven with love.', 'description' => 'Artisanal granny square bags & wearable creations woven with love.', 'primary_button_text' => 'Explore Bags', 'primary_button_link' => '/category/bags', 'secondary_button_text' => 'Shop Collections', 'secondary_button_link' => '/shop', 'cta_text' => 'Explore Bags', 'cta_link' => '/category/bags', 'tag_text' => 'Every Stitch Has A Story', 'alt' => 'Everyday Elegance Handcrafted Bags', 'status' => true, 'is_active' => true, 'sort_order' => 2],
                ['id' => 3, 'desktop' => asset('images/hero/3image.png'), 'desktop_image' => asset('images/hero/3image.png'), 'mobile' => asset('images/hero/3image.png'), 'mobile_image' => asset('images/hero/3image.png'), 'title' => 'Everlasting Blooms Made With Love', 'tagline' => 'Crafted Just For You', 'subtitle' => 'Bespoke everlasting flower bouquets crafted to brighten every moment.', 'description' => 'Bespoke everlasting flower bouquets crafted to brighten every moment.', 'primary_button_text' => 'Shop Flowers', 'primary_button_link' => '/category/flower', 'secondary_button_text' => 'Explore Collections', 'secondary_button_link' => '/shop', 'cta_text' => 'Shop Flowers', 'cta_link' => '/category/flower', 'tag_text' => 'Crafted Just For You', 'alt' => 'Everlasting Blooms Made With Love', 'status' => true, 'is_active' => true, 'sort_order' => 3],
                ['id' => 4, 'desktop' => asset('images/hero/4image.png'), 'desktop_image' => asset('images/hero/4image.png'), 'mobile' => asset('images/hero/4image.png'), 'mobile_image' => asset('images/hero/4image.png'), 'title' => 'Bespoke Creations Custom Crafted', 'tagline' => 'Pure Artisan Warmth', 'subtitle' => 'Personalized crochet treasures tailored specially for your memorable moments.', 'description' => 'Personalized crochet treasures tailored specially for your memorable moments.', 'primary_button_text' => 'Request Custom Order', 'primary_button_link' => '/custom-order', 'secondary_button_text' => 'Shop Now', 'secondary_button_link' => '/shop', 'cta_text' => 'Request Custom Order', 'cta_link' => '/custom-order', 'tag_text' => 'Pure Artisan Warmth', 'alt' => 'Bespoke Creations Custom Crafted', 'status' => true, 'is_active' => true, 'sort_order' => 4],
            ];
            $heroSlides = $fallbackSlides;
            $heroDesktop = $fallbackSlides[0]['desktop'];
            $heroMobile = $fallbackSlides[0]['mobile'];
        }

        if (!$heroMobile) {
            $heroMobile = $heroDesktop;
        }

        // 2. Custom Crochet Banner
        $customCrochetMedia = Media::where('page', 'homepage')
            ->where('section', 'custom_crochet')
            ->where('is_active', true)
            ->first();

        $customCrochet = [
            'desktop' => $customCrochetMedia ? $customCrochetMedia->url : asset('images/homepage/middleimg.png'),
            'mobile' => $customCrochetMedia ? $customCrochetMedia->url : asset('images/homepage/middleimg.png'),
            'title' => $customCrochetMedia && $customCrochetMedia->title ? $customCrochetMedia->title : 'Custom Crochet',
            'subtitle' => $customCrochetMedia && $customCrochetMedia->subtitle ? $customCrochetMedia->subtitle : 'Just for You',
            'description' => $customCrochetMedia && $customCrochetMedia->description ? $customCrochetMedia->description : "Your imagination, our yarn. Let's create something special together.",
            'cta_text' => $customCrochetMedia && $customCrochetMedia->cta_text ? $customCrochetMedia->cta_text : 'Request Your Custom Order',
            'cta_link' => $customCrochetMedia && $customCrochetMedia->cta_link ? $customCrochetMedia->cta_link : '/custom-order',
            'tag_text' => $customCrochetMedia && $customCrochetMedia->tag_text ? $customCrochetMedia->tag_text : 'Turn Your Ideas Into Handmade Reality',
            'is_active' => $customCrochetMedia ? (bool)$customCrochetMedia->is_active : true,
        ];

        // 3. Brand Story
        $brandStoryMedia = Media::where('page', 'homepage')
            ->where('section', 'brand_story')
            ->where('is_active', true)
            ->first();

        $brandStory = [
            'desktop' => $brandStoryMedia ? $brandStoryMedia->url : asset('images/homepage/middleimg.png'),
            'mobile' => $brandStoryMedia ? $brandStoryMedia->url : asset('images/homepage/middleimg.png'),
            'title' => $brandStoryMedia && $brandStoryMedia->title ? $brandStoryMedia->title : 'Every Stitch',
            'subtitle' => $brandStoryMedia && $brandStoryMedia->subtitle ? $brandStoryMedia->subtitle : 'Has a Story',
            'description' => $brandStoryMedia && $brandStoryMedia->description ? $brandStoryMedia->description : 'More than just crochet, we create memories, happiness and a little bit of magic.',
            'cta_text' => $brandStoryMedia && $brandStoryMedia->cta_text ? $brandStoryMedia->cta_text : 'Read Our Story',
            'cta_link' => $brandStoryMedia && $brandStoryMedia->cta_link ? $brandStoryMedia->cta_link : '/about',
            'is_active' => $brandStoryMedia ? (bool)$brandStoryMedia->is_active : true,
        ];

        // 4. Custom Order CTA
        $customOrderMedia = Media::where('page', 'homepage')
            ->where('section', 'custom_order')
            ->where('is_active', true)
            ->first();

        $customOrder = [
            'desktop' => $customOrderMedia ? $customOrderMedia->url : null,
            'mobile' => $customOrderMedia ? $customOrderMedia->url : null,
            'badge' => $customOrderMedia && $customOrderMedia->tag_text ? $customOrderMedia->tag_text : 'Bespoke Handcrafting',
            'title_line1' => $customOrderMedia && $customOrderMedia->title ? $customOrderMedia->title : 'Your Idea.',
            'title_line2' => $customOrderMedia && $customOrderMedia->subtitle ? $customOrderMedia->subtitle : 'Our Yarn.',
            'description' => $customOrderMedia && $customOrderMedia->description ? $customOrderMedia->description : "From custom color palettes and personalized initials to unique floral bouquets and character plushies — let's create something made especially for you.",
            'cta_text' => $customOrderMedia && $customOrderMedia->cta_text ? $customOrderMedia->cta_text : 'Start a Custom Order',
            'cta_link' => $customOrderMedia && $customOrderMedia->cta_link ? $customOrderMedia->cta_link : '/custom-order',
            'is_active' => $customOrderMedia ? (bool)$customOrderMedia->is_active : true,
        ];

        // 5. Newsletter
        $newsletterMedia = Media::where('page', 'homepage')
            ->where('section', 'newsletter')
            ->where('is_active', true)
            ->first();

        $newsletter = [
            'bg' => $newsletterMedia ? $newsletterMedia->url : null,
            'left' => null,
            'right' => null,
            'title' => $newsletterMedia && $newsletterMedia->title ? $newsletterMedia->title : 'Join Our Creative Journey',
            'subtitle' => $newsletterMedia && $newsletterMedia->subtitle ? $newsletterMedia->subtitle : 'Get updates on new products, offers and handmade stories.',
            'description' => $newsletterMedia && $newsletterMedia->description ? $newsletterMedia->description : ($newsletterMedia && $newsletterMedia->subtitle ? $newsletterMedia->subtitle : 'Get updates on new products, offers and handmade stories.'),
            'tag_text' => $newsletterMedia && $newsletterMedia->tag_text ? $newsletterMedia->tag_text : 'KNOTELLE10',
            'cta_text' => $newsletterMedia && $newsletterMedia->cta_text ? $newsletterMedia->cta_text : 'Subscribe',
            'is_active' => $newsletterMedia ? (bool)$newsletterMedia->is_active : true,
        ];

        // 6. Footer Artwork
        $footerBgMedia = Media::where('section', 'footer')
            ->where('is_active', true)
            ->where(function ($q) {
                $q->where('slot', 'footer_bg')
                  ->orWhere('slot', 'footer_artwork')
                  ->orWhere('slot', 'footer_left')
                  ->orWhereNull('slot');
            })
            ->first();

        if (!$footerBgMedia) {
            $footerBgMedia = Media::where('section', 'footer')->where('is_active', true)->first();
        }

        $footer = [
            'bg' => $footerBgMedia ? ($footerBgMedia->desktop_image_url ?: $footerBgMedia->url) : asset('images/footer/footer.png'),
            'left' => null,
            'right' => null,
            'title' => $footerBgMedia && $footerBgMedia->title ? $footerBgMedia->title : 'Knotelle Handmade Boutique',
            'subtitle' => $footerBgMedia && $footerBgMedia->subtitle ? $footerBgMedia->subtitle : 'Made with ♡ for a kinder, cozier world.',
        ];

        // 7. Global Site Assets
        $logoMedia = Media::where('page', 'global')
            ->where('section', 'global_assets')
            ->where('slot', 'main_logo')
            ->where('is_active', true)
            ->first();

        $mainLogo = $logoMedia ? $logoMedia->url : asset('images/logo/Logo_1.png');

        // 7. Categories for Homepage Grid
        $categories = Category::where('status', 1)
            ->whereNull('parent_id')
            ->orderBy('sort_order', 'asc')
            ->with(['image', 'products.variants'])
            ->get()
            ->map(function ($cat) {
                $imageUrl = null;
                if ($cat->image && $cat->image->file_path) {
                    $fp = $cat->image->file_path;
                    if (str_starts_with($fp, 'http://') || str_starts_with($fp, 'https://')) {
                        $imageUrl = $fp;
                    } elseif (str_starts_with($fp, 'images/')) {
                        $imageUrl = asset($fp);
                    } else {
                        $imageUrl = asset('storage/' . ltrim($fp, '/'));
                    }
                } else {
                    $imageUrl = asset('images/categories/' . $cat->slug . '.jpg');
                }

                return [
                    'id' => 'cat-' . $cat->id,
                    'db_id' => $cat->id,
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                    'image' => $imageUrl,
                    'itemCount' => $cat->products->where('status', 'active')->count(),
                    'sort_order' => $cat->sort_order,
                ];
            });

        // 8. Shop Page Media
        $shopBannerDesktop = Media::where('page', 'shop')->where('section', 'shop_banner')->where('slot', 'shop_banner_desktop')->where('is_active', true)->first();
        $shopBannerMobile = Media::where('page', 'shop')->where('section', 'shop_banner')->where('slot', 'shop_banner_mobile')->where('is_active', true)->first();
        $shopPromo = Media::where('page', 'shop')->where('section', 'shop_promo')->where('is_active', true)->first();

        $shop = [
            'banner' => [
                'desktop' => $shopBannerDesktop ? $shopBannerDesktop->url : null,
                'mobile' => $shopBannerMobile ? $shopBannerMobile->url : ($shopBannerDesktop ? $shopBannerDesktop->url : null),
                'title' => $shopBannerDesktop && $shopBannerDesktop->title ? $shopBannerDesktop->title : 'Artisanal Handcrafted Creations',
                'subtitle' => $shopBannerDesktop && $shopBannerDesktop->subtitle ? $shopBannerDesktop->subtitle : 'Discover unique crochet treasures woven with love, patience, and 100% natural cotton fibers.',
                'tag_text' => $shopBannerDesktop && $shopBannerDesktop->tag_text ? $shopBannerDesktop->tag_text : 'Handmade with Love',
                'cta_text' => $shopBannerDesktop ? $shopBannerDesktop->cta_text : null,
                'cta_link' => $shopBannerDesktop ? $shopBannerDesktop->cta_link : null,
                'is_active' => $shopBannerDesktop ? (bool)$shopBannerDesktop->is_active : true,
            ],
            'promo' => [
                'banner' => $shopPromo ? $shopPromo->url : null,
                'title' => $shopPromo && $shopPromo->title ? $shopPromo->title : 'Bespoke Custom Creations',
                'subtitle' => $shopPromo && $shopPromo->subtitle ? $shopPromo->subtitle : 'Can’t find your dream bouquet or plushie? We craft made-to-order treasures tailored especially for you.',
                'cta_text' => $shopPromo && $shopPromo->cta_text ? $shopPromo->cta_text : 'Request Custom Order',
                'cta_link' => $shopPromo && $shopPromo->cta_link ? $shopPromo->cta_link : '/custom-order',
                'is_active' => $shopPromo ? (bool)$shopPromo->is_active : true,
            ]
        ];

        // 9. About Page Media
        $aboutHeroMedia = Media::where('page', 'about')->where('section', 'about_story')->where('slot', 'about_hero_image')->where('is_active', true)->first();
        $aboutStudioMedia = Media::where('page', 'about')->where('section', 'about_story')->where('slot', 'about_studio_image')->where('is_active', true)->first();

        $about = [
            'hero' => [
                'image' => $aboutHeroMedia ? $aboutHeroMedia->url : 'https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=1000&auto=format&fit=crop',
                'title' => $aboutHeroMedia && $aboutHeroMedia->title ? $aboutHeroMedia->title : 'Every Loop Tells a Story',
                'subtitle' => $aboutHeroMedia && $aboutHeroMedia->subtitle ? $aboutHeroMedia->subtitle : 'The KNOTELLE Story',
                'description' => $aboutHeroMedia && $aboutHeroMedia->description ? $aboutHeroMedia->description : 'In a world flooded with disposable factory goods, KNOTELLE was born from a yearning for slow-made warmth. What began as a tiny home workshop in Bengaluru has grown into a vibrant collective of women artisans who share a deep love for yarn, color harmony, and delicate floral silhouettes.',
                'badge' => $aboutHeroMedia && $aboutHeroMedia->tag_text ? $aboutHeroMedia->tag_text : '100% Handcrafted',
                'cta_text' => $aboutHeroMedia && $aboutHeroMedia->cta_text ? $aboutHeroMedia->cta_text : 'Request a Custom Creation',
                'cta_link' => $aboutHeroMedia && $aboutHeroMedia->cta_link ? $aboutHeroMedia->cta_link : '/custom-order',
                'is_active' => $aboutHeroMedia ? (bool)$aboutHeroMedia->is_active : true,
            ],
            'studio' => [
                'image' => $aboutStudioMedia ? $aboutStudioMedia->url : null,
                'title' => $aboutStudioMedia && $aboutStudioMedia->title ? $aboutStudioMedia->title : 'Bengaluru Atelier',
                'subtitle' => $aboutStudioMedia && $aboutStudioMedia->subtitle ? $aboutStudioMedia->subtitle : 'Where stitches come alive',
            ]
        ];

        // 10. Contact Page Media
        $contactBannerMedia = Media::where('page', 'contact')->where('section', 'contact_header')->where('slot', 'contact_banner_image')->where('is_active', true)->first();
        $contactStudioMedia = Media::where('page', 'contact')->where('section', 'contact_header')->where('slot', 'contact_studio_image')->where('is_active', true)->first();

        $contact = [
            'banner' => [
                'image' => $contactBannerMedia ? $contactBannerMedia->url : null,
                'title' => $contactBannerMedia && $contactBannerMedia->title ? $contactBannerMedia->title : "Let's Connect",
                'subtitle' => $contactBannerMedia && $contactBannerMedia->subtitle ? $contactBannerMedia->subtitle : "Have a question about a product, custom order, or collaboration? We'd love to hear from you.",
                'tag_text' => $contactBannerMedia && $contactBannerMedia->tag_text ? $contactBannerMedia->tag_text : "We're Here For You",
            ],
            'studio' => [
                'image' => $contactStudioMedia ? $contactStudioMedia->url : null,
                'title' => $contactStudioMedia && $contactStudioMedia->title ? $contactStudioMedia->title : 'Atelier Studio',
                'subtitle' => $contactStudioMedia && $contactStudioMedia->subtitle ? $contactStudioMedia->subtitle : '12th Main, 4th Cross, Indiranagar, Bengaluru',
            ]
        ];

        // 11. Blog & Reels / Behind The Stitches
        $blogReelsSettings = Media::where('page', 'homepage')
            ->where('section', 'blog_reels')
            ->where('slot', 'section_settings')
            ->first();

        $blogReelsItems = Media::where('page', 'homepage')
            ->where('section', 'blog_reels')
            ->where('slot', '!=', 'section_settings')
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => (string)$item->id,
                    'db_id' => $item->id,
                    'title' => $item->title,
                    'subtitle' => $item->subtitle ?: ($item->tag_text ?: ''),
                    'description' => $item->description ?: '',
                    'caption' => $item->description ?: ($item->subtitle ?: $item->title),
                    'content_type' => $item->content_type ?: 'reel',
                    'category' => $item->category_name ?: 'Studio ASMR',
                    'thumbnail' => $item->desktop_image_url ?: $item->url,
                    'mobile_thumbnail' => $item->mobile_image_url ?: ($item->desktop_image_url ?: $item->url),
                    'video_url' => $item->video_stream_url ?: $item->video_url,
                    'videoUrl' => $item->video_stream_url ?: $item->video_url,
                    'duration' => $item->duration ?: '00:48',
                    'audio_name' => $item->audio_name ?: 'Original Sound',
                    'audio_track' => $item->audio_name ?: 'Original Sound',
                    'audioTrack' => $item->audio_name ?: 'Original Sound',
                    'likes' => $item->formatted_likes,
                    'likes_count' => (int)$item->likes_count,
                    'comments' => (string)($item->comments_count ?: 0),
                    'comments_count' => (int)$item->comments_count,
                    'views' => $item->formatted_views,
                    'views_count' => (int)$item->views_count,
                    'is_featured' => (bool)$item->is_featured,
                    'sort_order' => (int)$item->sort_order,
                    'author' => [
                        'name' => 'Krina Jani',
                        'role' => 'Lead Artisan',
                        'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop',
                    ],
                    'tags' => ['#' . str_replace(' ', '', $item->category_name ?: 'Crochet'), '#Knotelle', '#Handmade'],
                ];
            });

        $blogReels = [
            'title' => $blogReelsSettings && $blogReelsSettings->title ? $blogReelsSettings->title : 'Behind the Stitches',
            'subtitle' => $blogReelsSettings && $blogReelsSettings->subtitle ? $blogReelsSettings->subtitle : 'Watch our artisans hand-craft each creation, styling guides, and cozy studio ASMR unboxings.',
            'tag_text' => $blogReelsSettings && $blogReelsSettings->tag_text ? $blogReelsSettings->tag_text : 'Studio Journal & Video Reels',
            'cta_text' => $blogReelsSettings && $blogReelsSettings->cta_text ? $blogReelsSettings->cta_text : 'Follow @knotelleindia',
            'cta_link' => $blogReelsSettings && $blogReelsSettings->cta_link ? $blogReelsSettings->cta_link : 'https://instagram.com/knotelleindia',
            'is_active' => $blogReelsSettings ? (bool)$blogReelsSettings->is_active : true,
            'items' => $blogReelsItems,
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'hero' => [
                    'desktop' => $heroDesktop,
                    'mobile' => $heroMobile,
                    'slides' => $heroSlides,
                ],
                'categories' => $categories,
                'customCrochet' => $customCrochet,
                'brandStory' => $brandStory,
                'customOrder' => $customOrder,
                'newsletter' => $newsletter,
                'footer' => $footer,
                'shop' => $shop,
                'about' => $about,
                'contact' => $contact,
                'blogReels' => $blogReels,
                'testimonials' => Testimonial::where('is_active', true)->orderBy('id', 'desc')->get(),
                'global' => [
                    'logo' => $mainLogo,
                    'mobileLogo' => $mainLogo,
                    'favicon' => asset('favicon.ico'),
                    'defaultProduct' => asset('images/logo/Logo_1.png'),
                    'defaultCategory' => asset('images/logo/Logo_1.png'),
                ],
            ]
        ]);
    }

    /**
     * Increment Video / Reel View Count on Playback
     */
    public function incrementView($id): JsonResponse
    {
        $media = Media::where('page', 'homepage')
            ->where('section', 'blog_reels')
            ->where('id', $id)
            ->first();

        if ($media) {
            $media->increment('views_count');
            return response()->json([
                'success' => true,
                'views' => $media->views_count,
                'formatted_views' => $media->formatted_views,
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Video not found.'], 404);
    }
}
