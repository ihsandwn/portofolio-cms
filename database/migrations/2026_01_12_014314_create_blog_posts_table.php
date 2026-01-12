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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->json('content_blocks'); // [{"type": "text", ...}, ...]
            $table->json('seo_meta')->nullable(); // {"title": "...", "description": "...", ...}
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('category_id')->nullable()->index(); // Indexed for performance
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
            
            // Additional Indexing as requested
            $table->index(['slug', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
