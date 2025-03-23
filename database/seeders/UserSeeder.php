<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->createQuietly([
            'email' => 'user@example.com',
            'password' => 'password',
        ])->assignRole('user');

        User::factory()->createQuietly([
            'email' => 'writer@example.com',
            'password' => 'password',
        ])->assignRole('writer');

        User::factory()->createQuietly([
            'email' => 'admin@example.com',
            'password' => 'password',
        ])->assignRole('admin');
    }
}
