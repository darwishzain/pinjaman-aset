<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleAndUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define the roles
        $superAdminRole = Role::create(['name' => 'superadmin']);
        $adminRole      = Role::create(['name' => 'admin']);
        $managerRole    = Role::create(['name' => 'manager']);
        $staffRole      = Role::create(['name' => 'staff']);

        // 2. Create Default Users & Assign Corresponding Roles
        
        // Superadmin
        $superadmin = User::create([
            'name' => 'System Superadmin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
        ]);
        $superadmin->assignRole($superAdminRole);

        // Admin
        $admin = User::create([
            'name' => 'System Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole($adminRole);

        // Manager
        $manager = User::create([
            'name' => 'System Manager',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
        ]);
        $manager->assignRole($managerRole);

        // Staff
        $staff = User::create([
            'name' => 'System Staff',
            'email' => 'staff@example.com',
            'password' => Hash::make('password'),
        ]);
        $staff->assignRole($staffRole);
    }
}
