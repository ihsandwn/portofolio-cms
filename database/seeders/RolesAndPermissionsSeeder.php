<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            'view_dashboard',
            'view_portfolios',
            'manage_portfolios',
            'view_services',
            'manage_services',
            'view_settings',
            'manage_settings',
            'view_menus',
            'view_menus',
            'manage_menus',
            'view_pages',
            'manage_pages',
            'view_users',
            'manage_users',
            'view_roles',
            'manage_roles',
            'view_permissions',
            'manage_permissions',
            'view_permissions',
            'manage_permissions',
            'view_blogs',
            'manage_blogs',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // create roles and assign created permissions

        // Editor
        $role = Role::firstOrCreate(['name' => 'editor']);
        $role->syncPermissions([
            'view_dashboard',
            'view_portfolios', 'manage_portfolios',
            'view_services', 'manage_services',
            'view_services', 'manage_services',
            'view_menus', 'manage_menus',
            'view_pages', 'manage_pages',
            'view_menus', 'manage_menus',
            'view_pages', 'manage_pages',
            'view_blogs', 'manage_blogs',
        ]);

        // Super Admin
        $role = Role::firstOrCreate(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
    }
}
