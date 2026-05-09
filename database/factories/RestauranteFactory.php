<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RestauranteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'    => fake()->company() . ' Restaurant',
            'address' => fake()->streetAddress(),
        ];
    }
}
