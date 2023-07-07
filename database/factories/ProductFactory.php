<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=> fake()->name(),
            'user_id'=> random_int(1,2),
            'description'=>fake()->text(),
            'category_id'=>random_int(1,2),
            'inventory_id'=>random_int(1,2),
            'price' => random_int(10,20),
            'discount_id'=>random_int(1,2),
            'image'=>fake()->image()
        ];
    }
}
