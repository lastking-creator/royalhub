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
    Schema::create('grant_deliverables', function (Blueprint $table) {
        $table->id();
        $table->foreignId('grant_id')->constrained()->onDelete('cascade');
        $table->string('title');
        $table->date('due_date');
        $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grant_deliverables');
    }
};
