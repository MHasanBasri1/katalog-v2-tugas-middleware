<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('rating_avg', 2, 1)->unsigned()->default(0)->after('likes_count');
            $table->unsignedInteger('rating_count')->default(0)->after('rating_avg');
            $table->index(['status', 'rating_avg']);
        });

        DB::table('products')
            ->where('likes_count', '>', 0)
            ->update([
                'rating_count' => DB::raw('likes_count'),
                // SQLite tidak punya fungsi LEAST, jadi gunakan CASE agar lintas database.
                'rating_avg' => DB::raw(
                    'CASE
                        WHEN ROUND(4.0 + (likes_count / 500.0), 1) > 5.0 THEN 5.0
                        ELSE ROUND(4.0 + (likes_count / 500.0), 1)
                    END'
                ),
            ]);
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['status', 'rating_avg']);
            $table->dropColumn(['rating_avg', 'rating_count']);
        });
    }
};
