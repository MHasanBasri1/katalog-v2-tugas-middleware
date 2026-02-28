<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        if (
            ! Schema::hasTable('roles') ||
            ! Schema::hasTable('permissions') ||
            ! Schema::hasTable('model_has_roles') ||
            ! Schema::hasTable('role_has_permissions')
        ) {
            return;
        }

        $permissions = [
            'dashboard.view',
            'settings.manage',
            'categories.manage',
            'products.manage',
            'blogs.manage',
            'users.manage',
            'banners.manage',
            'static_pages.manage',
        ];

        foreach ($permissions as $permissionName) {
            Permission::findOrCreate($permissionName, 'web');
        }

        $adminRole = Role::findOrCreate('admin', 'web');
        $userRole = Role::findOrCreate('user', 'web');

        $adminRole->syncPermissions($permissions);
        $userRole->syncPermissions([]);

        User::query()->each(function (User $user): void {
            $role = $user->is_admin ? 'admin' : 'user';
            $user->syncRoles([$role]);
        });
    }
}
