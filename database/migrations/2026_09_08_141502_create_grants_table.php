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
    Schema::create('grants', function (Blueprint $table) {
        $table->id();
        $table->string('funder_name');
        $table->string('title');
        $table->decimal('amount', 12, 2)->default(0.00); // KSh value
        $table->date('application_deadline')->nullable();
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();
        $table->enum('status', ['draft', 'submitted', 'awarded', 'rejected'])->default('draft');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grants');
    }
};
