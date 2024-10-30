<?php

namespace Database\Seeders;

use App\Models\Todo;
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
        // Seed users
        $users = User::factory()->count(3)->create();

        // Seed posts for each user
        foreach ($users as $user) {
            Todo::factory()->count(3)->create([
                'user_id' => $user->id, // Associate each post with a user
            ]);
        }
    }
}
