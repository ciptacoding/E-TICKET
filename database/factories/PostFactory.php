<?php

namespace Database\Factories;

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
            'title' => fake()->sentence(20),
            'user_id' => 1,
            'excerpt' => fake()->sentence(20),
            'body' => fake()->paragraph(5),
            'date_post' => fake()->dateTimeThisMonth(),
        ];
    }
}
