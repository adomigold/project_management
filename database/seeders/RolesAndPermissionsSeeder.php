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
        // Create Roles
        $admin = Role::create(['name' => 'Admin']);
        $manager = Role::create(['name' => 'Manager']);
        $user = Role::create(['name' => 'User']);

        // Create Permissions
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage projects']);
        Permission::create(['name' => 'manage tasks']);
        Permission::create(['name' => 'update tasks']);

        // Assign Permissions to Roles
        $admin->givePermissionTo(['manage users', 'manage projects', 'manage tasks', 'update tasks']);
        $manager->givePermissionTo(['manage projects', 'manage tasks']);
        $user->givePermissionTo(['update tasks']);
    }
}
