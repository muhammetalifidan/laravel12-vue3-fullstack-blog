<?php

namespace Database\Factories;

use App\Enums\CommentStatusType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 3),
            'post_id' => fake()->numberBetween(1, 10),
            'body' => fake()->paragraph(2),
            'status' => fake()->randomElement(CommentStatusType::cases())->value,
        ];
    }
}
