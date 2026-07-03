<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleItemFactory extends Factory
{
    protected $model = SaleItem::class;

    public function definition(): array
    {
        $qty = fake()->numberBetween(1, 20);
        $price = fake()->randomFloat(2, 10, 300);

        return [
            'sale_id' => Sale::factory(),
            'product_id' => Product::factory(),
            'quantity' => $qty,
            'unit_price' => $price,
            'subtotal' => $qty * $price,
        ];
    }
}
