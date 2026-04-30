<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

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

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'dashboard.view',
            'settings.manage',
            'categories.manage',
            'products.manage',
            'blogs.manage',
            'users.manage',
            'banners.manage',
            'static_pages.manage',
            'vouchers.manage',
        ];

        foreach ($permissions as $permissionName) {
            Permission::query()->updateOrCreate(
                ['name' => $permissionName, 'guard_name' => 'web']
            );
        }

        $adminRole = Role::query()->updateOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $userRole = Role::query()->updateOrCreate(['name' => 'member', 'guard_name' => 'web']);
        $developerRole = Role::query()->updateOrCreate(['name' => 'developer', 'guard_name' => 'web']);
        $superAdminRole = Role::query()->updateOrCreate(['name' => 'super admin', 'guard_name' => 'web']);

        $adminRole->syncPermissions($permissions);
        $developerRole->syncPermissions($permissions);
        $superAdminRole->syncPermissions($permissions);
        $userRole->syncPermissions([]);

        User::query()->each(function (User $user): void {
            // Jangan timpa role jika user adalah developer atau super admin
            if (!$user->hasRole(['developer', 'super admin'])) {
                $role = $user->is_admin ? 'admin' : 'member';
                $user->syncRoles([$role]);
            }
        });
    }
}
