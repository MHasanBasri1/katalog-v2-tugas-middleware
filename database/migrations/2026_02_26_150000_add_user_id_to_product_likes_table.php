<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_likes', function (Blueprint $table): void {
            $table->foreignId('user_id')
                ->nullable()
                ->after('product_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->unique(['product_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::table('product_likes', function (Blueprint $table): void {
            $table->dropUnique('product_likes_product_id_user_id_unique');
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
