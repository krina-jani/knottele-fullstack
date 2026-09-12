<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ClinicStoreSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        // Clear existing data (carefully)
        $this->truncateTables();

        // Seed in correct order
        $this->seedBrands();
        $this->seedCategories();
        $this->seedTags();
        $this->seedSpecificationGroups();
        $this->seedSpecifications();
        $this->seedSpecificationValues();
        $this->seedSpecGroupSpecs();
        $this->seedCategorySpecGroups();
        $this->seedTaxClasses();
        $this->seedProducts();
        $this->seedProductVariants();
        $this->seedProductRelationships();
        
        // Enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }

    private function truncateTables()
    {
        // Truncate in reverse order of dependencies
        $tables = [
            'category_product',
            'product_tags',
            'related_products',
            'cross_sell_products',
            'upsell_products',
            'product_specifications',
            'variant_images',
            'product_variants',
            'products',
            'category_spec_groups',
            'spec_group_specs',
            'specification_values',
            'category_attributes',
            'attribute_values',
            'attributes',
            'specifications',
            'specification_groups',
            'tags',
            'categories',
            'brands',
            'category_hierarchies',
            'tax_classes',
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
                'name' => 'Dr. Kinjal Clinic',
                'slug' => 'dr-kinjal-clinic',
                'description' => 'Dermatologist-formulated skincare products with clinically proven ingredients',
                'logo_id' => 1,
                'status' => 1,
                'sort_order' => 1,
                'meta_title' => 'Dr. Kinjal Clinic - Dermatologist Formulated Skincare',
                'meta_description' => 'Professional skincare products formulated by dermatologists for effective results',
                'meta_keywords' => 'dermatologist skincare, clinical skincare, professional cosmetics',
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
                'parent_id' => null,
                'name' => 'Facewash',
                'slug' => 'facewash',
                'description' => 'Gentle and effective cleansers for all skin types',
                'status' => 1,
                'featured' => 1,
                'show_in_nav' => 1,
                'sort_order' => 1,
                'image_id' => 4,
                'meta_title' => 'Facewash - Gentle Skin Cleansers',
                'meta_description' => 'Professional face washes for acne, oily, dry, and sensitive skin',
                'meta_keywords' => 'face wash, cleanser, skin cleansing, acne facewash',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'name' => 'Moisturizers',
                'slug' => 'moisturizers',
                'description' => 'Hydrating creams, lotions, and gels for all skin types',
                'status' => 1,
                'featured' => 1,
                'show_in_nav' => 1,
                'sort_order' => 2,
                'image_id' => 5,
                'meta_title' => 'Face Moisturizers - Hydrating Skincare Products',
                'meta_description' => 'Professional moisturizers for dry, oily, combination, and sensitive skin types',
                'meta_keywords' => 'face moisturizer, hydration cream, skincare, dermatologist formulated',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'name' => 'Shampoo',
                'slug' => 'shampoo',
                'description' => 'Therapeutic shampoos for hair concerns',
                'status' => 1,
                'featured' => 1,
                'show_in_nav' => 1,
                'sort_order' => 3,
                'image_id' => 6,
                'meta_title' => 'Therapeutic Shampoos - Hair Care Solutions',
                'meta_description' => 'Professional shampoos for hair fall, dandruff, and scalp issues',
                'meta_keywords' => 'hair shampoo, therapeutic, hair fall control, scalp care',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'name' => 'Serums',
                'slug' => 'serums',
                'description' => 'Concentrated treatments for specific skin concerns',
                'status' => 1,
                'featured' => 1,
                'show_in_nav' => 1,
                'sort_order' => 4,
                'image_id' => 7,
                'meta_title' => 'Face Serums - Targeted Skin Treatments',
                'meta_description' => 'Professional serums for brightening, anti-aging, acne, and pigmentation',
                'meta_keywords' => 'face serum, skin treatment, concentrated skincare, active ingredients',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'name' => 'Bodywash',
                'slug' => 'bodywash',
                'description' => 'Body cleansers for different skin needs',
                'status' => 1,
                'featured' => 1,
                'show_in_nav' => 1,
                'sort_order' => 5,
                'image_id' => 8,
                'meta_title' => 'Bodywash - Therapeutic Body Cleansers',
                'meta_description' => 'Professional body washes for acne, dryness, and skin concerns',
                'meta_keywords' => 'body wash, body cleanser, shower gel, therapeutic',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'name' => 'Conditioner',
                'slug' => 'conditioner',
                'description' => 'Nourishing conditioners for smooth, manageable hair',
                'status' => 1,
                'featured' => 1,
                'show_in_nav' => 1,
                'sort_order' => 6,
                'image_id' => 9,
                'meta_title' => 'Hair Conditioners - Nourishing Hair Care',
                'meta_description' => 'Professional conditioners for hydration, detangling, and hair repair',
                'meta_keywords' => 'hair conditioner, nourishing, detangling, hair repair',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'name' => 'Soap',
                'slug' => 'soap',
                'description' => 'Therapeutic soaps for specific skin concerns',
                'status' => 1,
                'featured' => 1,
                'show_in_nav' => 1,
                'sort_order' => 7,
                'image_id' => 10,
                'meta_title' => 'Therapeutic Soaps - Skin Cleansing Bars',
                'meta_description' => 'Medicated soaps for acne, pigmentation, and skin brightening',
                'meta_keywords' => 'therapeutic soap, medicated, skin cleansing, dermatologist soap',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'name' => 'Sunscreens',
                'slug' => 'sunscreens',
                'description' => 'Broad spectrum sun protection for daily use',
                'status' => 1,
                'featured' => 1,
                'show_in_nav' => 1,
                'sort_order' => 8,
                'image_id' => 11,
                'meta_title' => 'Sunscreens - UVA/UVB Protection',
                'meta_description' => 'Dermatologist recommended sunscreens for all skin types',
                'meta_keywords' => 'sunscreen, sun protection, SPF, UV protection',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('categories')->insert($categories);

        // Create category hierarchies
        $this->createCategoryHierarchies();
    }

    private function createCategoryHierarchies()
    {
        $categories = DB::table('categories')->get();
        $hierarchies = [];

        foreach ($categories as $category) {
            $hierarchies[] = [
                'ancestor_id' => $category->id,
                'descendant_id' => $category->id,
                'depth' => 0,
            ];
        }

        DB::table('category_hierarchies')->insert($hierarchies);
    }

    private function seedTags()
    {
        $tags = [
            ['name' => 'Dermatologist Formulated', 'slug' => 'dermatologist-formulated', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Clinical Skincare', 'slug' => 'clinical-skincare', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Non-Comedogenic', 'slug' => 'non-comedogenic', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hypoallergenic', 'slug' => 'hypoallergenic', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cruelty Free', 'slug' => 'cruelty-free', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Paraben Free', 'slug' => 'paraben-free', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sulfate Free', 'slug' => 'sulfate-free', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fragrance Free', 'slug' => 'fragrance-free', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Acne Treatment', 'slug' => 'acne-treatment', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Brightening', 'slug' => 'brightening', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Anti-Aging', 'slug' => 'anti-aging', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hydration', 'slug' => 'hydration', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pigmentation', 'slug' => 'pigmentation', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sun Protection', 'slug' => 'sun-protection', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hair Fall Control', 'slug' => 'hair-fall-control', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Exfoliating', 'slug' => 'exfoliating', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Oil Control', 'slug' => 'oil-control', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Deep Cleansing', 'slug' => 'deep-cleansing', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Barrier Repair', 'slug' => 'barrier-repair', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hair Strengthening', 'slug' => 'hair-strengthening', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('tags')->insert($tags);
    }

    private function seedSpecificationGroups()
    {
        $groups = [
            ['name' => 'Product Information', 'sort_order' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Key Ingredients', 'sort_order' => 2, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Key Benefits', 'sort_order' => 3, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'How to Use', 'sort_order' => 4, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Skin & Hair Type', 'sort_order' => 5, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Quality & Safety', 'sort_order' => 6, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('specification_groups')->insert($groups);
    }

    private function seedSpecifications()
    {
        $specifications = [
            // Product Information group
            [
                'name' => 'Net Volume/Weight',
                'code' => 'net_volume',
                'input_type' => 'text',
                'is_required' => 1,
                'is_filterable' => 0,
                'sort_order' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MRP',
                'code' => 'mrp',
                'input_type' => 'text',
                'is_required' => 1,
                'is_filterable' => 0,
                'sort_order' => 2,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Key Ingredients group
            [
                'name' => 'Key Ingredients',
                'code' => 'key_ingredients',
                'input_type' => 'multiselect',
                'is_required' => 1,
                'is_filterable' => 1,
                'sort_order' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Key Benefits group
            [
                'name' => 'Key Benefits',
                'code' => 'key_benefits',
                'input_type' => 'multiselect',
                'is_required' => 1,
                'is_filterable' => 1,
                'sort_order' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // How to Use group
            [
                'name' => 'How to Use',
                'code' => 'how_to_use',
                'input_type' => 'textarea',
                'is_required' => 1,
                'is_filterable' => 0,
                'sort_order' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Skin & Hair Type group
            [
                'name' => 'Skin Type',
                'code' => 'skin_type',
                'input_type' => 'multiselect',
                'is_required' => 0,
                'is_filterable' => 1,
                'sort_order' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hair Type',
                'code' => 'hair_type',
                'input_type' => 'multiselect',
                'is_required' => 0,
                'is_filterable' => 1,
                'sort_order' => 2,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Quality & Safety group
            [
                'name' => 'Dermatologist Tested',
                'code' => 'dermatologist_tested',
                'input_type' => 'checkbox',
                'is_required' => 0,
                'is_filterable' => 1,
                'sort_order' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Non-Comedogenic',
                'code' => 'non_comedogenic',
                'input_type' => 'checkbox',
                'is_required' => 0,
                'is_filterable' => 1,
                'sort_order' => 2,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cruelty Free',
                'code' => 'cruelty_free',
                'input_type' => 'checkbox',
                'is_required' => 0,
                'is_filterable' => 1,
                'sort_order' => 3,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Paraben Free',
                'code' => 'paraben_free',
                'input_type' => 'checkbox',
                'is_required' => 0,
                'is_filterable' => 1,
                'sort_order' => 4,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sulfate Free',
                'code' => 'sulfate_free',
                'input_type' => 'checkbox',
                'is_required' => 0,
                'is_filterable' => 1,
                'sort_order' => 5,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('specifications')->insert($specifications);
    }

    private function seedSpecificationValues()
    {
        $specs = DB::table('specifications')->pluck('id', 'code');
        $values = [];

        // Key Ingredients values
        $ingredients = [
            'Kojic Acid', 'Niacinamide', 'Glutathione', 'Vitamin C', 'Vitamin E',
            'Aloe Vera', 'Arbutin', 'Salicylic Acid', 'Glycolic Acid', 'Lactic Acid',
            'Hyaluronic Acid', 'Ceramides', 'Glycerin', 'Shea Butter', 'Biotin',
            'Caffeine', 'Procapil', 'Argan Oil', 'Almond Oil', 'Sunflower Oil',
            'D-Panthenol', 'Allantoin', 'Retinol', 'Peptides', 'Mulberry Extract',
            'Ceramide', 'Liposomal Caffeine', 'Keratin', 'Saw Palmetto', 'Tropaeolum Majus',
            'Nasturtium Officinale', 'Olive Oil', 'Oatmeal Extract',
        ];

        foreach ($ingredients as $index => $ingredient) {
            $values[] = [
                'specification_id' => $specs['key_ingredients'],
                'value' => $ingredient,
                'sort_order' => $index + 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Key Benefits values
        $benefits = [
            'Brightening', 'Reduces Pigmentation', 'Acne Control', 'Oil Control',
            'Deep Cleansing', 'Hydration', 'Barrier Repair', 'Anti Aging',
            'Smooth Texture', 'Hair Fall Reduction', 'Hair Strengthening',
            'Improves Shine', 'Sun Protection', 'Soothing & Calming',
            'Exfoliation', 'Pore Minimizing', 'Even Skin Tone', 'Reduces Dark Spots',
            'Improves Skin Radiance', 'Promotes Skin Renewal', 'Reduces Breakouts',
            'Unclogs Pores', 'Reduces Blackheads & Whiteheads', 'Improves Scalp Health',
            'Promotes Hair Growth', 'Adds Density to Hair', 'Nourishes Hair',
            'Reduces Dullness', 'Maintains Skin Balance',
        ];

        foreach ($benefits as $index => $benefit) {
            $values[] = [
                'specification_id' => $specs['key_benefits'],
                'value' => $benefit,
                'sort_order' => $index + 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Skin Type values
        $skinTypes = [
            'All Skin Types', 'Oily', 'Dry', 'Combination', 'Sensitive', 'Acne Prone',
            'Normal', 'Mature Skin',
        ];

        foreach ($skinTypes as $index => $skinType) {
            $values[] = [
                'specification_id' => $specs['skin_type'],
                'value' => $skinType,
                'sort_order' => $index + 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Hair Type values
        $hairTypes = [
            'All Hair Types', 'Dry', 'Frizzy', 'Damaged', 'Hair Fall',
            'Oily Scalp', 'Normal', 'Color Treated', 'Thin Hair', 'Split Ends',
        ];

        foreach ($hairTypes as $index => $hairType) {
            $values[] = [
                'specification_id' => $specs['hair_type'],
                'value' => $hairType,
                'sort_order' => $index + 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('specification_values')->insert($values);
    }

    private function seedSpecGroupSpecs()
    {
        $groups = DB::table('specification_groups')->pluck('id', 'name');
        $specs = DB::table('specifications')->pluck('id', 'code');

        $groupSpecs = [
            // Product Information group
            ['spec_group_id' => $groups['Product Information'], 'specification_id' => $specs['net_volume'], 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['spec_group_id' => $groups['Product Information'], 'specification_id' => $specs['mrp'], 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],

            // Key Ingredients group
            ['spec_group_id' => $groups['Key Ingredients'], 'specification_id' => $specs['key_ingredients'], 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Key Benefits group
            ['spec_group_id' => $groups['Key Benefits'], 'specification_id' => $specs['key_benefits'], 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],

            // How to Use group
            ['spec_group_id' => $groups['How to Use'], 'specification_id' => $specs['how_to_use'], 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Skin & Hair Type group
            ['spec_group_id' => $groups['Skin & Hair Type'], 'specification_id' => $specs['skin_type'], 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['spec_group_id' => $groups['Skin & Hair Type'], 'specification_id' => $specs['hair_type'], 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],

            // Quality & Safety group
            ['spec_group_id' => $groups['Quality & Safety'], 'specification_id' => $specs['dermatologist_tested'], 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['spec_group_id' => $groups['Quality & Safety'], 'specification_id' => $specs['non_comedogenic'], 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['spec_group_id' => $groups['Quality & Safety'], 'specification_id' => $specs['cruelty_free'], 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['spec_group_id' => $groups['Quality & Safety'], 'specification_id' => $specs['paraben_free'], 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['spec_group_id' => $groups['Quality & Safety'], 'specification_id' => $specs['sulfate_free'], 'sort_order' => 5, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('spec_group_specs')->insert($groupSpecs);
    }

    private function seedCategorySpecGroups()
    {
        $categories = DB::table('categories')->get();
        $groups = DB::table('specification_groups')->pluck('id', 'name');

        $categoryGroups = [];

        foreach ($categories as $category) {
            foreach ($groups as $groupName => $groupId) {
                $categoryGroups[] = [
                    'category_id' => $category->id,
                    'spec_group_id' => $groupId,
                    'sort_order' => array_search($groupName, array_keys($groups->toArray())) + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('category_spec_groups')->insert($categoryGroups);
    }

    private function seedTaxClasses()
    {
        $taxClasses = [
            [
                'name' => 'Standard Skincare',
                'code' => 'standard_skincare',
                'description' => 'Standard tax rate for skincare and cosmetic products',
                'is_default' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('tax_classes')->insert($taxClasses);

        // Add tax rates
        $taxClassId = DB::table('tax_classes')->where('code', 'standard_skincare')->first()->id;

        $taxRates = [
            [
                'tax_class_id' => $taxClassId,
                'name' => 'IN Standard',
                'country_code' => 'IN',
                'state_code' => null,
                'zip_code' => null,
                'rate' => 18.0,
                'is_active' => 1,
                'priority' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('tax_rates')->insert($taxRates);
    }

    private function seedProducts()
    {
        $categories = DB::table('categories')->pluck('id', 'slug');
        $brands = DB::table('brands')->pluck('id', 'slug');
        $taxClass = DB::table('tax_classes')->where('code', 'standard_skincare')->first();

        // ==================== PRODUCTS (ALL SIMPLE) ====================
        $products = [];

        // P1: Dr. Kinjal Skin Brightening Face Wash
        $products[] = [
            'name' => 'Dr. Kinjal Skin Brightening Face Wash (100 ml)',
            'slug' => 'dr-kinjal-skin-brightening-face-wash',
            'product_type' => 'simple',
            'brand_id' => $brands['dr-kinjal-clinic'],
            'main_category_id' => $categories['facewash'],
            'tax_class_id' => $taxClass->id,
            'short_description' => 'Brightening cleanser for radiant skin.',
            'description' => $this->getProductDescription('P1'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 120,
            'length' => 8,
            'width' => 5,
            'height' => 2,
            'meta_title' => 'Dr. Kinjal Skin Brightening Face Wash - Kojic Acid Cleanser',
            'meta_description' => 'Scientifically formulated face wash with Kojic Acid, Niacinamide & Vitamin C for brighter, clearer skin.',
            'meta_keywords' => 'brightening face wash, kojic acid, vitamin c, dermatologist face wash, skin brightening',
            'canonical_url' => '/products/dr-kinjal-skin-brightening-face-wash',
            'product_code' => 'DK-FW-BRIGHT-100',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // P2: Dr. Kinjal Moisturizer
        $products[] = [
            'name' => 'Dr. Kinjal Moisturizer (200 ml)',
            'slug' => 'dr-kinjal-moisturizer',
            'product_type' => 'simple',
            'brand_id' => $brands['dr-kinjal-clinic'],
            'main_category_id' => $categories['moisturizers'],
            'tax_class_id' => $taxClass->id,
            'short_description' => 'Daily hydration & barrier repair moisturizer.',
            'description' => $this->getProductDescription('P2'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 220,
            'length' => 7,
            'width' => 7,
            'height' => 3,
            'meta_title' => 'Dr. Kinjal Moisturizer - Hydrating Face Cream',
            'meta_description' => 'Lightweight non-greasy moisturizer with Hyaluronic Acid and Ceramides for daily hydration.',
            'meta_keywords' => 'hydrating moisturizer, hyaluronic acid, ceramides, face cream, barrier repair',
            'canonical_url' => '/products/dr-kinjal-moisturizer',
            'product_code' => 'DK-MOIST-200',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // P3: Dr. Kinjal 3 in 1 Shampoo
        $products[] = [
            'name' => 'Dr. Kinjal 3 in 1 Shampoo (250 ml)',
            'slug' => 'dr-kinjal-3-in-1-shampoo',
            'product_type' => 'simple',
            'brand_id' => $brands['dr-kinjal-clinic'],
            'main_category_id' => $categories['shampoo'],
            'tax_class_id' => $taxClass->id,
            'short_description' => 'Hair fall control shampoo with Procapil.',
            'description' => $this->getProductDescription('P3'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 270,
            'length' => 9,
            'width' => 5,
            'height' => 18,
            'meta_title' => 'Dr. Kinjal 3 in 1 Shampoo - Hair Fall Control',
            'meta_description' => 'Advanced hair strengthening shampoo with Procapil and Biotin for hair fall control.',
            'meta_keywords' => 'hair fall shampoo, procapil, biotin, hair strengthening, 3 in 1 shampoo',
            'canonical_url' => '/products/dr-kinjal-3-in-1-shampoo',
            'product_code' => 'DK-SHAM-250',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // P4: Dr. Kinjal Face Serum
        $products[] = [
            'name' => 'Dr. Kinjal Face Serum (30 ml)',
            'slug' => 'dr-kinjal-face-serum',
            'product_type' => 'simple',
            'brand_id' => $brands['dr-kinjal-clinic'],
            'main_category_id' => $categories['serums'],
            'tax_class_id' => $taxClass->id,
            'short_description' => 'Acne exfoliating serum with AHA + BHA.',
            'description' => $this->getProductDescription('P4'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 35,
            'length' => 4,
            'width' => 4,
            'height' => 12,
            'meta_title' => 'Dr. Kinjal Face Serum - AHA BHA Exfoliating Treatment',
            'meta_description' => 'Exfoliating serum with Salicylic Acid and Glycolic Acid for acne and texture improvement.',
            'meta_keywords' => 'exfoliating serum, aha bha, acne serum, face treatment, salicylic acid',
            'canonical_url' => '/products/dr-kinjal-face-serum',
            'product_code' => 'DK-SERUM-30',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // P5: Dr. Kinjal Bodywash
        $products[] = [
            'name' => 'Dr. Kinjal Bodywash (250 ml)',
            'slug' => 'dr-kinjal-bodywash',
            'product_type' => 'simple',
            'brand_id' => $brands['dr-kinjal-clinic'],
            'main_category_id' => $categories['bodywash'],
            'tax_class_id' => $taxClass->id,
            'short_description' => 'Exfoliating bodywash for acne-prone skin.',
            'description' => $this->getProductDescription('P5'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 0,
            'weight' => 270,
            'length' => 9,
            'width' => 5,
            'height' => 18,
            'meta_title' => 'Dr. Kinjal Bodywash - Exfoliating Shower Gel',
            'meta_description' => 'Salicylic acid bodywash for body acne and smooth, clear skin.',
            'meta_keywords' => 'exfoliating bodywash, salicylic acid, body acne, shower gel, strawberry skin',
            'canonical_url' => '/products/dr-kinjal-bodywash',
            'product_code' => 'DK-BODY-250',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // P6: Dr. Kinjal Conditioner
        $products[] = [
            'name' => 'Dr. Kinjal Conditioner (100 ml)',
            'slug' => 'dr-kinjal-conditioner',
            'product_type' => 'simple',
            'brand_id' => $brands['dr-kinjal-clinic'],
            'main_category_id' => $categories['conditioner'],
            'tax_class_id' => $taxClass->id,
            'short_description' => 'Nourishing hair conditioner.',
            'description' => $this->getProductDescription('P6'),
            'status' => 'active',
            'is_featured' => 0,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 110,
            'length' => 7,
            'width' => 4,
            'height' => 15,
            'meta_title' => 'Dr. Kinjal Conditioner - Nourishing Hair Care',
            'meta_description' => 'Hydrating conditioner with D-Panthenol and natural oils for smooth, shiny hair.',
            'meta_keywords' => 'hair conditioner, nourishing, detangling, hair repair, d-panthenol',
            'canonical_url' => '/products/dr-kinjal-conditioner',
            'product_code' => 'DK-COND-100',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // P8: Dr. Kinjal Acne Control Facewash
        $products[] = [
            'name' => 'Dr. Kinjal Acne Control Facewash (100 ml)',
            'slug' => 'dr-kinjal-acne-control-facewash',
            'product_type' => 'simple',
            'brand_id' => $brands['dr-kinjal-clinic'],
            'main_category_id' => $categories['facewash'],
            'tax_class_id' => $taxClass->id,
            'short_description' => 'Acne control cleanser with acids.',
            'description' => $this->getProductDescription('P8'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 120,
            'length' => 8,
            'width' => 5,
            'height' => 2,
            'meta_title' => 'Dr. Kinjal Acne Control Facewash - Salicylic Acid Cleanser',
            'meta_description' => 'Acid-based facewash for oily and acne-prone skin with salicylic acid.',
            'meta_keywords' => 'acne face wash, salicylic acid, oily skin, acne treatment, glycolic acid',
            'canonical_url' => '/products/dr-kinjal-acne-control-facewash',
            'product_code' => 'DK-FW-ACNE-100',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // P9: Dr. Kinjal Skin Brightening Soap
        $products[] = [
            'name' => 'Dr. Kinjal Skin Brightening Soap (75 g)',
            'slug' => 'dr-kinjal-skin-brightening-soap',
            'product_type' => 'simple',
            'brand_id' => $brands['dr-kinjal-clinic'],
            'main_category_id' => $categories['soap'],
            'tax_class_id' => $taxClass->id,
            'short_description' => 'Brightening cleansing soap.',
            'description' => $this->getProductDescription('P9'),
            'status' => 'active',
            'is_featured' => 0,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 80,
            'length' => 8,
            'width' => 4,
            'height' => 2,
            'meta_title' => 'Dr. Kinjal Skin Brightening Soap - Kojic Acid Soap',
            'meta_description' => 'Therapeutic soap with Kojic Acid and Glutathione for skin brightening.',
            'meta_keywords' => 'brightening soap, kojic acid, glutathione, therapeutic soap, skin lightening',
            'canonical_url' => '/products/dr-kinjal-skin-brightening-soap',
            'product_code' => 'DK-SOAP-75',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // P10: Dr. Kinjal Sunscreen
        $products[] = [
            'name' => 'Dr. Kinjal Sunscreen SPF 50++ (50 g)',
            'slug' => 'dr-kinjal-sunscreen-spf-50',
            'product_type' => 'simple',
            'brand_id' => $brands['dr-kinjal-clinic'],
            'main_category_id' => $categories['sunscreens'],
            'tax_class_id' => $taxClass->id,
            'short_description' => 'Broad spectrum sunscreen.',
            'description' => $this->getProductDescription('P10'),
            'status' => 'active',
            'is_featured' => 1,
            'is_new' => 1,
            'is_bestseller' => 1,
            'weight' => 55,
            'length' => 5,
            'width' => 5,
            'height' => 8,
            'meta_title' => 'Dr. Kinjal Sunscreen SPF 50 - Broad Spectrum Protection',
            'meta_description' => 'Broad spectrum sunscreen SPF 50 with UVA/UVB protection and Hyaluronic Acid.',
            'meta_keywords' => 'sunscreen spf 50, sun protection, broad spectrum, face sunscreen, hyaluronic acid',
            'canonical_url' => '/products/dr-kinjal-sunscreen-spf-50',
            'product_code' => 'DK-SUN-50',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Insert products
        foreach ($products as $productData) {
            DB::table('products')->insert($productData);
        }
    }

    private function seedProductVariants()
    {
        $products = DB::table('products')->get();
        $mediaIds = [12, 13, 14, 15, 16, 17, 18, 19, 20];
        
        // Define pricing for each product
        $pricing = [
            'DK-FW-BRIGHT-100' => ['price' => 399, 'compare_price' => 499, 'cost_price' => 200],
            'DK-MOIST-200' => ['price' => 399, 'compare_price' => 499, 'cost_price' => 200],
            'DK-SHAM-250' => ['price' => 799, 'compare_price' => 999, 'cost_price' => 400],
            'DK-SERUM-30' => ['price' => 480, 'compare_price' => 599, 'cost_price' => 240],
            'DK-BODY-250' => ['price' => 420, 'compare_price' => 525, 'cost_price' => 210],
            'DK-COND-100' => ['price' => 330, 'compare_price' => 412, 'cost_price' => 165],
            'DK-FW-ACNE-100' => ['price' => 299, 'compare_price' => 374, 'cost_price' => 150],
            'DK-SOAP-75' => ['price' => 135, 'compare_price' => 169, 'cost_price' => 68],
            'DK-SUN-50' => ['price' => 398, 'compare_price' => 498, 'cost_price' => 199],
        ];

        foreach ($products as $product) {
            $productCode = $product->product_code;
            $priceData = $pricing[$productCode] ?? ['price' => 299, 'compare_price' => 374, 'cost_price' => 150];

            // Create simple variant
            $variantId = DB::table('product_variants')->insertGetId([
                'product_id' => $product->id,
                'sku' => $productCode,
                'combination_hash' => null,
                'price' => $priceData['price'],
                'compare_price' => $priceData['compare_price'],
                'cost_price' => $priceData['cost_price'],
                'stock_quantity' => 1000,
                'reserved_quantity' => 0,
                'stock_status' => 'in_stock',
                'is_default' => 1,
                'status' => 1,
                'weight' => $product->weight,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Add variant images (2-3 images per product)
            $selectedIndices = array_rand($mediaIds, min(3, count($mediaIds)));
            if (!is_array($selectedIndices)) {
                $selectedIndices = [$selectedIndices];
            }

            $isPrimary = true;
            foreach ($selectedIndices as $index => $mediaIndex) {
                DB::table('variant_images')->insert([
                    'variant_id' => $variantId,
                    'media_id' => $mediaIds[$mediaIndex],
                    'is_primary' => $isPrimary ? 1 : 0,
                    'sort_order' => $index,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $isPrimary = false;
            }

            // Add product specifications
            $this->addProductSpecifications($product->id, $product->name, $productCode);
        }
    }

    private function addProductSpecifications($productId, $productName, $productCode)
    {
        $specs = DB::table('specifications')->pluck('id', 'code');
        $specValues = DB::table('specification_values')->get()->groupBy('specification_id');

        // Define specifications for each product
        $productSpecs = $this->getProductSpecifications($productName, $productCode, $specs, $specValues);

        foreach ($productSpecs as $specData) {
            DB::table('product_specifications')->insert([
                'product_id' => $productId,
                'specification_id' => $specData['specification_id'],
                'specification_value_id' => $specData['specification_value_id'] ?? null,
                'custom_value' => $specData['custom_value'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function getProductSpecifications($productName, $productCode, $specs, $specValues)
    {
        $specData = [];
        
        // Define product data based on product code
        $productData = $this->getProductData($productCode, $specValues);

        // 1. Net Volume/Weight
        $specData[] = [
            'specification_id' => $specs['net_volume'],
            'custom_value' => $productData['net_volume'],
        ];

        // 2. MRP
        $specData[] = [
            'specification_id' => $specs['mrp'],
            'custom_value' => '₹' . $productData['price'],
        ];

        // 3. Key Ingredients (multiselect - store as comma-separated value IDs)
        $ingredientIds = $this->getSpecValueIds($specs['key_ingredients'], $specValues, $productData['ingredients']);
        $specData[] = [
            'specification_id' => $specs['key_ingredients'],
            'custom_value' => implode(',', $ingredientIds),
        ];

        // 4. Key Benefits (multiselect - store as comma-separated value IDs)
        $benefitIds = $this->getSpecValueIds($specs['key_benefits'], $specValues, $productData['benefits']);
        $specData[] = [
            'specification_id' => $specs['key_benefits'],
            'custom_value' => implode(',', $benefitIds),
        ];

        // 5. How to Use
        $specData[] = [
            'specification_id' => $specs['how_to_use'],
            'custom_value' => $productData['how_to_use'],
        ];

        // 6. Skin/Hair Type
        if ($productData['type'] === 'hair') {
            $hairTypeIds = $this->getSpecValueIds($specs['hair_type'], $specValues, $productData['skin_hair_type']);
            $specData[] = [
                'specification_id' => $specs['hair_type'],
                'custom_value' => implode(',', $hairTypeIds),
            ];
        } else {
            $skinTypeIds = $this->getSpecValueIds($specs['skin_type'], $specValues, $productData['skin_hair_type']);
            $specData[] = [
                'specification_id' => $specs['skin_type'],
                'custom_value' => implode(',', $skinTypeIds),
            ];
        }

        // 7. Quality & Safety specs
        $qualitySpecs = ['dermatologist_tested', 'non_comedogenic', 'cruelty_free', 'paraben_free', 'sulfate_free'];
        foreach ($qualitySpecs as $qualitySpec) {
            $specData[] = [
                'specification_id' => $specs[$qualitySpec],
                'custom_value' => 'Yes',
            ];
        }

        return $specData;
    }

    private function getProductData($productCode, $specValues)
    {
        $data = [
            'DK-FW-BRIGHT-100' => [
                'net_volume' => '100 ml',
                'price' => 399,
                'type' => 'skin',
                'ingredients' => ['Kojic Acid', 'Niacinamide', 'Glutathione', 'Vitamin C', 'Aloe Vera', 'Arbutin', 'Mulberry Extract', 'Vitamin E'],
                'benefits' => ['Brightening', 'Reduces Pigmentation', 'Deep Cleansing', 'Hydration', 'Reduces Dark Spots', 'Improves Skin Radiance', 'Promotes Skin Renewal', 'Reduces Dullness'],
                'how_to_use' => 'Apply a small amount on wet face, gently massage in circular motions, and rinse thoroughly with water. Use twice daily for best results.',
                'skin_hair_type' => ['All Skin Types'],
            ],
            'DK-MOIST-200' => [
                'net_volume' => '200 ml',
                'price' => 399,
                'type' => 'skin',
                'ingredients' => ['Glycerin', 'Hyaluronic Acid', 'Ceramides', 'Shea Butter', 'Aloe Vera', 'Olive Oil', 'Oatmeal Extract', 'Vitamin E'],
                'benefits' => ['Hydration', 'Barrier Repair', 'Soothing & Calming', 'Maintains Skin Balance'],
                'how_to_use' => 'Apply an adequate amount to clean skin and gently massage until fully absorbed. Use morning and evening for best results.',
                'skin_hair_type' => ['All Skin Types', 'Sensitive', 'Acne Prone'],
            ],
            'DK-SHAM-250' => [
                'net_volume' => '250 ml',
                'price' => 799,
                'type' => 'hair',
                'ingredients' => ['Ceramide', 'Liposomal Caffeine', 'Procapil', 'Keratin', 'Biotin', 'Aloe Vera', 'Argan Oil', 'Saw Palmetto', 'Tropaeolum Majus', 'Nasturtium Officinale'],
                'benefits' => ['Hair Fall Reduction', 'Hair Strengthening', 'Improves Shine', 'Improves Scalp Health', 'Promotes Hair Growth', 'Adds Density to Hair', 'Nourishes Hair'],
                'how_to_use' => 'Apply a generous amount on wet hair, massage gently into the scalp and hair, and rinse thoroughly. Repeat if necessary. For optimal results, use alongside Dr. Kinjal Conditioner.',
                'skin_hair_type' => ['All Hair Types'],
            ],
            'DK-SERUM-30' => [
                'net_volume' => '30 ml',
                'price' => 480,
                'type' => 'skin',
                'ingredients' => ['Salicylic Acid', 'Glycolic Acid'],
                'benefits' => ['Acne Control', 'Oil Control', 'Smooth Texture', 'Exfoliation', 'Pore Minimizing', 'Reduces Breakouts', 'Unclogs Pores', 'Reduces Blackheads & Whiteheads'],
                'how_to_use' => 'Apply 2–3 drops to clean, dry skin. Gently spread over the face or affected areas, preferably at night. Follow with a moisturizer. Use sunscreen during the day while using exfoliating acids.',
                'skin_hair_type' => ['Oily', 'Acne Prone'],
            ],
            'DK-BODY-250' => [
                'net_volume' => '250 ml',
                'price' => 420,
                'type' => 'skin',
                'ingredients' => ['Salicylic Acid', 'Aloe Vera', 'D-Panthenol', 'Hyaluronic Acid', 'Allantoin', 'Vitamin E'],
                'benefits' => ['Exfoliation', 'Acne Control', 'Oil Control', 'Smooth Texture', 'Soothing & Calming', 'Hydration'],
                'how_to_use' => 'Wet your skin thoroughly in the shower. Apply a small amount of bodywash to your hands, loofah, or sponge. Massage gently over the body, focusing on areas with uneven texture or bumps. Rinse off completely with water.',
                'skin_hair_type' => ['Oily', 'Acne Prone'],
            ],
            'DK-COND-100' => [
                'net_volume' => '100 ml',
                'price' => 330,
                'type' => 'hair',
                'ingredients' => ['D-Panthenol', 'Almond Oil', 'Sunflower Oil'],
                'benefits' => ['Improves Shine', 'Hair Strengthening', 'Nourishes Hair', 'Reduces Frizz'],
                'how_to_use' => 'After shampooing, apply an adequate amount to damp hair, focusing on mid-lengths and ends. Leave on for 2–3 minutes and rinse thoroughly. For best results, use with Dr. Kinjal Shampoo.',
                'skin_hair_type' => ['Dry', 'Frizzy', 'Damaged', 'Split Ends'],
            ],
            'DK-FW-ACNE-100' => [
                'net_volume' => '100 ml',
                'price' => 299,
                'type' => 'skin',
                'ingredients' => ['Salicylic Acid', 'Glycolic Acid', 'Lactic Acid', 'Aloe Vera', 'D-Panthenol', 'Vitamin E'],
                'benefits' => ['Acne Control', 'Oil Control', 'Exfoliation', 'Smooth Texture', 'Unclogs Pores', 'Reduces Blackheads & Whiteheads', 'Soothing & Calming'],
                'how_to_use' => 'Apply a small amount on wet face, gently massage in circular motions, and rinse thoroughly with water. Use twice daily for best results.',
                'skin_hair_type' => ['Oily', 'Acne Prone'],
            ],
            'DK-SOAP-75' => [
                'net_volume' => '75 g',
                'price' => 135,
                'type' => 'skin',
                'ingredients' => ['Kojic Acid', 'Glutathione', 'Niacinamide', 'Aloe Vera', 'Mulberry Extract', 'Vitamin C', 'Vitamin E', 'Arbutin'],
                'benefits' => ['Brightening', 'Reduces Pigmentation', 'Anti Aging', 'Smooth Texture', 'Deep Cleansing', 'Reduces Dark Spots', 'Improves Skin Radiance'],
                'how_to_use' => 'Lather the soap with water, gently cleanse the skin, and rinse thoroughly. Use daily for best results.',
                'skin_hair_type' => ['All Skin Types'],
            ],
            'DK-SUN-50' => [
                'net_volume' => '50 g',
                'price' => 398,
                'type' => 'skin',
                'ingredients' => ['Hyaluronic Acid', 'Vitamin C', 'Aloe Vera', 'Allantoin'],
                'benefits' => ['Sun Protection', 'Anti Aging', 'Hydration', 'Soothing & Calming'],
                'how_to_use' => 'Apply generously on the face and exposed areas 15–20 minutes before sun exposure. Reapply every 2–3 hours or after swimming, sweating, or towel drying.',
                'skin_hair_type' => ['All Skin Types', 'Sensitive', 'Acne Prone'],
            ],
        ];

        return $data[$productCode] ?? [
            'net_volume' => '100 ml',
            'price' => 299,
            'type' => 'skin',
            'ingredients' => [],
            'benefits' => [],
            'how_to_use' => 'Use as directed.',
            'skin_hair_type' => ['All Skin Types'],
        ];
    }

    private function getSpecValueIds($specId, $specValues, $values)
    {
        $valueIds = [];
        if (isset($specValues[$specId])) {
            foreach ($specValues[$specId] as $specValue) {
                if (in_array($specValue->value, $values)) {
                    $valueIds[] = $specValue->id;
                }
            }
        }
        return $valueIds;
    }

    private function seedProductRelationships()
    {
        $products = DB::table('products')->get();
        $categories = DB::table('categories')->pluck('id', 'slug');
        $tags = DB::table('tags')->pluck('id', 'name');

        foreach ($products as $product) {
            // Add to main category (primary)
            DB::table('category_product')->insert([
                'product_id' => $product->id,
                'category_id' => $product->main_category_id,
                'is_primary' => 1,
                'sort_order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Add tags based on product characteristics
            $productTags = ['Dermatologist Formulated', 'Clinical Skincare'];

            // Add specific tags based on product
            if (strpos($product->name, 'Brightening') !== false) {
                $productTags[] = 'Brightening';
                $productTags[] = 'Pigmentation';
            }
            if (strpos($product->name, 'Acne') !== false) {
                $productTags[] = 'Acne Treatment';
                $productTags[] = 'Oil Control';
            }
            if (strpos($product->name, 'Moisturizer') !== false) {
                $productTags[] = 'Hydration';
                $productTags[] = 'Barrier Repair';
            }
            if (strpos($product->name, 'Shampoo') !== false) {
                $productTags[] = 'Hair Fall Control';
                $productTags[] = 'Hair Strengthening';
            }
            if (strpos($product->name, 'Serum') !== false) {
                $productTags[] = 'Exfoliating';
            }
            if (strpos($product->name, 'Bodywash') !== false) {
                $productTags[] = 'Exfoliating';
                $productTags[] = 'Deep Cleansing';
            }
            if (strpos($product->name, 'Conditioner') !== false) {
                $productTags[] = 'Hair Strengthening';
            }
            if (strpos($product->name, 'Sunscreen') !== false) {
                $productTags[] = 'Sun Protection';
            }
            if (strpos($product->name, 'Soap') !== false) {
                $productTags[] = 'Brightening';
            }

            // Add quality tags
            $productTags[] = 'Non-Comedogenic';
            $productTags[] = 'Hypoallergenic';
            $productTags[] = 'Cruelty Free';
            $productTags[] = 'Paraben Free';
            $productTags[] = 'Sulfate Free';

            // Insert unique tags
            $uniqueTags = array_unique($productTags);
            foreach ($uniqueTags as $tagName) {
                if (isset($tags[$tagName])) {
                    DB::table('product_tags')->insert([
                        'product_id' => $product->id,
                        'tag_id' => $tags[$tagName],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Add related products (2-3 random products, not itself)
            $allProductIds = $products->pluck('id')->toArray();
            $relatedCount = min(3, count($allProductIds) - 1);
            if ($relatedCount > 0) {
                $possibleRelatedIds = array_diff($allProductIds, [$product->id]);
                $relatedIds = array_rand(array_flip($possibleRelatedIds), $relatedCount);

                if (!is_array($relatedIds)) {
                    $relatedIds = [$relatedIds];
                }

                foreach ($relatedIds as $relatedId) {
                    DB::table('related_products')->insert([
                        'product_id' => $product->id,
                        'related_product_id' => $relatedId,
                        'sort_order' => rand(1, 10),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    private function getProductDescription($productCode)
    {
        $descriptions = [
            'P1' => "<div class='space-y-4'>
                <p>Reveal visibly brighter, clearer, and healthier-looking skin with Dr. Kinjal Skin Brightening Face Wash, a scientifically formulated cleanser designed to gently cleanse while enhancing your skin's natural radiance.</p>
                <p>Enriched with powerful brightening and skin-loving ingredients like Kojic Acid, Niacinamide, Glutathione, Vitamin C, Arbutin, and Mulberry Extract, this face wash helps reduce pigmentation, dark spots, and dullness while promoting an even skin tone. The addition of Aloe Vera and Vitamin E ensures deep hydration, soothing care, and protection against environmental damage.</p>
                <p>Its mild exfoliating action removes impurities, excess oil, and dead skin cells, leaving your skin fresh, smooth, and glowing after every wash.</p>
            </div>",
            'P2' => "<div class='space-y-4'>
                <p>Dr. Kinjal Moisturizer is a daily hydration formula designed to deeply moisturize, repair the skin barrier, and maintain long-lasting comfort. Its advanced blend of hydrating, soothing, and barrier-strengthening ingredients delivers effective moisture without heaviness, making it ideal for everyday use.</p>
                <p>The lightweight, non-greasy texture absorbs quickly to provide a soft, smooth finish while keeping the skin nourished, balanced, and protected. Suitable for all skin types, including sensitive and acne-prone skin, this moisturizer is non-comedogenic and gentle enough for regular use.</p>
            </div>",
            'P3' => "<div class='space-y-4'>
                <p>Dr. Kinjal 3-in-1 Shampoo is a multi-action haircare solution designed to cleanse, nourish, and strengthen hair while targeting hair fall and promoting healthy growth. Suitable for all hair types, it combines advanced botanical extracts and clinically researched actives to provide professional-grade haircare at home.</p>
                <p>Enriched with Ceramide, Liposomal Caffeine, Procapil, Keratin, Biotin, Aloe Vera, Argan Oil, Saw Palmetto, Tropaeolum Majus, and Nasturtium Officinale, this shampoo helps reduce hair fall, improve scalp health, and nourish hair from root to tip.</p>
            </div>",
            'P4' => "<div class='space-y-4'>
                <p>Experience dermatologist-inspired clarity with Dr. Kinjal Face Serum, a precision-formulated exfoliating treatment designed to address acne, blackheads, clogged pores, excess oil, and blemishes. This advanced serum combines clinically trusted exfoliating acids to promote clearer, smoother, and more refined skin.</p>
                <p>The lightweight, fast-absorbing formula works both on the skin's surface and deep within pores to gently remove dead skin cells, regulate oil production, and improve overall skin tone. With consistent use, skin appears visibly clearer, brighter, and healthier.</p>
            </div>",
            'P5' => "<div class='space-y-4'>
                <p>Transform your shower routine with Dr. Kinjal Bodywash, specially formulated for acne-prone and oily skin. Infused with gentle yet effective actives, it not only cleanses but also treats common skin concerns like uneven texture and strawberry skin.</p>
                <p>Gentle yet powerful, this bodywash leaves your skin feeling fresh, smooth, and confident every day.</p>
            </div>",
            'P6' => "<div class='space-y-4'>
                <p>Dr. Kinjal Conditioner is a deeply nourishing haircare formula designed to restore moisture, smoothness, and shine to dry, split, frizzy, and dull hair. Enriched with a blend of hydrating and nourishing ingredients, it helps repair damage, control frizz, and improve overall hair texture, leaving hair soft, manageable, and healthy-looking.</p>
                <p>Formulated with D-Panthenol, Almond Oil, and Sunflower Oil, this conditioner works to strengthen hair strands, lock in moisture, and protect hair from dryness and environmental stress.</p>
            </div>",
            'P8' => "<div class='space-y-4'>
                <p>Dr. Kinjal Facewash is an exfoliating cleanser formulated for acne-prone and oily skin. It gently removes impurities, excess oil, and dead skin cells while helping to prevent breakouts, unclog pores, and improve skin texture.</p>
                <p>Infused with a blend of Salicylic Acid, Glycolic Acid, Lactic Acid, Aloe Vera, D-Panthenol, and Vitamin E, this facewash not only cleanses effectively but also soothes, hydrates, and protects the skin.</p>
            </div>",
            'P9' => "<div class='space-y-4'>
                <p>Dr. Kinjal Skin Brightening Soap is a cleansing bar formulated to deeply cleanse, brighten, and rejuvenate the skin. Enriched with powerful brightening and antioxidant ingredients, it helps reduce pigmentation, dullness, and uneven skin tone while maintaining skin health.</p>
                <p>Its gentle exfoliating action removes impurities and dead skin cells without stripping moisture, leaving the skin fresh, smooth, and visibly radiant. Suitable for daily use on the face and body.</p>
            </div>",
            'P10' => "<div class='space-y-4'>
                <p>Dr. Kinjal Sunscreen SPF 50++ is a high-performance sunscreen that offers broad-spectrum protection against UVA, UVB, Blue Light (BL), and HEV rays. Its lightweight, non-greasy formula is non-comedogenic and leaves no white cast, making it ideal for daily use on all skin types.</p>
                <p>Enriched with Hyaluronic Acid, Vitamin C, Aloe Vera, and Allantoin, this sunscreen not only shields your skin from harmful rays but also hydrates, soothes, and protects against premature aging, pigmentation, and environmental stress.</p>
            </div>",
        ];

        return $descriptions[$productCode] ?? "<p>Professional dermatologist-formulated product for effective results.</p>";
    }
}