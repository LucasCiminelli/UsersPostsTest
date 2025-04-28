<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::count() === 0) {
            User::factory(10)->create();
        }

        User::all()->each(function ($user) {
            Post::factory(5)->create([
                'user_id' => $user->id
            ]);
        });
    }
}
