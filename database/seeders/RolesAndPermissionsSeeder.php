<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            'view dashboard',
            'manage users',
            'manage roles',
            'manage portfolios',
            'manage blogs',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // create roles and assign created permissions

        // this can be done as separate statements
        $role = Role::firstOrCreate(['name' => 'editor']);
        $role->givePermissionTo(['view dashboard', 'manage portfolios', 'manage blogs']);

        $role = Role::firstOrCreate(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());

        // Assign super-admin to the first user if exists
        $user = User::first();
        if ($user) {
           $user->assignRole('super-admin');
        }
    }
}
