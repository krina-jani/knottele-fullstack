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
        Schema::table('media', function (Blueprint $table) {
            if (!Schema::hasColumn('media', 'secondary_cta_text')) {
                $table->string('secondary_cta_text', 100)->nullable()->after('cta_link');
            }
            if (!Schema::hasColumn('media', 'secondary_cta_link')) {
                $table->string('secondary_cta_link', 255)->nullable()->after('secondary_cta_text');
            }
            if (!Schema::hasColumn('media', 'mobile_image_path')) {
                $table->string('mobile_image_path', 500)->nullable()->after('file_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $columns = ['secondary_cta_text', 'secondary_cta_link', 'mobile_image_path'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('media', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
