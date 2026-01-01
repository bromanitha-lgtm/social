<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create regular users
        $users = User::factory(10)->create();

        // Create posts
        Post::factory(50)->create([
            'user_id' => $users->random()->id,
        ]);

        // Create some posts for admin
        Post::factory(5)->create([
            'user_id' => $admin->id,
        ]);

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin credentials: admin@example.com / password');
    }
}