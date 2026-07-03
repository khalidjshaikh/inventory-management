<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'sku' => 'SKU-' . fake()->unique()->numerify('#####'),
            'category_id' => Category::factory(),
            'supplier_id' => Supplier::factory(),
            'cost_price' => fake()->randomFloat(2, 10, 500),
            'selling_price' => fake()->randomFloat(2, 20, 800),
            'stock_quantity' => fake()->numberBetween(0, 100),
            'low_stock_threshold' => fake()->numberBetween(5, 20),
            'unit' => 'pcs',
            'is_active' => true,
        ];
    }
}
