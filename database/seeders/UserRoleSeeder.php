<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {

        // Pastikan role ada
        Role::findOrCreate('developer', 'web');
        Role::findOrCreate('super admin', 'web');
        Role::findOrCreate('admin', 'web');
        Role::findOrCreate('member', 'web');

        // 1. Akun Developer
        $developer = User::updateOrCreate(
            ['email' => 'dev@katalogue.com'],
            [
                'name' => 'Developer Mode',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'is_admin' => true,
                'is_frozen' => false,
            ]
        );
        $developer->syncRoles(['developer']);

        // 2. Akun Super Admin
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@katalogue.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'is_admin' => true,
                'is_frozen' => false,
            ]
        );
        $superAdmin->syncRoles(['super admin']);

        // 3. Akun Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@katalogue.com'],
            [
                'name' => 'Admin Toko',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'is_admin' => true,
                'is_frozen' => false,
            ]
        );
        $admin->syncRoles(['admin']);

        // 4. Akun Member
        $member = User::updateOrCreate(
            ['email' => 'member@katalogue.com'],
            [
                'name' => 'Member Biasa',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'is_admin' => false,
                'is_frozen' => false,
            ]
        );
        $member->syncRoles(['member']);
    }
}
