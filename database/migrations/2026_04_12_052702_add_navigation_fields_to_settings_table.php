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
        Schema::table('settings', function (Blueprint $table) {
            $table->json('header_navigation')->nullable()->after('social_media');
            $table->json('footer_navigation')->nullable()->after('header_navigation');
            $table->json('trending_keywords')->nullable()->after('footer_navigation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['header_navigation', 'footer_navigation', 'trending_keywords']);
        });
    }
};
