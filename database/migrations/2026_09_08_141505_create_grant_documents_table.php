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
    Schema::create('grant_documents', function (Blueprint $table) {
        $table->id();
        $table->foreignId('grant_id')->constrained()->onDelete('cascade');
        $table->string('title');
        $table->enum('category', ['proposal', 'compliance', 'financial_report', 'narrative_report', 'other']);
        $table->string('file_path');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grant_documents');
    }
};
