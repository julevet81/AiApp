<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $guardName = config('auth.defaults.guard');

        // Create permissions
        $permissions = [
            // Applications
            'applications.view',
            'applications.create',
            'applications.update',
            'applications.delete',
            // Users
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            // Roles
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',
            // Permissions
            'permissions.view',
            'permissions.create',
            'permissions.update',
            'permissions.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => $guardName],
                ['name' => $permission, 'guard_name' => $guardName]
            );
        }

        // Create roles and assign permissions
        $superAdmin = Role::firstOrCreate(
            ['name' => 'super_admin', 'guard_name' => $guardName],
            ['name' => 'super_admin', 'guard_name' => $guardName]
        );
        $superAdmin->givePermissionTo(Permission::all());

        $admin = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => $guardName],
            ['name' => 'admin', 'guard_name' => $guardName]
        );
        $admin->givePermissionTo([
            'applications.view', 'applications.create', 'applications.update', 'applications.delete',
            'users.view', 'users.create', 'users.update', 'users.delete',
            'roles.view', 'roles.create', 'roles.update',
            'permissions.view',
        ]);

        $user = Role::firstOrCreate(
            ['name' => 'user', 'guard_name' => $guardName],
            ['name' => 'user', 'guard_name' => $guardName]
        );
        $user->givePermissionTo([
            'applications.view', 'applications.create', 'applications.update',
            'users.view',
        ]);
    }
}
