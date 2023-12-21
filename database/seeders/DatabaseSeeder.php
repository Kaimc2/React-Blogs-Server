<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(1)->create([
            'name' => 'Admin',
            'profile' => 'pf.png',
            'email' => 'admin@admin.com',
            'password' => bcrypt('adminPassword123'),
        ]);
        User::factory(4)->create();

        Category::create(["name" => "Horror"]);
        Category::create(["name" => "Technology"]);
        Category::create(["name" => "Travel"]);
        Category::create(["name" => "Food"]);

        Post::factory(10)->create();
    }
}
