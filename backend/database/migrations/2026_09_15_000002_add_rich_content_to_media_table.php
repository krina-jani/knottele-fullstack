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
            if (!Schema::hasColumn('media', 'subtitle')) {
                $table->string('subtitle', 255)->nullable()->after('title');
            }
            if (!Schema::hasColumn('media', 'description')) {
                $table->text('description')->nullable()->after('subtitle');
            }
            if (!Schema::hasColumn('media', 'cta_text')) {
                $table->string('cta_text', 100)->nullable()->after('description');
            }
            if (!Schema::hasColumn('media', 'cta_link')) {
                $table->string('cta_link', 255)->nullable()->after('cta_text');
            }
            if (!Schema::hasColumn('media', 'tag_text')) {
                $table->string('tag_text', 150)->nullable()->after('cta_link');
            }
            if (!Schema::hasColumn('media', 'metadata')) {
                $table->json('metadata')->nullable()->after('tag_text');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $columns = ['subtitle', 'description', 'cta_text', 'cta_link', 'tag_text', 'metadata'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('media', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
