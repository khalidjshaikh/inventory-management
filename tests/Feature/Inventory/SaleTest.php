<?php

namespace Tests\Feature\Inventory;

use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_list_sales()
    {
        Sale::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get(route('sales.index'));

        $response->assertOk();
    }

    public function test_can_create_sale()
    {
        $product = Product::factory()->create(['stock_quantity' => 50]);

        $response = $this->actingAs($this->user)->post(route('sales.store'), [
            'sale_date' => now()->format('Y-m-d'),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'unit_price' => 75.00,
                ],
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('sales', ['total_amount' => 150.00]);
        $this->assertDatabaseHas('sale_items', ['product_id' => $product->id]);
    }

    public function test_can_show_sale()
    {
        $sale = Sale::factory()->create();

        $response = $this->actingAs($this->user)->get(route('sales.show', $sale));

        $response->assertOk();
    }

    public function test_sale_auto_generates_number()
    {
        $sale = Sale::factory()->create(['sale_number' => null]);

        $this->assertNotNull($sale->sale_number);
        $this->assertStringStartsWith('SAL-', $sale->sale_number);
    }

    public function test_sale_reduces_stock()
    {
        $product = Product::factory()->create(['stock_quantity' => 50]);

        $this->actingAs($this->user)->post(route('sales.store'), [
            'sale_date' => now()->format('Y-m-d'),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 5,
                    'unit_price' => 75.00,
                ],
            ],
        ]);

        $this->assertEquals(45, $product->fresh()->stock_quantity);
    }

    public function test_can_delete_sale()
    {
        $sale = Sale::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('sales.destroy', $sale));

        $response->assertRedirect();
        $this->assertDatabaseMissing('sales', ['id' => $sale->id]);
    }
}
