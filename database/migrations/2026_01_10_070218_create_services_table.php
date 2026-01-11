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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->json('title'); // Translatable
            $table->string('slug')->unique();
            $table->json('description')->nullable(); // Translatable
            $table->string('icon')->nullable(); // SVG code or path
            $table->enum('category', ['web_dev', 'ai_solution', 'system_arch'])->default('web_dev');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
