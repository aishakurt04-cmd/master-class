<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CraftFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'description' => fake()->paragraph(),
            'image' => null,
        ];
    }
}
