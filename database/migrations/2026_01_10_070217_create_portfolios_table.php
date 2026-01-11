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
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->json('title'); // Translatable
            $table->string('slug')->unique();
            $table->json('description')->nullable(); // Translatable
            $table->string('image')->nullable();
            $table->string('client')->nullable();
            $table->string('url')->nullable();
            $table->string('repo_url')->nullable();
            $table->enum('type', ['web', 'ai_agent', 'consulting'])->default('web');
            $table->json('tech_stack')->nullable(); // ['Laravel', 'Python', 'OpenAI']
            $table->longText('case_study')->nullable(); // Rich text explanation
            $table->json('meta_data')->nullable(); // For AI specific stats
            $table->date('completed_at')->nullable();
            $table->boolean('is_featured')->default(false);
            
            // Indexes for performance
            $table->index(['type', 'is_featured']);
            $table->index('created_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
