<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('target_community'); // e.g. "Likoni Youth" or "General Members"
            $table->foreignId('manager_id')->constrained('users')->onDelete('cascade'); // CBO Member as Manager
            $table->string('status')->default('Planning');
            $table->decimal('budget', 12, 2)->default(0.00);
            $table->timestamps();
        });

        // Add project_id to programs
        Schema::table('programs', function (Blueprint $table) {
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropColumn('project_id');
        });
        Schema::dropIfExists('projects');
    }
};