<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify payment_status from restrictive enum to varchar(50) so all payment statuses
        // ('pending', 'paid', 'partially_paid', 'partially_refunded', 'failed', 'refunded')
        // are saved without MySQL data truncation / enum errors.
        try {
            DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status VARCHAR(50) NOT NULL DEFAULT 'pending'");
        } catch (\Throwable $e) {
            // Log or fallback
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status ENUM('pending', 'paid', 'failed', 'refunded', 'partially_refunded') NOT NULL DEFAULT 'pending'");
        } catch (\Throwable $e) {
        }
    }
};
