<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add performance indexes to blog_posts.
     *
     * - published_at: used in scopePublished() WHERE + ORDER BY
     * - is_pinned:    used in scopePinned() WHERE
     *
     * These columns were missing explicit indexes, causing full table scans
     * on every published blog query.
     */
    public function up(): void
    {
        Schema::table('blog_posts', static function (Blueprint $table) {
            $table->index('published_at');
            $table->index('is_pinned');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_posts', static function (Blueprint $table) {
            $table->dropIndex(['published_at']);
            $table->dropIndex(['is_pinned']);
        });
    }
};
