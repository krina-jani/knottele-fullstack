<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Media;

class KnotelleStoreSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSettings();
        $this->seedBrand();
        $this->seedCategories();
        if (Schema::hasTable('tags')) {
            $this->seedTags();
        }
        if (Schema::hasTable('specification_groups')) {
            $this->seedSpecificationGroups();
        }
        if (Schema::hasTable('specifications')) {
            $this->seedSpecifications();
        }
        $this->seedTaxClasses();
        $this->seedProducts();
        $this->seedProductVariants();
        $this->seedMedia();
        $this->seedTestimonials();
        $this->seedOffers();
    }

    private function seedSettings(): void
    {
        $settings = [
            ['group' => 'general', 'key' => 'store_name', 'label' => 'Store Name', 'value' => 'KNOTELLE', 'type' => 'text'],
            ['group' => 'general', 'key' => 'store_email', 'label' => 'Store Email', 'value' => 'hello@knotelle.com', 'type' => 'text'],
            ['group' => 'general', 'key' => 'store_phone', 'label' => 'Store Phone', 'value' => '+91 98765 43210', 'type' => 'text'],
            ['group' => 'general', 'key' => 'store_phone_alt', 'label' => 'Alternate Phone', 'value' => '+91 98765 43211', 'type' => 'text'],
            ['group' => 'general', 'key' => 'currency', 'label' => 'Currency', 'value' => 'INR', 'type' => 'text'],
            ['group' => 'general', 'key' => 'store_address', 'label' => 'Store Address', 'value' => "12th Main, 4th Cross, Indiranagar\nBengaluru, Karnataka - 560038\nIndia", 'type' => 'textarea'],
            ['group' => 'seo', 'key' => 'meta_title', 'label' => 'Meta Title', 'value' => 'KNOTELLE | Handmade Crochet Creations & Gifts', 'type' => 'text'],
            ['group' => 'seo', 'key' => 'meta_description', 'label' => 'Meta Description', 'value' => 'Handcrafted crochet flowers, bouquets, plushies, bags, bookmarks, phone covers, and accessories made with love.', 'type' => 'textarea'],
            ['group' => 'seo', 'key' => 'meta_keywords', 'label' => 'Meta Keywords', 'value' => 'crochet, handmade, amigurumi, crochet flowers, bouquet, gifts, boutique, knotelle, wool art', 'type' => 'textarea'],
            ['group' => 'appearance', 'key' => 'theme_color', 'label' => 'Theme Color', 'value' => '#913638', 'type' => 'text'],
            ['group' => 'appearance', 'key' => 'logo_url', 'label' => 'Logo URL', 'value' => 'images/logo/Logo_1.png', 'type' => 'text'],
            ['group' => 'contact_info', 'key' => 'address_line_1', 'label' => 'Address Line 1', 'value' => '12th Main, 4th Cross, Indiranagar', 'type' => 'text'],
            ['group' => 'contact_info', 'key' => 'address_line_2', 'label' => 'Address Line 2', 'value' => 'Bengaluru, Karnataka - 560038, India', 'type' => 'text'],
            ['group' => 'contact_info', 'key' => 'phone', 'label' => 'Contact Phone', 'value' => '+91 98765 43210', 'type' => 'text'],
            ['group' => 'contact_info', 'key' => 'hours', 'label' => 'Working Hours', 'value' => 'Mon - Sat, 10am - 7pm IST', 'type' => 'text'],
            ['group' => 'contact_info', 'key' => 'email', 'label' => 'Contact Email', 'value' => 'hello@knotelle.com', 'type' => 'text'],
            ['group' => 'contact_info', 'key' => 'response_time', 'label' => 'Response Time', 'value' => 'We reply within 24 hours', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['group' => $setting['group'], 'key' => $setting['key']],
                [
                    'label' => $setting['label'],
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'is_public' => 1,
                    'updated_at' => now(),
                ]
            );
        }
    }

    private function seedBrand(): void
    {
        DB::table('brands')->updateOrInsert(
            ['slug' => 'knotelle'],
            [
                'name' => 'KNOTELLE',
                'description' => 'Artisanal Handcrafted Crochet Creations — Handcrafted with Love.',
                'status' => 1,
                'featured' => 1,
                'sort_order' => 1,
                'meta_title' => 'KNOTELLE - Handcrafted Crochet Creations',
                'meta_description' => 'Boutique handcrafted crochet bouquets, plushies, bags, bookmarks, and accessories.',
                'meta_keywords' => 'crochet, handmade, boutique, knotelle',
                'updated_at' => now(),
            ]
        );
    }

    private function seedCategories(): void
    {
        $categories = [
            [
                'name' => 'Keychain',
                'slug' => 'keychain',
                'description' => 'Charming miniature crochet keychains and bag charms made with durable cotton yarn.',
                'status' => 1,
                'featured' => 1,
                'show_in_nav' => 1,
                'sort_order' => 1,
                'meta_title' => 'Crochet Keychains - KNOTELLE',
                'meta_description' => 'Charming miniature crochet keychains and bag charms.',
                'meta_keywords' => 'crochet keychain, amigurumi charm, bunny keychain',
            ],
            [
                'name' => 'Flower',
                'slug' => 'flower',
                'description' => 'Everlasting individual crochet blossoms, sunflowers, tulips, and daisies.',
                'status' => 1,
                'featured' => 1,
                'show_in_nav' => 1,
                'sort_order' => 2,
                'meta_title' => 'Everlasting Crochet Flowers - KNOTELLE',
                'meta_description' => 'Everlasting individual crochet blossoms, sunflowers, and tulips.',
                'meta_keywords' => 'crochet flower, sunflower, tulip, handmade blossom',
            ],
            [
                'name' => 'Bouquet',
                'slug' => 'bouquet',
                'description' => 'Stunning handcrafted flower arrangements that never wilt, wrapped in luxury paper.',
                'status' => 1,
                'featured' => 1,
                'show_in_nav' => 1,
                'sort_order' => 3,
                'meta_title' => 'Luxury Crochet Bouquets - KNOTELLE',
                'meta_description' => 'Stunning handcrafted flower arrangements that never wilt.',
                'meta_keywords' => 'crochet bouquet, rose bouquet, everlasting bouquet, gift',
            ],
            [
                'name' => 'Soft Toys',
                'slug' => 'soft-toys',
                'description' => 'Adorable handmade amigurumi plushies, bunnies, bears, and whimsical characters.',
                'status' => 1,
                'featured' => 1,
                'show_in_nav' => 1,
                'sort_order' => 4,
                'meta_title' => 'Handmade Amigurumi Plushies - KNOTELLE',
                'meta_description' => 'Adorable handmade amigurumi plushies, bunnies, and teddy bears.',
                'meta_keywords' => 'amigurumi, soft toys, plushie, teddy bear, handmade toy',
            ],
            [
                'name' => 'Bags',
                'slug' => 'bags',
                'description' => 'Boutique tote bags, granny square totes, shoulder bags, and bohemian market sacks.',
                'status' => 1,
                'featured' => 1,
                'show_in_nav' => 1,
                'sort_order' => 5,
                'meta_title' => 'Bohemian Crochet Bags & Totes - KNOTELLE',
                'meta_description' => 'Boutique tote bags, granny square totes, and bohemian market sacks.',
                'meta_keywords' => 'crochet bag, granny square tote, handmade handbag',
            ],
            [
                'name' => 'Coin Purse',
                'slug' => 'coin-purse',
                'description' => 'Vintage clasp crochet coin pouches and pocket-sized zippered mini organizers.',
                'status' => 1,
                'featured' => 1,
                'show_in_nav' => 1,
                'sort_order' => 6,
                'meta_title' => 'Vintage Clasp Coin Purses - KNOTELLE',
                'meta_description' => 'Vintage clasp crochet coin pouches and mini organizers.',
                'meta_keywords' => 'coin purse, kiss clasp, crochet pouch',
            ],
            [
                'name' => 'Phone Cover',
                'slug' => 'phone-cover',
                'description' => 'Protective and stylish knitted phone sleeves with strap attachments and floral motifs.',
                'status' => 1,
                'featured' => 1,
                'show_in_nav' => 1,
                'sort_order' => 7,
                'meta_title' => 'Floral Crochet Phone Covers - KNOTELLE',
                'meta_description' => 'Protective and stylish knitted phone sleeves with crossbody straps.',
                'meta_keywords' => 'phone cover, crochet sleeve, crossbody phone bag',
            ],
            [
                'name' => 'Cup (Tea/Coffee)',
                'slug' => 'cup-tea-coffee',
                'description' => 'Insulating mug cozies, flower cup holders, teapot covers, and table coasters.',
                'status' => 1,
                'featured' => 1,
                'show_in_nav' => 1,
                'sort_order' => 8,
                'meta_title' => 'Mug Cozies & Coasters - KNOTELLE',
                'meta_description' => 'Insulating mug cozies, flower cup holders, and table coasters.',
                'meta_keywords' => 'mug cozy, cup sleeve, crochet coaster, tea coffee',
            ],
            [
                'name' => 'Bookmark',
                'slug' => 'bookmark',
                'description' => 'Delicate botanical sprout, rosebud, and lace crochet bookmarks for avid book lovers.',
                'status' => 1,
                'featured' => 0,
                'show_in_nav' => 1,
                'sort_order' => 9,
                'meta_title' => 'Botanical Crochet Bookmarks - KNOTELLE',
                'meta_description' => 'Delicate botanical sprout and daisy crochet bookmarks.',
                'meta_keywords' => 'crochet bookmark, sprout bookmark, book lover gift',
            ],
            [
                'name' => 'Hair Accessories',
                'slug' => 'hair-accessories',
                'description' => 'Romantic scrunchies, floral hair clips, headband crowns, and bow barrettes.',
                'status' => 1,
                'featured' => 0,
                'show_in_nav' => 1,
                'sort_order' => 10,
                'meta_title' => 'Crochet Hair Accessories & Scrunchies - KNOTELLE',
                'meta_description' => 'Romantic scrunchies, floral hair clips, and bow barrettes.',
                'meta_keywords' => 'crochet scrunchie, hair clips, daisy clip, hair accessories',
            ],
            [
                'name' => 'Clothing',
                'slug' => 'clothing',
                'description' => 'Bespoke crochet cardigans, summer halter tops, delicate lace collars, and vests.',
                'status' => 1,
                'featured' => 0,
                'show_in_nav' => 1,
                'sort_order' => 11,
                'meta_title' => 'Artisanal Crochet Apparel - KNOTELLE',
                'meta_description' => 'Bespoke crochet cardigans, summer tops, and floral vests.',
                'meta_keywords' => 'crochet clothing, granny square vest, cottagecore vest',
            ],
        ];

        foreach ($categories as $cat) {
            DB::table('categories')->updateOrInsert(
                ['slug' => $cat['slug']],
                array_merge($cat, [
                    'updated_at' => now(),
                ])
            );
        }

        $inserted = DB::table('categories')->get();
        foreach ($inserted as $cat) {
            DB::table('category_hierarchies')->updateOrInsert(
                [
                    'ancestor_id' => $cat->id,
                    'descendant_id' => $cat->id,
                ],
                [
                    'depth' => 0
                ]
            );
        }
    }

    private function seedTags(): void
    {
        $tags = [
            ['name' => 'Handmade', 'slug' => 'handmade', 'status' => 1],
            ['name' => 'Best Seller', 'slug' => 'best-seller', 'status' => 1],
            ['name' => 'Gift Ready', 'slug' => 'gift-ready', 'status' => 1],
            ['name' => 'Customizable', 'slug' => 'customizable', 'status' => 1],
            ['name' => 'Cottagecore', 'slug' => 'cottagecore', 'status' => 1],
            ['name' => 'Botanical', 'slug' => 'botanical', 'status' => 1],
        ];

        foreach ($tags as $tag) {
            DB::table('tags')->updateOrInsert(
                ['slug' => $tag['slug']],
                array_merge($tag, ['updated_at' => now()])
            );
        }
    }

    private function seedSpecificationGroups(): void
    {
        $groups = [
            ['name' => 'Artisan & Craft Details', 'sort_order' => 1, 'status' => 1],
            ['name' => 'Product Dimensions', 'sort_order' => 2, 'status' => 1],
        ];

        foreach ($groups as $group) {
            DB::table('specification_groups')->updateOrInsert(
                ['name' => $group['name']],
                array_merge($group, ['updated_at' => now()])
            );
        }
    }

    private function seedSpecifications(): void
    {
        $specs = [
            ['name' => 'Materials', 'code' => 'materials', 'input_type' => 'text', 'is_required' => 0, 'is_filterable' => 1, 'sort_order' => 1, 'status' => 1],
            ['name' => 'Crafting Time', 'code' => 'craft_time', 'input_type' => 'text', 'is_required' => 0, 'is_filterable' => 0, 'sort_order' => 2, 'status' => 1],
            ['name' => 'Dimensions', 'code' => 'dimensions', 'input_type' => 'text', 'is_required' => 0, 'is_filterable' => 0, 'sort_order' => 3, 'status' => 1],
        ];

        foreach ($specs as $spec) {
            DB::table('specifications')->updateOrInsert(
                ['code' => $spec['code']],
                array_merge($spec, ['updated_at' => now()])
            );
        }
    }

    private function seedTaxClasses(): void
    {
        DB::table('tax_classes')->updateOrInsert(
            ['code' => 'standard_gst'],
            [
                'name' => 'Standard Handcrafted GST',
                'description' => 'GST for artisanal handmade items',
                'is_default' => 1,
                'updated_at' => now(),
            ]
        );

        $taxClass = DB::table('tax_classes')->where('code', 'standard_gst')->first();
        if ($taxClass) {
            DB::table('tax_rates')->updateOrInsert(
                [
                    'tax_class_id' => $taxClass->id,
                    'name' => 'GST Standard',
                ],
                [
                    'country_code' => 'IN',
                    'state_code' => null,
                    'zip_code' => null,
                    'rate' => 5.0,
                    'is_active' => 1,
                    'priority' => 1,
                    'updated_at' => now(),
                ]
            );
        }
    }

    private function seedProducts(): void
    {
        $categories = DB::table('categories')->pluck('id', 'slug');
        $brand = DB::table('brands')->where('slug', 'knotelle')->first();
        $taxClass = DB::table('tax_classes')->where('code', 'standard_gst')->first();

        $brandId = $brand ? $brand->id : null;
        $taxClassId = $taxClass ? $taxClass->id : null;

        $products = [
            [
                'product_code' => 'prod-1',
                'name' => 'Cute Bunny Amigurumi Keychain',
                'slug' => 'cute-bunny-keychain',
                'product_type' => 'configurable',
                'brand_id' => $brandId,
                'main_category_id' => $categories['keychain'] ?? null,
                'tax_class_id' => $taxClassId,
                'short_description' => 'Handmade miniature bunny charm with gold clasp and soft blush cheeks.',
                'description' => '<p>An adorable miniature bunny charm hand-crocheted with 100% soft milk cotton yarn. Features a sturdy gold alloy clasp ring, delicate blush cheeks, and a tiny embroidered nose. Perfect as a bag charm, car key accessory, or a heartwarming gift.</p>',
                'status' => 'active',
                'is_featured' => 1,
                'is_new' => 0,
                'is_bestseller' => 1,
                'weight' => 50,
                'length' => 6.5,
                'width' => 4,
                'height' => 4,
                'meta_title' => 'Cute Bunny Amigurumi Keychain - KNOTELLE',
                'meta_description' => 'Handmade miniature bunny charm with gold clasp and soft blush cheeks.',
                'meta_keywords' => 'bunny keychain, amigurumi charm, bag accessory',
                'canonical_url' => '/product/cute-bunny-keychain',
            ],
            [
                'product_code' => 'prod-2',
                'name' => 'Sunburst Everlasting Sunflower',
                'slug' => 'sunflower-single-stem',
                'product_type' => 'configurable',
                'brand_id' => $brandId,
                'main_category_id' => $categories['flower'] ?? null,
                'tax_class_id' => $taxClassId,
                'short_description' => 'Vibrant handmade everlasting sunflower with posable stem and textured center.',
                'description' => '<p>Brighten your desk or bedside table with our cheerful crochet sunflower. Hand-stitched with warm golden petals, textured chocolate brown center, and a flexible internal stem wrapped in leaf-green yarn that can be styled in any vase.</p>',
                'status' => 'active',
                'is_featured' => 1,
                'is_new' => 0,
                'is_bestseller' => 1,
                'weight' => 80,
                'length' => 28,
                'width' => 9,
                'height' => 9,
                'meta_title' => 'Sunburst Everlasting Sunflower - KNOTELLE',
                'meta_description' => 'Vibrant handmade everlasting sunflower with posable stem.',
                'meta_keywords' => 'sunflower, crochet flower, home decor, gift',
                'canonical_url' => '/product/sunflower-single-stem',
            ],
            [
                'product_code' => 'prod-3',
                'name' => 'Luxury Rose & Tulip Wrap Bouquet',
                'slug' => 'rose-bouquet-luxury-wrap',
                'product_type' => 'configurable',
                'brand_id' => $brandId,
                'main_category_id' => $categories['bouquet'] ?? null,
                'tax_class_id' => $taxClassId,
                'short_description' => 'Luxury handcrafted roses, tulips, and eucalyptus in Korean matte wrap.',
                'description' => '<p>A breathtaking handcrafted bouquet consisting of multi-layered crochet pink roses, cream tulips, lavender sprigs, and eucalyptus leaves. Wrapped in Korean matte waterproof blush paper tied with a satin ribbon and custom wax seal tag.</p>',
                'status' => 'active',
                'is_featured' => 1,
                'is_new' => 1,
                'is_bestseller' => 1,
                'weight' => 250,
                'length' => 35,
                'width' => 22,
                'height' => 22,
                'meta_title' => 'Luxury Rose & Tulip Wrap Bouquet - KNOTELLE',
                'meta_description' => 'Luxury handcrafted roses, tulips, and eucalyptus in Korean matte wrap.',
                'meta_keywords' => 'bouquet, roses, tulips, anniversary gift, luxury',
                'canonical_url' => '/product/rose-bouquet-luxury-wrap',
            ],
            [
                'product_code' => 'prod-4',
                'name' => 'Heirloom Chenille Teddy Bear',
                'slug' => 'classic-teddy-bear',
                'product_type' => 'configurable',
                'brand_id' => $brandId,
                'main_category_id' => $categories['soft-toys'] ?? null,
                'tax_class_id' => $taxClassId,
                'short_description' => 'Ultra-soft heirloom amigurumi bear made with velvet chenille yarn.',
                'description' => '<p>A huggable, nostalgic heirloom crochet teddy bear. Hand-stitched with hypoallergenic velvet chenille yarn, safety lock eyes, and a sweet little satin bow. The ultimate comfort keepsake for both children and adults.</p>',
                'status' => 'active',
                'is_featured' => 1,
                'is_new' => 0,
                'is_bestseller' => 1,
                'weight' => 200,
                'length' => 22,
                'width' => 15,
                'height' => 15,
                'meta_title' => 'Heirloom Chenille Teddy Bear - KNOTELLE',
                'meta_description' => 'Ultra-soft heirloom amigurumi bear made with velvet chenille yarn.',
                'meta_keywords' => 'teddy bear, plushie, amigurumi, kids gift, heirloom',
                'canonical_url' => '/product/classic-teddy-bear',
            ],
            [
                'product_code' => 'prod-5',
                'name' => 'Sage & Daisy Crossbody Phone Sleeve',
                'slug' => 'daisy-phone-cover',
                'product_type' => 'configurable',
                'brand_id' => $brandId,
                'main_category_id' => $categories['phone-cover'] ?? null,
                'tax_class_id' => $taxClassId,
                'short_description' => 'Textured floral phone sleeve with 3D daisies, wood button, and crossbody strap.',
                'description' => '<p>Snug and protective handmade crochet phone sleeve decorated with 3D blooming daisies. Features a soft interior lining to prevent scratches, a natural wooden button loop closure, and a crossbody braided strap.</p>',
                'status' => 'active',
                'is_featured' => 1,
                'is_new' => 1,
                'is_bestseller' => 1,
                'weight' => 90,
                'length' => 18,
                'width' => 10,
                'height' => 2,
                'meta_title' => 'Sage & Daisy Crossbody Phone Sleeve - KNOTELLE',
                'meta_description' => 'Textured floral phone sleeve with 3D daisies and wood button.',
                'meta_keywords' => 'phone cover, daisy sleeve, crossbody pouch',
                'canonical_url' => '/product/daisy-phone-cover',
            ],
            [
                'product_code' => 'prod-6',
                'name' => 'Botanical Granny Square Tote Bag',
                'slug' => 'granny-square-bag',
                'product_type' => 'configurable',
                'brand_id' => $brandId,
                'main_category_id' => $categories['bags'] ?? null,
                'tax_class_id' => $taxClassId,
                'short_description' => 'Statement bohemian tote handcrafted from 24 intricate floral granny squares.',
                'description' => '<p>The crown jewel of our boutique collection. An artisanal bohemian tote meticulously assembled from 24 individual floral granny squares. Reinforced with sturdy interior canvas lining, magnetic clasp, inner zipper pocket, and durable shoulder straps.</p>',
                'status' => 'active',
                'is_featured' => 1,
                'is_new' => 0,
                'is_bestseller' => 1,
                'weight' => 450,
                'length' => 38,
                'width' => 36,
                'height' => 10,
                'meta_title' => 'Botanical Granny Square Tote Bag - KNOTELLE',
                'meta_description' => 'Statement bohemian tote handcrafted from 24 intricate floral granny squares.',
                'meta_keywords' => 'granny square bag, tote bag, boho bag, luxury crochet',
                'canonical_url' => '/product/granny-square-bag',
            ],
            [
                'product_code' => 'prod-7',
                'name' => 'Strawberry Kiss-Clasp Coin Purse',
                'slug' => 'vintage-rose-coin-purse',
                'product_type' => 'configurable',
                'brand_id' => $brandId,
                'main_category_id' => $categories['coin-purse'] ?? null,
                'tax_class_id' => $taxClassId,
                'short_description' => 'Strawberry-shaped crochet coin purse with antique bronze kiss-clasp.',
                'description' => '<p>Charming antique-inspired strawberry coin purse with an embossed brass kiss-lock frame. The textured crochet shell is lined with floral fabric to keep coins, jewelry, or earphones safe.</p>',
                'status' => 'active',
                'is_featured' => 0,
                'is_new' => 1,
                'is_bestseller' => 0,
                'weight' => 70,
                'length' => 10,
                'width' => 9,
                'height' => 4,
                'meta_title' => 'Strawberry Kiss-Clasp Coin Purse - KNOTELLE',
                'meta_description' => 'Strawberry-shaped crochet coin purse with antique bronze kiss-clasp.',
                'meta_keywords' => 'coin purse, strawberry pouch, vintage kiss clasp',
                'canonical_url' => '/product/vintage-rose-coin-purse',
            ],
            [
                'product_code' => 'prod-8',
                'name' => '3D Tulip Mug Cozy & Flower Coaster Set',
                'slug' => 'tulip-cup-cozy-coaster-set',
                'product_type' => 'configurable',
                'brand_id' => $brandId,
                'main_category_id' => $categories['cup-tea-coffee'] ?? null,
                'tax_class_id' => $taxClassId,
                'short_description' => 'Buttoned thermal mug sleeve with 3D tulips and matching flower coaster.',
                'description' => '<p>Keep your tea or coffee warm while adding botanical elegance to your morning routine. Includes a buttoned thermal mug sleeve with 3D tulip details and a matching wavy scallop flower coaster.</p>',
                'status' => 'active',
                'is_featured' => 0,
                'is_new' => 1,
                'is_bestseller' => 0,
                'weight' => 85,
                'length' => 26,
                'width' => 7.5,
                'height' => 1,
                'meta_title' => '3D Tulip Mug Cozy & Coaster Set - KNOTELLE',
                'meta_description' => 'Buttoned thermal mug sleeve with 3D tulips and matching flower coaster.',
                'meta_keywords' => 'mug cozy, coaster set, tea coffee, kitchen decor',
                'canonical_url' => '/product/tulip-cup-cozy-coaster-set',
            ],
            [
                'product_code' => 'prod-9',
                'name' => 'Botanical Sprout & Daisy Bookmark',
                'slug' => 'botanical-sprout-bookmark',
                'product_type' => 'configurable',
                'brand_id' => $brandId,
                'main_category_id' => $categories['bookmark'] ?? null,
                'tax_class_id' => $taxClassId,
                'short_description' => 'Flat woven bookmark with 3D sprouting leaf and daisy topper.',
                'description' => '<p>Never lose your page again with this charming botanical crochet bookmark. Lies flat between pages without bending the spine, with sweet green leaves and a blooming daisy blossom draping over the top.</p>',
                'status' => 'active',
                'is_featured' => 0,
                'is_new' => 0,
                'is_bestseller' => 0,
                'weight' => 20,
                'length' => 25,
                'width' => 3,
                'height' => 0.5,
                'meta_title' => 'Botanical Sprout & Daisy Bookmark - KNOTELLE',
                'meta_description' => 'Flat woven bookmark with 3D sprouting leaf and daisy topper.',
                'meta_keywords' => 'bookmark, reading gift, stationery, daisy bookmark',
                'canonical_url' => '/product/botanical-sprout-bookmark',
            ],
            [
                'product_code' => 'prod-10',
                'name' => 'Ruffle Crochet Scrunchies & Daisy Clip Set',
                'slug' => 'daisy-hair-scrunchie-set',
                'product_type' => 'configurable',
                'brand_id' => $brandId,
                'main_category_id' => $categories['hair-accessories'] ?? null,
                'tax_class_id' => $taxClassId,
                'short_description' => '3 ruffled cotton scrunchies and matching handmade daisy hair clips.',
                'description' => '<p>Gentle on curls and straight hair alike. Knitted around snag-free elastic bands with delicate scalloped ruffle edges, paired with 3 handmade daisy hair clips for cottagecore charm.</p>',
                'status' => 'active',
                'is_featured' => 0,
                'is_new' => 0,
                'is_bestseller' => 1,
                'weight' => 40,
                'length' => 10,
                'width' => 10,
                'height' => 2,
                'meta_title' => 'Ruffle Crochet Scrunchies Set - KNOTELLE',
                'meta_description' => '3 ruffled cotton scrunchies and matching handmade daisy hair clips.',
                'meta_keywords' => 'hair scrunchie, daisy clip, hair accessories',
                'canonical_url' => '/product/daisy-hair-scrunchie-set',
            ],
            [
                'product_code' => 'prod-11',
                'name' => 'Petal Symphony Floral Crochet Vest',
                'slug' => 'crochet-cottagecore-vest',
                'product_type' => 'configurable',
                'brand_id' => $brandId,
                'main_category_id' => $categories['clothing'] ?? null,
                'tax_class_id' => $taxClassId,
                'short_description' => 'Handmade botanical granny square vest with wooden buttons and scalloped hem.',
                'description' => '<p>An artisanal cottagecore layering piece made from breathable cotton blend yarn. Features delicate floral square motifs in cream, rose, and olive, wooden button closures, and scalloped borders.</p>',
                'status' => 'active',
                'is_featured' => 1,
                'is_new' => 1,
                'is_bestseller' => 0,
                'weight' => 380,
                'length' => 46,
                'width' => 40,
                'height' => 3,
                'meta_title' => 'Petal Symphony Floral Crochet Vest - KNOTELLE',
                'meta_description' => 'Handmade botanical granny square vest with wooden buttons.',
                'meta_keywords' => 'crochet vest, cottagecore apparel, floral vest',
                'canonical_url' => '/product/crochet-cottagecore-vest',
            ],
            [
                'product_code' => 'prod-12',
                'name' => 'Mini Potted Everlasting Tulip Plant',
                'slug' => 'crochet-tulip-pot-plant',
                'product_type' => 'configurable',
                'brand_id' => $brandId,
                'main_category_id' => $categories['flower'] ?? null,
                'tax_class_id' => $taxClassId,
                'short_description' => '4 blooming tulips in a terracotta crochet pot that never needs watering.',
                'description' => '<p>A zero-maintenance potted plant featuring 4 blooming pastel tulips nestled in a textured terracotta-colored crochet pot with twine bow. Filled with weighted beads at the base so it stands proudly on any desk or bookshelf.</p>',
                'status' => 'active',
                'is_featured' => 0,
                'is_new' => 0,
                'is_bestseller' => 1,
                'weight' => 150,
                'length' => 16,
                'width' => 10,
                'height' => 10,
                'meta_title' => 'Mini Potted Everlasting Tulip Plant - KNOTELLE',
                'meta_description' => '4 blooming tulips in a terracotta crochet pot.',
                'meta_keywords' => 'potted tulips, desk plant, crochet flowers, gift',
                'canonical_url' => '/product/crochet-tulip-pot-plant',
            ],
        ];

        foreach ($products as $p) {
            DB::table('products')->updateOrInsert(
                ['product_code' => $p['product_code']],
                array_merge($p, ['updated_at' => now()])
            );
        }

        // Category product mapping
        $insertedProducts = DB::table('products')->get();
        foreach ($insertedProducts as $product) {
            if ($product->main_category_id) {
                DB::table('category_product')->updateOrInsert(
                    [
                        'product_id' => $product->id,
                        'category_id' => $product->main_category_id,
                    ],
                    []
                );
            }
        }
    }

    private function seedProductVariants(): void
    {
        $products = DB::table('products')->get()->keyBy('product_code');
        $variants = [];

        if (isset($products['prod-1'])) {
            $variants[] = ['product_id' => $products['prod-1']->id, 'sku' => 'BNY-STD-WHT', 'price' => 150, 'compare_price' => 199, 'stock_quantity' => 25, 'is_default' => 1, 'status' => 1];
            $variants[] = ['product_id' => $products['prod-1']->id, 'sku' => 'BNY-STD-PNK', 'price' => 150, 'compare_price' => 199, 'stock_quantity' => 20, 'is_default' => 0, 'status' => 1];
        }

        if (isset($products['prod-2'])) {
            $variants[] = ['product_id' => $products['prod-2']->id, 'sku' => 'SNF-GLD-28', 'price' => 300, 'compare_price' => 350, 'stock_quantity' => 18, 'is_default' => 1, 'status' => 1];
        }

        if (isset($products['prod-3'])) {
            $variants[] = ['product_id' => $products['prod-3']->id, 'sku' => 'BQT-5B-PNK', 'price' => 1000, 'compare_price' => 1299, 'stock_quantity' => 12, 'is_default' => 1, 'status' => 1];
            $variants[] = ['product_id' => $products['prod-3']->id, 'sku' => 'BQT-9B-PNK', 'price' => 1600, 'compare_price' => 1899, 'stock_quantity' => 8, 'is_default' => 0, 'status' => 1];
        }

        if (isset($products['prod-4'])) {
            $variants[] = ['product_id' => $products['prod-4']->id, 'sku' => 'TED-MED-BLU', 'price' => 1200, 'compare_price' => 1450, 'stock_quantity' => 10, 'is_default' => 1, 'status' => 1];
            $variants[] = ['product_id' => $products['prod-4']->id, 'sku' => 'TED-LRG-BLU', 'price' => 1700, 'compare_price' => 1950, 'stock_quantity' => 6, 'is_default' => 0, 'status' => 1];
        }

        if (isset($products['prod-5'])) {
            $variants[] = ['product_id' => $products['prod-5']->id, 'sku' => 'PHN-SGE-IPH', 'price' => 1200, 'compare_price' => 1399, 'stock_quantity' => 15, 'is_default' => 1, 'status' => 1];
        }

        if (isset($products['prod-6'])) {
            $variants[] = ['product_id' => $products['prod-6']->id, 'sku' => 'BAG-GSQ-VNT', 'price' => 5000, 'compare_price' => 5800, 'stock_quantity' => 6, 'is_default' => 1, 'status' => 1];
        }

        if (isset($products['prod-7'])) {
            $variants[] = ['product_id' => $products['prod-7']->id, 'sku' => 'CP-STR-RED', 'price' => 450, 'compare_price' => 550, 'stock_quantity' => 20, 'is_default' => 1, 'status' => 1];
        }

        if (isset($products['prod-8'])) {
            $variants[] = ['product_id' => $products['prod-8']->id, 'sku' => 'COZ-TLP-PNK', 'price' => 400, 'compare_price' => 499, 'stock_quantity' => 24, 'is_default' => 1, 'status' => 1];
        }

        if (isset($products['prod-9'])) {
            $variants[] = ['product_id' => $products['prod-9']->id, 'sku' => 'BM-SPR-GRN', 'price' => 180, 'compare_price' => 220, 'stock_quantity' => 30, 'is_default' => 1, 'status' => 1];
        }

        if (isset($products['prod-10'])) {
            $variants[] = ['product_id' => $products['prod-10']->id, 'sku' => 'SCR-DSY-PST', 'price' => 350, 'compare_price' => 420, 'stock_quantity' => 22, 'is_default' => 1, 'status' => 1];
        }

        if (isset($products['prod-11'])) {
            $variants[] = ['product_id' => $products['prod-11']->id, 'sku' => 'VST-FLR-ML', 'price' => 3800, 'compare_price' => 4500, 'stock_quantity' => 5, 'is_default' => 1, 'status' => 1];
        }

        if (isset($products['prod-12'])) {
            $variants[] = ['product_id' => $products['prod-12']->id, 'sku' => 'POT-TLP-PNK', 'price' => 550, 'compare_price' => 650, 'stock_quantity' => 14, 'is_default' => 1, 'status' => 1];
        }

        foreach ($variants as $v) {
            DB::table('product_variants')->updateOrInsert(
                ['sku' => $v['sku']],
                array_merge($v, ['updated_at' => now()])
            );
        }
    }

    private function seedMedia(): void
    {
        // 1. Seed Logo
        $logoMedia = Media::firstOrCreate(
            ['file_path' => 'images/logo/Logo_1.png'],
            [
                'file_name' => 'Logo_1.png',
                'disk' => 'local',
                'mime_type' => 'image/png',
                'file_type' => 'image',
                'file_size' => 26405,
                'alt_text' => 'KNOTELLE Official Brand Logo',
                'uploaded_by' => 1,
                'uploader_type' => 'admin',
            ]
        );
        DB::table('brands')->where('slug', 'knotelle')->update(['logo_id' => $logoMedia->id]);

        // 2. Seed Category Images
        $categoryImageMap = [
            'keychain' => 'images/categories/keychain.jpg',
            'flower' => 'images/categories/flower.jpg',
            'bouquet' => 'images/categories/bouquet.jpg',
            'soft-toys' => 'images/categories/soft-toys.jpg',
            'bags' => 'images/categories/bags.jpg',
            'coin-purse' => 'images/categories/coin-purse.jpg',
            'phone-cover' => 'images/categories/phone-cover.jpg',
            'cup-tea-coffee' => 'images/categories/cup-tea-coffee.jpg',
            'bookmark' => 'images/categories/bookmark.jpg',
            'hair-accessories' => 'images/categories/hair-accessories.jpg',
            'clothing' => 'images/categories/clothing.jpg',
        ];

        foreach ($categoryImageMap as $catSlug => $relPath) {
            $catMedia = Media::firstOrCreate(
                ['file_path' => $relPath],
                [
                    'file_name' => basename($relPath),
                    'disk' => 'local',
                    'mime_type' => 'image/jpeg',
                    'file_type' => 'image',
                    'alt_text' => ucwords(str_replace('-', ' ', $catSlug)) . ' Category',
                    'uploaded_by' => 1,
                    'uploader_type' => 'admin',
                ]
            );

            DB::table('categories')->where('slug', $catSlug)->update(['image_id' => $catMedia->id]);
        }

        // 3. Seed Product Images
        $products = DB::table('products')->get()->keyBy('product_code');

        $productImageMap = [
            'prod-1' => 'images/products/bunny-keychain.jpg',
            'prod-2' => 'images/products/sunflower-stem.jpg',
            'prod-3' => 'images/products/rose-bouquet.jpg',
            'prod-4' => 'images/products/teddy-bear.jpg',
            'prod-5' => 'images/products/daisy-phone-cover.jpg',
            'prod-6' => 'images/products/granny-square-bag.jpg',
            'prod-7' => 'images/products/strawberry-coin-purse.jpg',
            'prod-8' => 'images/products/tulip-mug-cozy.jpg',
            'prod-9' => 'images/products/sprout-bookmark.jpg',
            'prod-10' => 'images/products/floral-scrunchies.jpg',
            'prod-11' => 'images/products/crochet-vest.jpg',
            'prod-12' => 'images/products/potted-tulips.jpg',
        ];

        foreach ($productImageMap as $productCode => $relPath) {
            if (!isset($products[$productCode])) continue;
            $product = $products[$productCode];
            $variants = DB::table('product_variants')->where('product_id', $product->id)->get();

            $media = Media::firstOrCreate(
                ['file_path' => $relPath],
                [
                    'file_name' => basename($relPath),
                    'disk' => 'local',
                    'mime_type' => 'image/jpeg',
                    'file_type' => 'image',
                    'alt_text' => $product->name,
                    'uploaded_by' => 1,
                    'uploader_type' => 'admin',
                ]
            );

            foreach ($variants as $variant) {
                DB::table('variant_images')->updateOrInsert(
                    [
                        'variant_id' => $variant->id,
                        'media_id' => $media->id,
                    ],
                    [
                        'is_primary' => $variant->is_default ? 1 : 0,
                        'sort_order' => 1,
                        'updated_at' => now(),
                    ]
                );
            }
        }

        // 4. Seed Hero & Homepage media
        $extraImages = [
            'images/hero/hero-enhanced.jpg' => 'Hero Banner Bouquet',
            'images/hero/2image.png' => 'Hero Showcase 2',
            'images/hero/3image.png' => 'Hero Showcase 3',
            'images/hero/4image.png' => 'Hero Showcase 4',
            'images/homepage/middleimg.png' => 'Craft Studio Middle Image',
        ];

        foreach ($extraImages as $relPath => $label) {
            Media::firstOrCreate(
                ['file_path' => $relPath],
                [
                    'file_name' => basename($relPath),
                    'disk' => 'local',
                    'mime_type' => str_ends_with($relPath, '.png') ? 'image/png' : 'image/jpeg',
                    'file_type' => 'image',
                    'alt_text' => $label,
                    'uploaded_by' => 1,
                    'uploader_type' => 'admin',
                ]
            );
        }
    }

    private function seedTestimonials(): void
    {
        $testimonials = [
            [
                'name' => 'Aanya Verma',
                'designation' => 'Bengaluru, India',
                'message' => 'I ordered the custom rose bouquet for my sister\'s birthday and she cried tears of joy! The stitches are unimaginably neat, and the packaging smelled like gentle lavender. KNOTELLE is pure art.',
                'rating' => 5,
                'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop',
                'is_active' => 1,
            ],
            [
                'name' => 'Rhea Mukherjee',
                'designation' => 'Mumbai, India',
                'message' => 'This bag is an absolute showstopper. Strangers stop me on Bandra streets asking where I got it. The lining is sturdy and the color harmony is so chic. Worth every single rupee!',
                'rating' => 5,
                'image' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?q=80&w=200&auto=format&fit=crop',
                'is_active' => 1,
            ],
            [
                'name' => 'Tanvi Deshmukh',
                'designation' => 'Pune, India',
                'message' => 'Requested a custom size for my phone with a personalized initial charm. The team sent me yarn color previews beforehand. The craftsmanship is flawless and super protective.',
                'rating' => 5,
                'image' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=200&auto=format&fit=crop',
                'is_active' => 1,
            ],
            [
                'name' => 'Isha Kapoor',
                'designation' => 'Delhi NCR, India',
                'message' => 'Bought this for my newborn niece as an heirloom keepsake. The chenille yarn is so soft and gentle. You can feel the love poured into every single loop.',
                'rating' => 5,
                'image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=200&auto=format&fit=crop',
                'is_active' => 1,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            DB::table('testimonials')->updateOrInsert(
                ['name' => $testimonial['name']],
                array_merge($testimonial, ['updated_at' => now()])
            );
        }
    }

    private function seedOffers(): void
    {
        DB::table('offers')->updateOrInsert(
            ['code' => 'KNOTELLE10'],
            [
                'name' => 'Welcome to Knotelle',
                'offer_type' => 'percentage',
                'discount_value' => 10,
                'min_cart_amount' => 499,
                'max_discount' => 500,
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addYear(),
                'status' => 1,
                'updated_at' => now(),
            ]
        );
    }
}
