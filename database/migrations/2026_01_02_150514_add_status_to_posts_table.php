<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            // Add status column if it doesn't exist
            if (!Schema::hasColumn('posts', 'status')) {
                $table->enum('status', ['draft', 'published'])->default('published')->after('comments_count');
            }
            
            // Add published_at column if it doesn't exist
            if (!Schema::hasColumn('posts', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('status');
            }
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            // Remove columns if they exist
            if (Schema::hasColumn('posts', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('posts', 'published_at')) {
                $table->dropColumn('published_at');
            }
        });
    }
};