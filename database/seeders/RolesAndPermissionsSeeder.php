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
        // Define permissions
        $permissions = [
            'create post',
            'edit post',
            'delete post',
            'create article',
            'edit article',
            'delete article'
        ];
    
        // Create or update permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    
        // Create 'user' role if it doesn't exist
        $userRole = Role::firstOrCreate(['name' => 'user']);
    
        // Assign all defined permissions to 'user' role
        $userRole->syncPermissions($permissions);
    }
    
}
