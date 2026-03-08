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
            $table->json('marketplaces')->nullable()->after('favicon');
        });

        // Seed default marketplaces for existing settings
        \Illuminate\Support\Facades\DB::table('settings')->update([
            'marketplaces' => json_encode(['Shopee', 'Tokopedia', 'Lazada', 'Blibli', 'Tiktok Shop'])
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('marketplaces');
        });
    }
};
