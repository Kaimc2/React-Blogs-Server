<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Random\Randomizer;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("posts")->insert([
            "title"=> fake()->unique()->name,
            "thumbnail" => storage_path("placeholder.jpg"),
            "slug"=> fake()->unique()->slug,
            "body"=> fake()->paragraphs,
            "author_id"=> User::all()->random()->id,
        ]);
    }
}
