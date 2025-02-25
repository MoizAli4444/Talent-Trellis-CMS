<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
{
    // Call the RolesAndPermissionsSeeder first to ensure roles exist
    $this->call([
        RolesAndPermissionsSeeder::class,
    ]);

    // Create a test user
    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    // Assign the 'user' role to the test user
    $user->assignRole('user');

    // Call other seeders
    $this->call([
        UserSeeder::class,
        PostSeeder::class,
        ArticleSeeder::class,
    ]);
}

}
