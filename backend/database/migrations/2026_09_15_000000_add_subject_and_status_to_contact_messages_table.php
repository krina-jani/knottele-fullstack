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
        Schema::table('contact_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('contact_messages', 'subject')) {
                $table->string('subject')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('contact_messages', 'status')) {
                $table->string('status')->default('unread')->after('message');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            if (Schema::hasColumn('contact_messages', 'subject')) {
                $table->dropColumn('subject');
            }
            if (Schema::hasColumn('contact_messages', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
