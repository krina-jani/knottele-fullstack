<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $iconMap = [
            'Keychain' => 'keychain',
            'Bouquet' => 'bouquet',
            'Soft Toys' => 'soft_toys',
            'Bags' => 'bag',
            'Coin Purse' => 'coin_purse',
            'Flower pot' => 'flower',
            'Phone Cover' => 'phone_cover',
            'Cup' => 'cup',
            'Bookmark' => 'bookmark',
            'Hair Accessories' => 'scissors',
            'Clothing' => 'clothing',
            'Custom Concept' => 'palette',
        ];

        foreach ($iconMap as $title => $icon) {
            DB::table('media')
                ->where('page', 'custom_order')
                ->where('section', 'custom_order_items')
                ->where(function ($q) use ($title) {
                    $q->where('title', $title)
                      ->orWhere('title', 'like', '%' . $title . '%');
                })
                ->update(['tag_text' => $icon]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('media')
            ->where('page', 'custom_order')
            ->where('section', 'custom_order_items')
            ->update(['tag_text' => 'Flower2']);
    }
};
