<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $event = Event::factory()->create();
        $user = User::factory()->create();

        return [
            'amount' => fake()->numberBetween(100, 900),
            'reference'=> uniqid(),
            'event_id' =>  $event->id,
            'user_id' => $user->id,
            'ticket_type' => fake()->word(),
            'quantity'=> fake()->numberBetween(1, 10),
        ];
    }
}
