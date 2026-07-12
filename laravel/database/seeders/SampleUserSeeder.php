<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class SampleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $userSuperAdmin = User::firstOrCreate([
            'name' => 'Sample Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
        ]);
        $userSuperAdmin->assignRole('superadmin');
        $userAdmin = User::firstOrCreate([
            'name' => 'Sample Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $userAdmin->assignRole('admin');
        $userManager = User::firstOrCreate([
            'name' => 'Sample Manager',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
        ]);
        $userManager->assignRole('manager');
        $userStaff = User::firstOrCreate([
            'name' => 'Sample Staff',
            'email' => 'staff@example.com',
            'password' => Hash::make('password'),
        ]);
        $userStaff->assignRole('staff');
    }
}
