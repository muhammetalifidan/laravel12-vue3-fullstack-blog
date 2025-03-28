<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            UserSeeder::class,
            PostSeeder::class,
            CategorySeeder::class,
            CategoryPostSeeder::class,
            CommentSeeder::class,
        ]);
    }
}
