<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'sku' => strtoupper(fake()->bothify('PRD-####')),
            'category' => fake()->randomElement(['Elektronik', 'Aksesoris', 'ATK']),
            'price' => fake()->numberBetween(10000, 500000),
            'stock' => fake()->numberBetween(1, 100),
            'is_active' => true,
            'description' => fake()->sentence(),
        ];
    }
}
