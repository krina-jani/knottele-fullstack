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
        // 1. Drop child / pivot tables first to satisfy foreign key constraints
        Schema::dropIfExists('variant_attributes');
        Schema::dropIfExists('product_specifications');
        Schema::dropIfExists('product_tags');
        Schema::dropIfExists('category_attributes');
        Schema::dropIfExists('category_spec_groups');
        Schema::dropIfExists('spec_group_specs');
        Schema::dropIfExists('specification_values');
        Schema::dropIfExists('attribute_values');

        // 2. Drop parent tables
        Schema::dropIfExists('specifications');
        Schema::dropIfExists('specification_groups');
        Schema::dropIfExists('attributes');
        Schema::dropIfExists('tags');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not reversible
    }
};
