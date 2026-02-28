<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('original_price', 15, 2)->nullable()->after('price');
            $table->unsignedInteger('sold_count')->default(0)->after('status');
            $table->boolean('is_featured')->default(false)->after('sold_count');

            $table->index(['status', 'is_featured', 'sold_count']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['status', 'is_featured', 'sold_count']);
            $table->dropIndex(['status', 'created_at']);
            $table->dropColumn(['original_price', 'sold_count', 'is_featured']);
        });
    }
};
