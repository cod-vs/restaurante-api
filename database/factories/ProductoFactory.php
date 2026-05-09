<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    public function definition(): array
    {
        $items = ['Burger', 'Pizza', 'Pasta', 'Tacos', 'Salad', 'Soup', 'Steak', 'Sushi', 'Sandwich', 'Wings'];

        return [
            'name'  => fake()->randomElement($items) . ' ' . fake()->word(),
            'price' => fake()->randomFloat(2, 5, 50),
        ];
    }
}
