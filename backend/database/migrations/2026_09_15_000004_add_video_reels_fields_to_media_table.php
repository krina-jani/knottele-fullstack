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
            if (!Schema::hasColumn('media', 'content_type')) {
                $table->string('content_type', 50)->nullable()->default('video')->after('file_type')->index();
            }
            if (!Schema::hasColumn('media', 'category_name')) {
                $table->string('category_name', 150)->nullable()->after('content_type');
            }
            if (!Schema::hasColumn('media', 'video_url')) {
                $table->string('video_url', 500)->nullable()->after('mobile_image_path');
            }
            if (!Schema::hasColumn('media', 'duration')) {
                $table->string('duration', 50)->nullable()->after('video_url');
            }
            if (!Schema::hasColumn('media', 'audio_name')) {
                $table->string('audio_name', 255)->nullable()->after('duration');
            }
            if (!Schema::hasColumn('media', 'likes_count')) {
                $table->integer('likes_count')->default(0)->after('audio_name');
            }
            if (!Schema::hasColumn('media', 'comments_count')) {
                $table->integer('comments_count')->default(0)->after('likes_count');
            }
            if (!Schema::hasColumn('media', 'views_count')) {
                $table->integer('views_count')->default(0)->after('comments_count');
            }
            if (!Schema::hasColumn('media', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('is_active')->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $columns = [
                'content_type',
                'category_name',
                'video_url',
                'duration',
                'audio_name',
                'likes_count',
                'comments_count',
                'views_count',
                'is_featured',
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('media', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
