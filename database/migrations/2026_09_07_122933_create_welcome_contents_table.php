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
    Schema::create('welcome_contents', function (Blueprint $table) {
        $table->id();
        $table->string('title')->default('Welcome to Our Community-Based Organization');
        $table->text('subtitle')->nullable();
        $table->text('body_content')->nullable();
        $table->string('hero_image')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('welcome_contents');
    }
};
