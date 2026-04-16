<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // use WithoutModelEvents;

    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

        $this->call([
            AdminUserSeeder::class,
            RolePermissionSeeder::class,
            SettingSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            ProductImageSeeder::class,
            MarketplaceLinkSeeder::class,
            BlogSeeder::class,
            BannerSeeder::class,
            StaticPageSeeder::class,
            VoucherSeeder::class,
        ]);

        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
    }
}
