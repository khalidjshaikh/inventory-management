<?php

namespace Database\Factories;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseOrderFactory extends Factory
{
    protected $model = PurchaseOrder::class;

    public function definition(): array
    {
        return [
            'supplier_id' => Supplier::factory(),
            'order_date' => fake()->date(),
            'status' => 'pending',
            'total_amount' => 0,
        ];
    }
}
