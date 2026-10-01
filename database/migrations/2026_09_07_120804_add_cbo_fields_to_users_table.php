<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. Personal Information
            $table->date('date_of_birth')->nullable()->after('email');
            $table->string('id_number')->nullable()->after('date_of_birth');
            $table->string('physical_address')->nullable()->after('id_number');

            // 2. Contact Details
            $table->string('whatsapp_number')->nullable()->after('phone_number');
            $table->string('next_of_kin_name')->nullable()->after('whatsapp_number');
            $table->string('next_of_kin_relationship')->nullable()->after('next_of_kin_name');
            $table->string('next_of_kin_phone')->nullable()->after('next_of_kin_relationship');

            // 3. Skills & Interests
            $table->string('occupation')->nullable()->after('next_of_kin_phone');
            $table->text('talents_skills')->nullable()->after('occupation');

            // 4. Member Declaration
            $table->boolean('terms_accepted')->default(false)->after('talents_skills');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'id_number',
                'physical_address',
                'whatsapp_number',
                'next_of_kin_name',
                'next_of_kin_relationship',
                'next_of_kin_phone',
                'occupation',
                'talents_skills',
                'terms_accepted',
            ]);
        });
    }
};