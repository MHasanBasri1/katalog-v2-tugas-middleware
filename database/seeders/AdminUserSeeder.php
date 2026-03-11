<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $name = env('ADMIN_NAME', 'Admin Kataloque');
        $email = env('ADMIN_EMAIL', 'jayanuxid@gmail.com');
        $password = env('ADMIN_PASSWORD', 'admin12345');

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
                'is_admin' => true,
                'is_frozen' => false,
                'frozen_at' => null,
                'freeze_reason' => null,
            ]
        );

        if (Schema::hasTable('roles') && Schema::hasTable('model_has_roles')) {
            Role::findOrCreate('admin', 'web');
            Role::findOrCreate('user', 'web');
            $user->syncRoles(['admin']);
        }
    }
}
