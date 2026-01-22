<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Post;
use App\Models\Profile;
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
            'profile_id' => Profile::factory(),
            'parent_id' => null,
            'content' => fake()->realText(),
        ];
    }

    public function reply(Post $parentPost): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $parentPost->id,
            'content' => fake()->realText(120),
        ]);
    }
}
