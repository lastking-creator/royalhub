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
    Schema::create('communication_logs', function (Blueprint $table) {
        $table->id();
        $table->string('type'); // 'email', 'sms', 'announcement'
        $table->string('recipient_group'); // 'all_members', 'approved_members', 'beneficiaries', 'specific'
        $table->string('recipient_contact')->nullable(); // Email or Phone number if specific
        $table->string('subject')->nullable();
        $table->text('message');
        $table->enum('status', ['sent', 'failed', 'queued'])->default('sent');
        $table->foreignId('sender_id')->nullable()->constrained('users')->onDelete('set null');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communication_logs');
    }
};
