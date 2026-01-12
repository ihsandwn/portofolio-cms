<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Roles & Permissions
        $this->call(RolesAndPermissionsSeeder::class);
        
        // 2. User
        $user = User::factory()->create([
            'name' => 'Ichsan Dwi Nugraha',
            'email' => 'admin@ichsan.dev',
            'password' => Hash::make('P@ssw0rd!~'), 
        ]);

        $user->assignRole('super-admin');

        // 3. Portfolio & Content
        $this->call([
            ServiceSeeder::class,
            SettingSeeder::class,
            PortfolioSeeder::class,
            PageSeeder::class,
            MenuSeeder::class,
            BlogPostSeeder::class,
        ]);
        
        // 4. Default Settings (Optional)
    }
}
