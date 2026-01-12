<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogPost>
 */
class BlogPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'slug' => $this->faker->slug(),
            'excerpt' => $this->faker->paragraph(),
            'content_blocks' => [
                ['type' => 'text', 'data' => '<p>' . $this->faker->paragraph() . '</p>'],
            ],
            'seo_meta' => [
                'title' => $this->faker->sentence(),
                'description' => $this->faker->sentence(),
            ],
            'author_id' => \App\Models\User::factory(),
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'category_id' => $this->faker->numberBetween(1, 5),
        ];
    }
}
