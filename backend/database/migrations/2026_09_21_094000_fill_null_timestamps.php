<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = ['categories', 'brands', 'products', 'product_variants', 'tax_classes', 'tax_rates', 'media'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                if (Schema::hasColumn($table, 'created_at')) {
                    DB::table($table)->whereNull('created_at')->update([
                        'created_at' => now(),
                    ]);
                }
                if (Schema::hasColumn($table, 'updated_at')) {
                    DB::table($table)->whereNull('updated_at')->update([
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
