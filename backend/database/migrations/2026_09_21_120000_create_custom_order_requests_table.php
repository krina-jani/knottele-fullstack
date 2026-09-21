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
        Schema::create('custom_order_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference_id', 50)->unique();
            $table->unsignedBigInteger('customer_id')->nullable()->index();
            $table->string('customer_name');
            $table->string('customer_email')->index();
            $table->string('customer_phone', 50)->nullable();
            $table->string('category', 100)->default('Bouquet');
            $table->string('selected_palette', 100)->nullable();
            $table->json('custom_colors')->nullable();
            $table->text('custom_color_notes')->nullable();
            $table->string('size_preference', 100)->nullable();
            $table->text('personalization')->nullable();
            $table->text('design_notes')->nullable();
            $table->string('urgency', 100)->nullable();
            $table->string('budget_range', 100)->nullable();
            $table->string('reference_image_url', 1000)->nullable();
            $table->string('reference_image_name', 255)->nullable();
            $table->string('status', 50)->default('pending')->index(); // pending, in_review, quoted, approved, in_progress, completed, rejected
            $table->decimal('quoted_price', 10, 2)->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_order_requests');
    }
};
