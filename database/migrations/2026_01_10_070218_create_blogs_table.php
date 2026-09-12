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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->json('title'); // Translatable
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->json('content'); // Translatable
            $table->string('image')->nullable();
            $table->enum('category', ['tech', 'ai', 'tutorial'])->default('tech');
            $table->timestamp('published_at')->nullable();
            
            // Indexes
            $table->index(['category', 'published_at']);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
