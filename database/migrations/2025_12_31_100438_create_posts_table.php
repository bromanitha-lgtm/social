<?php
// database/migrations/2025_12_31_100438_create_posts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            
            // Status for posts (draft, published, archived)
            $table->string('status')->default('published');
            
            // When the post was published (if scheduled)
            $table->timestamp('published_at')->nullable();
            
            // Post image
            $table->string('image')->nullable();
            
            // Counters
            $table->integer('views')->default(0);
            $table->integer('likes_count')->default(0);
            $table->integer('comments_count')->default(0);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};