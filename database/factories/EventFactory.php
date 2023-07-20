<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create();
        return [
            //
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'image' => fake()->image(),
            'type' => fake()->word(),
            'capacity' => fake()->numberBetween(100,700),
            'available_seats' => fake()->numberBetween(100,600),
            'price' => fake()->numberBetween(100,900),
            // 'short_link' => fake()->url(), // 'hsttps://www.google.com
            'user_id' => $user->id,
            'location' => fake()->address(),
            'start_date' => fake()->date(),
            'end_date' => fake()->date(),
            'time' => fake()->time(),
        
        ];
    }
}
