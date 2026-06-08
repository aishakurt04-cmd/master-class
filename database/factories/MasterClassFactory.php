<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MasterClassFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'date' => fake()->dateTimeBetween('now', '+1 month'),
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'max_participants' => fake()->numberBetween(5, 20),
            'current_participants' => 0,
            'price' => fake()->numberBetween(500, 5000),
        ];
    }
}