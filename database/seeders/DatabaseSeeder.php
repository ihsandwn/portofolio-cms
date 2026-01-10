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
        $role = Role::create(['name' => 'super-admin']);
        
        // 2. User
        $user = User::factory()->create([
            'name' => 'Ichsan Dwi Nugraha',
            'email' => 'admin@ichsan.dev',
            'password' => Hash::make('password'), 
        ]);

        $user->assignRole($role);

        // 3. Portfolio & Content
        $this->call([
            PortfolioSeeder::class,
            // ServiceSeeder::class, // Can implement later
            // MenuSeeder::class,    // Can implement later
        ]);
        
        // 4. Default Settings (Optional)
    }
}
