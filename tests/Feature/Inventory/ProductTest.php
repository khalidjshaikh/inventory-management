<?php

namespace Tests\Feature\Inventory;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_list_products()
    {
        Product::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get(route('products.index'));

        $response->assertOk();
    }

    public function test_can_create_product()
    {
        $category = Category::factory()->create();
        $supplier = Supplier::factory()->create();

        $response = $this->actingAs($this->user)->post(route('products.store'), [
            'name' => 'Laptop',
            'sku' => 'LPT-001',
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'cost_price' => 500.00,
            'selling_price' => 750.00,
            'stock_quantity' => 10,
            'low_stock_threshold' => 5,
            'unit' => 'pcs',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', ['sku' => 'LPT-001']);
    }

    public function test_can_show_product()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user)->get(route('products.show', $product));

        $response->assertOk();
    }

    public function test_can_update_product()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user)->put(route('products.update', $product), [
            'name' => 'Updated Laptop',
            'sku' => $product->sku,
            'cost_price' => 500.00,
            'selling_price' => 750.00,
            'stock_quantity' => 10,
            'low_stock_threshold' => 5,
            'unit' => 'pcs',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', ['name' => 'Updated Laptop']);
    }

    public function test_can_delete_product()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('products.destroy', $product));

        $response->assertRedirect();
        $this->assertSoftDeleted($product);
    }

    public function test_can_view_barcode()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user)->get(route('products.barcode', $product));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'image/png');
    }

    public function test_product_auto_generates_barcode()
    {
        $product = Product::factory()->create(['barcode' => null]);

        $this->assertNotNull($product->barcode);
        $this->assertStringStartsWith('BAR', $product->barcode);
    }

    public function test_sku_must_be_unique()
    {
        Product::factory()->create(['sku' => 'UNIQUE-SKU']);

        $response = $this->actingAs($this->user)->post(route('products.store'), [
            'name' => 'Duplicate',
            'sku' => 'UNIQUE-SKU',
            'cost_price' => 10,
            'selling_price' => 20,
            'stock_quantity' => 1,
            'low_stock_threshold' => 1,
            'unit' => 'pcs',
        ]);

        $response->assertSessionHasErrors('sku');
    }
}
