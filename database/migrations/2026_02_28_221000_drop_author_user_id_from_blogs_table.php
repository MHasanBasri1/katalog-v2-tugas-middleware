<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('blogs', 'author_user_id')) {
            return;
        }

        Schema::table('blogs', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('author_user_id');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('blogs', 'author_user_id')) {
            return;
        }

        Schema::table('blogs', function (Blueprint $table): void {
            $table->foreignId('author_user_id')->nullable()->after('author_name')->constrained('users')->nullOnDelete();
        });
    }
};
