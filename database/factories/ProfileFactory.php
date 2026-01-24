<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $handle = fake()->unique()->userName();

        return [
            'user_id' => User::factory(),
            'display_name' => fake()->name(),
            'handle' => $handle,
            'bio' => fake()->sentences(3, true),
            'avatar_url' => 'https://i.pravatar.cc/150?u='.fake()->unique()->uuid(),
            'cover_url' => 'https://dummyimage.com/1400x640/777/ECA749?text='.$handle,
        ];
    }
}
