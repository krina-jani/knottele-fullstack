<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Media;
use App\Models\Category;

class MediaTaxonomySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Hero slides
        Media::where('file_path', 'like', '%hero-enhanced.jpg%')->update([
            'page' => 'homepage',
            'section' => 'hero',
            'slot' => 'hero_slide_1_desktop',
            'device' => 'desktop',
            'title' => 'Little Stitches Big Happiness',
            'subtitle' => 'Handmade crochet products that add warmth to your everyday life.',
            'cta_text' => 'Shop All Collections',
            'cta_link' => '/shop',
            'tag_text' => 'Good Things Are Handmade',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Media::where('file_path', 'like', '%2image.png%')->update([
            'page' => 'homepage',
            'section' => 'hero',
            'slot' => 'hero_slide_2_desktop',
            'device' => 'desktop',
            'title' => 'Everyday Elegance Handcrafted Bags',
            'subtitle' => 'Artisanal granny square bags & wearable creations woven with love.',
            'cta_text' => 'Explore Bags',
            'cta_link' => '/category/bags',
            'tag_text' => 'Every Stitch Has A Story',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Media::where('file_path', 'like', '%3image.png%')->update([
            'page' => 'homepage',
            'section' => 'hero',
            'slot' => 'hero_slide_3_desktop',
            'device' => 'desktop',
            'title' => 'Everlasting Blooms Made With Love',
            'subtitle' => 'Bespoke everlasting flower bouquets crafted to brighten every moment.',
            'cta_text' => 'Shop Flowers',
            'cta_link' => '/category/flower',
            'tag_text' => 'Crafted Just For You',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        Media::where('file_path', 'like', '%4image.png%')->update([
            'page' => 'homepage',
            'section' => 'hero',
            'slot' => 'hero_slide_4_desktop',
            'device' => 'desktop',
            'title' => 'Bespoke Creations Custom Crafted',
            'subtitle' => 'Personalized crochet treasures tailored specially for your memorable moments.',
            'cta_text' => 'Request Custom Order',
            'cta_link' => '/custom-order',
            'tag_text' => 'Pure Artisan Warmth',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        // 2. Custom Crochet Banner
        Media::where('file_path', 'like', '%middleimg.png%')->update([
            'page' => 'homepage',
            'section' => 'custom_crochet',
            'slot' => 'custom_crochet_desktop',
            'device' => 'desktop',
            'title' => 'Custom Crochet',
            'subtitle' => 'Just for You',
            'description' => 'Your imagination, our yarn. Let\'s create something special together.',
            'cta_text' => 'Request Your Custom Order',
            'cta_link' => '/custom-order',
            'tag_text' => 'Turn Your Ideas Into Handmade Reality',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // 3. Footer Artwork
        Media::where('file_path', 'like', '%footer.png%')->update([
            'page' => 'homepage',
            'section' => 'footer',
            'slot' => 'footer_bg',
            'device' => 'all',
            'title' => 'Footer Background Artwork',
            'subtitle' => 'Made with ♡ for a kinder, cozier world.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // 4. Global Assets
        Media::where('file_path', 'like', '%Logo_1.png%')->update([
            'page' => 'global',
            'section' => 'global_assets',
            'slot' => 'main_logo',
            'device' => 'all',
            'title' => 'Main Boutique Crown Logo',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // 5. Category Images
        foreach (Category::all() as $cat) {
            if ($cat->image_id) {
                Media::where('id', $cat->image_id)->update([
                    'page' => 'homepage',
                    'section' => 'categories',
                    'slot' => 'category_' . $cat->slug,
                    'device' => 'all',
                    'title' => $cat->name . ' Category Image',
                    'sort_order' => $cat->sort_order ?: 1,
                    'is_active' => (bool)$cat->status,
                ]);
            }
        }
    }
}
