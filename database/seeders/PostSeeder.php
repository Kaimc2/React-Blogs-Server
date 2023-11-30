<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("posts")->insert([
            "title"=> fake()->name,
            "slug"=> fake()->slug,
            "body"=> fake()->text,
            "user_id"=> 1,
        ]);
    }
}
