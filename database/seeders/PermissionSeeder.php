<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $writerRole = Role::create(['name' => 'writer']);
        $userRole = Role::create(['name' => 'user']);

        $createPosts = Permission::create(['name' => 'create posts']);
        $editOwnPosts = Permission::create(['name' => 'edit own posts']);
        $deleteOwnPosts = Permission::create(['name' => 'delete own posts']);
        $createComments = Permission::create(['name' => 'create comments']);
        $editOwnComments = Permission::create(['name' => 'edit own comments']);
        $deleteOwnComments = Permission::create(['name' => 'delete own comments']);

        $adminPermissions = Permission::all();
        $writerPermissions = [$createPosts, $editOwnPosts, $deleteOwnPosts, $createComments, $editOwnComments, $deleteOwnComments];
        $userPermissions = [$createComments, $editOwnComments, $deleteOwnComments];

        $adminRole->givePermissionTo($adminPermissions);
        $writerRole->givePermissionTo($writerPermissions);
        $userRole->givePermissionTo($userPermissions);
    }
}
