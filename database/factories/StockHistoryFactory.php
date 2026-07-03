<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\StockHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockHistoryFactory extends Factory
{
    protected $model = StockHistory::class;

    public function definition(): array
    {
        $types = ['purchase', 'sale', 'adjustment', 'return'];

        return [
            'product_id' => Product::factory(),
            'type' => fake()->randomElement($types),
            'quantity_change' => fake()->randomElement([fake()->numberBetween(1, 50), -fake()->numberBetween(1, 20)]),
            'notes' => fake()->sentence(),
        ];
    }
}
