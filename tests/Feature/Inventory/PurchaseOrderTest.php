<?php

namespace Tests\Feature\Inventory;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseOrderTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_list_purchase_orders()
    {
        PurchaseOrder::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get(route('purchase-orders.index'));

        $response->assertOk();
    }

    public function test_can_create_purchase_order()
    {
        $supplier = Supplier::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user)->post(route('purchase-orders.store'), [
            'supplier_id' => $supplier->id,
            'order_date' => now()->format('Y-m-d'),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity_ordered' => 10,
                    'unit_cost' => 25.00,
                ],
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('purchase_orders', ['supplier_id' => $supplier->id]);
        $this->assertDatabaseHas('purchase_order_items', ['product_id' => $product->id]);
    }

    public function test_can_show_purchase_order()
    {
        $purchaseOrder = PurchaseOrder::factory()->create();

        $response = $this->actingAs($this->user)->get(route('purchase-orders.show', $purchaseOrder));

        $response->assertOk();
    }

    public function test_purchase_order_auto_generates_number()
    {
        $supplier = Supplier::factory()->create();

        $po = PurchaseOrder::factory()->create(['order_number' => null, 'supplier_id' => $supplier->id]);

        $this->assertNotNull($po->order_number);
        $this->assertStringStartsWith('PO-', $po->order_number);
    }

    public function test_can_receive_items()
    {
        $supplier = Supplier::factory()->create();
        $product = Product::factory()->create(['stock_quantity' => 0]);

        $po = PurchaseOrder::factory()->create([
            'supplier_id' => $supplier->id,
            'status' => 'pending',
        ]);

        $item = $po->items()->create([
            'product_id' => $product->id,
            'quantity_ordered' => 10,
            'quantity_received' => 0,
            'unit_cost' => 25.00,
            'subtotal' => 250.00,
        ]);

        $response = $this->actingAs($this->user)->post(route('purchase-orders.receive', $po), [
            'items' => [
                ['id' => $item->id, 'quantity_received' => 10],
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('purchase_orders', [
            'id' => $po->id,
            'status' => 'received',
        ]);
    }
}
