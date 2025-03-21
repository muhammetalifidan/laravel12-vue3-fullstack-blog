<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'admin',
            'writer',
            'user',
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
    }
}
