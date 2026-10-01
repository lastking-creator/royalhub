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
        Schema::table('donations', function (Blueprint $table) {
    $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            
            // Allow both registered members and external donors
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('donor_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            
            $table->string('type')->default('cash'); // cash, in-kind
            $table->decimal('amount', 12, 2)->default(0.00);
            $table->string('purpose')->nullable(); // Dues, Welfare, Registration Fee
            $table->string('payment_method')->default('mpesa'); // mpesa, cash, bank
            $table->string('reference_number')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('donated_at')->useCurrent();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};