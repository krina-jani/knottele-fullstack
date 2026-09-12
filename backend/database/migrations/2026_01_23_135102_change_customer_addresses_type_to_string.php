<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->string('type', 50)->default('shipping')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            // We can't easily revert to enum if invalid data exists, but we'll define the reversal for schema structure.
            // Note: This might fail if 'Home' is present in data.
            // $table->enum('type', ['shipping', 'billing', 'both'])->default('shipping')->change();
        });
    }
};
