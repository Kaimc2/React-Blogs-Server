<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "title" => fake()->word,
            "thumbnail" => "placeholder.jpg",
            "slug" => fake()->unique()->slug,
            "body" => fake()->paragraph,
            "category_id" => Category::all()->random()->id,
            "author_id" => User::all()->random()->id,
        ];
    }
}
