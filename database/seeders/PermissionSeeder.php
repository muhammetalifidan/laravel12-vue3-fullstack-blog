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
        $editPosts = Permission::create(['name' => 'edit posts']);
        $deletePosts = Permission::create(['name' => 'delete posts']);

        $adminPermissions = Permission::all();
        $writerPermissions = [$createPosts, $editPosts, $deletePosts];
        $userPermissions = [];

        $adminRole->givePermissionTo($adminPermissions);
        $writerRole->givePermissionTo($writerPermissions);
        $userRole->givePermissionTo($userPermissions);
    }
}
