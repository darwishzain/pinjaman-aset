<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //* Roles
        Role::firstOrCreate(['name' => 'superadmin']);
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'manager']);
        Role::firstOrCreate(['name' => 'staff']);
        //* User Permissions
        Permission::firstOrCreate(['name' => 'create:users']);
        Permission::firstOrCreate(['name' => 'view:users']);
        Permission::firstOrCreate(['name' => 'view-any:users']);
        Permission::firstOrCreate(['name' => 'update:user-roles']);
        //* Asset Permissions
        Permission::firstOrCreate(['name' => 'create:assets']);
        Permission::firstOrCreate(['name' => 'view:assets']);
        Permission::firstOrCreate(['name' => 'view-any:assets']);
        Permission::firstOrCreate(['name' => 'update:assets']);
        //* Request Permissions
        Permission::firstOrCreate(['name' => 'create:requests']);
        Permission::firstOrCreate(['name' => 'view:requests']);
        Permission::firstOrCreate(['name' => 'view-any:requests']);
        Permission::firstOrCreate(['name' => 'support:requests']);// Department manager
        Permission::firstOrCreate(['name' => 'approve:requests']);// Digital Department staffs
        //* Transaction Permissions
        Permission::firstOrCreate(['name' => 'create:transactions']);
        Permission::firstOrCreate(['name' => 'view:transactions']);
        Permission::firstOrCreate(['name' => 'view-any:transactions']);
    }
}
