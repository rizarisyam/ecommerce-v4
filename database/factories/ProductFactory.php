<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'desc' => $this->faker->paragraph(),
            'quantity' => $this->faker->randomDigitNot(0),
            'SKU' => $this->faker->text(),
            'price' => $this->faker->randomFloat(2, 100000, 1000000),
            'category_id' => rand(1, Category::count())
        ];
    }
}
