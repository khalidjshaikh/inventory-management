<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseOrderItemFactory extends Factory
{
    protected $model = PurchaseOrderItem::class;

    public function definition(): array
    {
        $qty = fake()->numberBetween(1, 50);
        $cost = fake()->randomFloat(2, 5, 200);

        return [
            'purchase_order_id' => PurchaseOrder::factory(),
            'product_id' => Product::factory(),
            'quantity_ordered' => $qty,
            'quantity_received' => 0,
            'unit_cost' => $cost,
            'subtotal' => $qty * $cost,
        ];
    }
}
