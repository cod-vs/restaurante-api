<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'total' => 0,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
