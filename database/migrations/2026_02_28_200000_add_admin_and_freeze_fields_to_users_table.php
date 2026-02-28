<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->boolean('is_admin')->default(false)->after('password');
            $table->boolean('is_frozen')->default(false)->after('is_admin');
            $table->timestamp('frozen_at')->nullable()->after('is_frozen');
            $table->string('freeze_reason', 255)->nullable()->after('frozen_at');

            $table->index(['is_admin', 'is_frozen']);
        });

        $adminEmail = env('ADMIN_EMAIL', 'admin@vistora.local');

        DB::table('users')
            ->where('email', $adminEmail)
            ->update(['is_admin' => true]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropIndex(['is_admin', 'is_frozen']);
            $table->dropColumn(['is_admin', 'is_frozen', 'frozen_at', 'freeze_reason']);
        });
    }
};
