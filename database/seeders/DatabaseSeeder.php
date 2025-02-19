<?php

namespace Database\Seeders;

use App\Models\Post;
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
        User::factory()->create([
            'name' => 'User1',
            'email' => 'user1@example.com',
        ]);
        User::factory()->create([
            'name' => 'User2',
            'email' => 'user2@example.com',
        ]);
        User::factory()->create([
            'name' => 'User3',
            'email' => 'user3@example.com',
        ]);

        Post::factory(30)->create();
    }
}
