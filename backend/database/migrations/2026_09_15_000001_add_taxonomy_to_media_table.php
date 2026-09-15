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
            if (!Schema::hasColumn('media', 'page')) {
                $table->string('page', 50)->nullable()->default('homepage')->after('alt_text')->index();
            }
            if (!Schema::hasColumn('media', 'section')) {
                $table->string('section', 50)->nullable()->default('unassigned')->after('page')->index();
            }
            if (!Schema::hasColumn('media', 'slot')) {
                $table->string('slot', 50)->nullable()->default('default')->after('section')->index();
            }
            if (!Schema::hasColumn('media', 'device')) {
                $table->string('device', 20)->default('all')->after('slot')->index();
            }
            if (!Schema::hasColumn('media', 'title')) {
                $table->string('title', 255)->nullable()->after('device');
            }
            if (!Schema::hasColumn('media', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('title');
            }
            if (!Schema::hasColumn('media', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('sort_order')->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $columns = ['page', 'section', 'slot', 'device', 'title', 'sort_order', 'is_active'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('media', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
