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
    Schema::table('welcome_contents', function (Blueprint $table) {
        $table->text('mission')->nullable()->after('body_content');
        $table->text('vision')->nullable()->after('mission');
    });
}

public function down(): void
{
    Schema::table('welcome_contents', function (Blueprint $table) {
        $table->dropColumn(['mission', 'vision']);
    });
}
};
