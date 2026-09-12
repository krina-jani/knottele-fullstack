<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Setting::where('key', 'store_phone_alt')->exists()) {
            Setting::create([
                'group' => 'general',
                'key' => 'store_phone_alt',
                'value' => '',
                'label' => 'Alternative Phone Number',
                'type' => 'text',
                'description' => 'Secondary contact phone number',
                'is_encrypted' => false,
                'is_public' => true,
                'sort_order' => 35
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Setting::where('key', 'store_phone_alt')->delete();
    }
};
