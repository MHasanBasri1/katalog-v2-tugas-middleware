<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('favorites')) {
            Schema::create('favorites', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('user_id')
                    ->constrained('users')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
                $table->foreignId('product_id')
                    ->constrained('products')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['user_id', 'product_id']);
                $table->index('product_id');
            });
        }

        if (Schema::hasTable('product_likes')) {
            $timestamp = now();

            DB::table('product_likes')
                ->select('user_id', 'product_id')
                ->whereNotNull('user_id')
                ->distinct()
                ->get()
                ->each(function ($row) use ($timestamp): void {
                    DB::table('favorites')->updateOrInsert(
                        [
                            'user_id' => $row->user_id,
                            'product_id' => $row->product_id,
                        ],
                        [
                            'created_at' => $timestamp,
                            'updated_at' => $timestamp,
                        ]
                    );
                });

            Schema::drop('product_likes');
        }

        if (Schema::hasTable('product_views')) {
            Schema::drop('product_views');
        }

        if (Schema::hasColumn('products', 'view_count')) {
            Schema::table('products', function (Blueprint $table): void {
                $table->dropColumn('view_count');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('product_likes')) {
            Schema::create('product_likes', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('product_id')
                    ->constrained('products')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
                $table->foreignId('user_id')
                    ->nullable()
                    ->constrained('users')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['product_id', 'user_id']);
            });
        }

        if (Schema::hasTable('favorites')) {
            Schema::drop('favorites');
        }

        if (! Schema::hasColumn('products', 'view_count')) {
            Schema::table('products', function (Blueprint $table): void {
                $table->unsignedInteger('view_count')->default(0)->after('sold_count');
            });
        }
    }
};
