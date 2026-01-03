<?php
// database/migrations/2026_01_03_120000_add_extra_columns_to_posts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        Schema::table('posts', function (Blueprint $table) {
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('posts', 'status')) {
                $table->string('status')->default('published')->after('content');
            }
            
            if (!Schema::hasColumn('posts', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('posts', 'views')) {
                $table->integer('views')->default(0)->after('image');
            }
            
            if (!Schema::hasColumn('posts', 'likes_count')) {
                $table->integer('likes_count')->default(0)->after('views');
            }
            
            if (!Schema::hasColumn('posts', 'comments_count')) {
                $table->integer('comments_count')->default(0)->after('likes_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Optional: Remove columns if needed
            // $table->dropColumn(['status', 'published_at', 'views', 'likes_count', 'comments_count']);
        });
    }
};