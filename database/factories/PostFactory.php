<?php

namespace Database\Factories;

use App\Enums\PostStatusType;
use App\Models\Post;
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
            'title' => fake()->sentence(10),
            'slug' => fake()->slug(3),
            'content' => fake()->paragraph(10),
            'published_at' => fake()->dateTimeBetween('-1 week', '+1 week'),
            'status' => fake()->randomElement(PostStatusType::cases())->value,
            'user_id' => fake()->numberBetween(1, 3),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Post $post) {
            $post->addMedia(public_path('sample-images/default-post-image.jpg'))->preservingOriginal()->toMediaCollection();
        });
    }
}
