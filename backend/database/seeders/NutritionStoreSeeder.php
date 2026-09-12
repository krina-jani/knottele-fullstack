<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class NutritionStoreSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        // Clear existing data
        $this->truncateTables();

        // Seed in correct order
        $this->seedBrands();
        $this->seedCategories();
        $this->seedTags();
        $this->seedSpecificationGroups();
        $this->seedSpecifications();
        $this->seedTaxClasses();
        $this->seedProducts();
        $this->seedProductVariants();
        $this->seedMedia();
        
        // Enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }

    private function truncateTables()
    {
        $tables = [
            'category_product', 'product_tags', 'related_products', 'cross_sell_products',
            'upsell_products', 'product_specifications', 'variant_images', 'product_variants',
            'products', 'category_spec_groups', 'spec_group_specs', 'specification_values',
            'category_attributes', 'attribute_values', 'attributes', 'specifications',
            'specification_groups', 'tags', 'categories', 'brands', 'category_hierarchies',
            'tax_classes', 'media'
        ];

        foreach ($tables as $table) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                DB::table($table)->truncate();
            }
        }
    }

    private function seedBrands()
    {
        $brands = [
            [
                'name' => 'Nuts & Nutrition',
                'slug' => 'nuts-and-nutrition',
                'description' => 'Premium quality nuts, seeds, and sports nutrition products.',
                'logo_id' => 1,
                'status' => 1,
                'sort_order' => 1,
                'meta_title' => 'Nuts & Nutrition - Premium Health Foods',
                'meta_description' => 'Premium quality nuts, seeds, and sports nutrition products for a healthy lifestyle.',
                'meta_keywords' => 'nuts, nutrition, whey protein, healthy snacks',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('brands')->insert($brands);
    }

    private function seedCategories()
    {
        $categories = [
            [
                'parent_id' => null, 'name' => 'Nutrition Powder', 'slug' => 'nutrition-powder',
                'description' => 'High quality nutrition powders.',
                'status' => 1, 'featured' => 1, 'show_in_nav' => 1, 'sort_order' => 1,
                'image_id' => null, 'meta_title' => 'Nutrition Powder', 'meta_description' => '', 'meta_keywords' => '',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'parent_id' => null, 'name' => 'Dry Fruits & Seeds', 'slug' => 'dry-fruits-seeds',
                'description' => 'Premium quality dry fruits and seeds.',
                'status' => 1, 'featured' => 1, 'show_in_nav' => 1, 'sort_order' => 2,
                'image_id' => null, 'meta_title' => 'Dry Fruits & Seeds', 'meta_description' => '', 'meta_keywords' => '',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'parent_id' => null, 'name' => 'Seeds Mix', 'slug' => 'seeds-mix',
                'description' => 'Nutrient dense seeds mix.',
                'status' => 1, 'featured' => 1, 'show_in_nav' => 1, 'sort_order' => 3,
                'image_id' => null, 'meta_title' => 'Seeds Mix', 'meta_description' => '', 'meta_keywords' => '',
                'created_at' => now(), 'updated_at' => now(),
            ],
        ];

        DB::table('categories')->insert($categories);

        $cats = DB::table('categories')->get();
        $hierarchies = [];
        foreach ($cats as $cat) {
            $hierarchies[] = ['ancestor_id' => $cat->id, 'descendant_id' => $cat->id, 'depth' => 0];
        }
        DB::table('category_hierarchies')->insert($hierarchies);
    }

    private function seedTags()
    {
        $tags = [
            ['name' => 'High Protein', 'slug' => 'high-protein', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Vegan', 'slug' => 'vegan', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('tags')->insert($tags);
    }

    private function seedSpecificationGroups()
    {
        $groups = [
            ['name' => 'Nutritional Information', 'sort_order' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('specification_groups')->insert($groups);
    }

    private function seedSpecifications()
    {
        $specifications = [
            ['name' => 'Protein per Serving', 'code' => 'protein_per_serving', 'input_type' => 'text', 'is_required' => 0, 'is_filterable' => 0, 'sort_order' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('specifications')->insert($specifications);
    }

    private function seedTaxClasses()
    {
        $taxClasses = [
            ['name' => 'Standard Food Tax', 'code' => 'standard_food', 'description' => 'Tax rate for food items', 'is_default' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('tax_classes')->insert($taxClasses);

        $taxClassId = DB::table('tax_classes')->first()->id;
        DB::table('tax_rates')->insert([
            ['tax_class_id' => $taxClassId, 'name' => 'IN Standard Food', 'country_code' => 'IN', 'state_code' => null, 'zip_code' => null, 'rate' => 5.0, 'is_active' => 1, 'priority' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    private function seedProducts()
    {
        $categories = DB::table('categories')->pluck('id', 'slug');
        $brands = DB::table('brands')->pluck('id', 'slug');
        $taxClass = DB::table('tax_classes')->first();

        $products = [
            [
                'name' => 'Chocolate Nutrition Powder',
                'slug' => 'chocolate-nutrition-powder',
                'product_type' => 'configurable',
                'brand_id' => $brands['nuts-and-nutrition'],
                'main_category_id' => $categories['nutrition-powder'],
                'tax_class_id' => $taxClass->id,
                'short_description' => 'Rich chocolate flavored nutrition powder for daily energy.',
                'description' => '<p>Our Chocolate Nutrition Powder is crafted with premium ingredients to provide you with essential nutrients. Perfect for starting your day or as a post-workout recovery drink.</p>',
                'status' => 'active', 'is_featured' => 1, 'is_new' => 1, 'is_bestseller' => 1,
                'weight' => 0, 'length' => 0, 'width' => 0, 'height' => 0,
                'meta_title' => 'Chocolate Nutrition Powder',
                'meta_description' => 'Rich chocolate flavored nutrition powder for daily energy.',
                'meta_keywords' => 'chocolate nutrition powder',
                'canonical_url' => '/products/chocolate-nutrition-powder',
                'product_code' => 'prod-1',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'name' => 'Kesar Pista Nutrition Powder',
                'slug' => 'kesar-pista-nutrition-powder',
                'product_type' => 'configurable',
                'brand_id' => $brands['nuts-and-nutrition'],
                'main_category_id' => $categories['nutrition-powder'],
                'tax_class_id' => $taxClass->id,
                'short_description' => 'Traditional kesar pista flavor packed with nutrients.',
                'description' => '<p>Experience the authentic taste of saffron and pistachios combined with essential vitamins and minerals for your daily nutritional needs.</p>',
                'status' => 'active', 'is_featured' => 1, 'is_new' => 0, 'is_bestseller' => 0,
                'weight' => 0, 'length' => 0, 'width' => 0, 'height' => 0,
                'meta_title' => 'Kesar Pista Nutrition Powder',
                'meta_description' => 'Traditional kesar pista flavor packed with nutrients.',
                'meta_keywords' => 'kesar pista powder',
                'canonical_url' => '/products/kesar-pista-nutrition-powder',
                'product_code' => 'prod-2',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'name' => 'Dry Fruits with Seeds Mix',
                'slug' => 'dry-fruits-with-seeds-mix',
                'product_type' => 'configurable',
                'brand_id' => $brands['nuts-and-nutrition'],
                'main_category_id' => $categories['dry-fruits-seeds'],
                'tax_class_id' => $taxClass->id,
                'short_description' => 'Premium almonds, cashews mixed with healthy seeds.',
                'description' => '<p>A perfect blend of high-quality dry fruits and roasted seeds to keep you energetic throughout the day. A guilt-free snacking option.</p>',
                'status' => 'active', 'is_featured' => 1, 'is_new' => 0, 'is_bestseller' => 1,
                'weight' => 0, 'length' => 0, 'width' => 0, 'height' => 0,
                'meta_title' => 'Dry Fruits with Seeds Mix',
                'meta_description' => 'Premium almonds, cashews mixed with healthy seeds.',
                'meta_keywords' => 'dry fruits seeds mix',
                'canonical_url' => '/products/dry-fruits-with-seeds-mix',
                'product_code' => 'prod-3',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'name' => 'Roasted Seeds Mix',
                'slug' => 'roasted-seeds-mix',
                'product_type' => 'configurable',
                'brand_id' => $brands['nuts-and-nutrition'],
                'main_category_id' => $categories['seeds-mix'],
                'tax_class_id' => $taxClass->id,
                'short_description' => 'A crunchy mix of pumpkin, sunflower, flax, and chia seeds.',
                'description' => '<p>Packed with omega-3 and fiber, our roasted seeds mix is the perfect topping for your salads, smoothies, or just as a healthy snack.</p>',
                'status' => 'active', 'is_featured' => 0, 'is_new' => 0, 'is_bestseller' => 0,
                'weight' => 0, 'length' => 0, 'width' => 0, 'height' => 0,
                'meta_title' => 'Roasted Seeds Mix',
                'meta_description' => 'A crunchy mix of pumpkin, sunflower, flax, and chia seeds.',
                'meta_keywords' => 'roasted seeds mix',
                'canonical_url' => '/products/roasted-seeds-mix',
                'product_code' => 'prod-4',
                'created_at' => now(), 'updated_at' => now(),
            ],
        ];

        DB::table('products')->insert($products);

        // Assign categories to products
        $insertedProducts = DB::table('products')->get();
        $catProduct = [];
        foreach ($insertedProducts as $product) {
            $catProduct[] = [
                'product_id' => $product->id,
                'category_id' => $product->main_category_id,
            ];
        }
        DB::table('category_product')->insert($catProduct);
    }

    private function seedProductVariants()
    {
        $products = DB::table('products')->get()->keyBy('product_code');
        $variants = [];

        // prod-1
        $variants[] = ['product_id' => $products['prod-1']->id, 'sku' => 'CNP-08', 'price' => 299, 'stock_quantity' => 50, 'is_default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()];
        $variants[] = ['product_id' => $products['prod-1']->id, 'sku' => 'CNP-16', 'price' => 549, 'stock_quantity' => 35, 'is_default' => 0, 'status' => 1, 'created_at' => now(), 'updated_at' => now()];
        $variants[] = ['product_id' => $products['prod-1']->id, 'sku' => 'CNP-32', 'price' => 999, 'stock_quantity' => 20, 'is_default' => 0, 'status' => 1, 'created_at' => now(), 'updated_at' => now()];

        // prod-2
        $variants[] = ['product_id' => $products['prod-2']->id, 'sku' => 'KPNP-08', 'price' => 299, 'stock_quantity' => 40, 'is_default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()];
        $variants[] = ['product_id' => $products['prod-2']->id, 'sku' => 'KPNP-16', 'price' => 549, 'stock_quantity' => 25, 'is_default' => 0, 'status' => 1, 'created_at' => now(), 'updated_at' => now()];
        $variants[] = ['product_id' => $products['prod-2']->id, 'sku' => 'KPNP-32', 'price' => 999, 'stock_quantity' => 15, 'is_default' => 0, 'status' => 1, 'created_at' => now(), 'updated_at' => now()];

        // prod-3
        $variants[] = ['product_id' => $products['prod-3']->id, 'sku' => 'DFSM-250', 'price' => 249, 'stock_quantity' => 100, 'is_default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()];
        $variants[] = ['product_id' => $products['prod-3']->id, 'sku' => 'DFSM-500', 'price' => 449, 'stock_quantity' => 80, 'is_default' => 0, 'status' => 1, 'created_at' => now(), 'updated_at' => now()];
        $variants[] = ['product_id' => $products['prod-3']->id, 'sku' => 'DFSM-1KG', 'price' => 849, 'stock_quantity' => 40, 'is_default' => 0, 'status' => 1, 'created_at' => now(), 'updated_at' => now()];

        // prod-4
        $variants[] = ['product_id' => $products['prod-4']->id, 'sku' => 'RSM-250', 'price' => 199, 'stock_quantity' => 120, 'is_default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()];
        $variants[] = ['product_id' => $products['prod-4']->id, 'sku' => 'RSM-500', 'price' => 349, 'stock_quantity' => 90, 'is_default' => 0, 'status' => 1, 'created_at' => now(), 'updated_at' => now()];

        DB::table('product_variants')->insert($variants);
    }

    private function seedMedia()
    {
        $products = DB::table('products')->get()->keyBy('product_code');

        $imageMap = [
            'prod-1' => [
                'chocolate.jpeg',
                'chocolate-diffrent.jpeg',
                'chocolate-diffrent2.jpeg',
                'chocolate-powder-video.mp4'
            ],
            'prod-2' => [
                'pista.jpeg',
                'kesarpista-view.jpeg',
                'kesarpista-view2.jpeg',
                'kesar-pista-powder.mp4'
            ],
            'prod-3' => [
                'dryfruits.jpeg',
                'dry-fruits-view.jpeg',
                'dry-fruits-view-2.jpeg',
                'dryfruits-with-seeds-mix.mp4'
            ],
            'prod-4' => [
                'rosted-seeds.jpeg',
                'rosted-seeds-view.jpeg',
                'rosted-seeds-view-2.jpeg',
                'mix-seeds.mp4'
            ]
        ];

        foreach ($imageMap as $productCode => $images) {
            $product = $products[$productCode];
            
            // Get all variants for this product
            $variants = DB::table('product_variants')->where('product_id', $product->id)->get();
            
            foreach ($images as $index => $imageName) {
                // Determine file type
                $fileType = 'image';
                if (Str::endsWith($imageName, '.mp4')) {
                    $fileType = 'video';
                }

                $mediaId = DB::table('media')->insertGetId([
                    'file_name' => $imageName,
                    // We need to use `../images/image.jpg` or `../images/video.mp4` because the API assumes it's in storage, so we go out of storage and into public/images
                    'file_path' => '../images/' . $imageName,
                    'disk' => 'public',
                    'mime_type' => 'image/jpeg',
                    'file_type' => $fileType,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Attach to default variant (which represents the product main images in this model)
                $defaultVariant = collect($variants)->firstWhere('is_default', 1);
                if ($defaultVariant) {
                    DB::table('variant_images')->insert([
                        'variant_id' => $defaultVariant->id,
                        'media_id' => $mediaId,
                        'is_primary' => ($index === 0) ? 1 : 0,
                        'sort_order' => $index,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }
    }
}
