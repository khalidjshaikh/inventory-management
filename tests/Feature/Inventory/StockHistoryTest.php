<?php

namespace Tests\Feature\Inventory;

use App\Models\Product;
use App\Models\StockHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockHistoryTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_list_stock_history()
    {
        StockHistory::factory()->count(5)->create();

        $response = $this->actingAs($this->user)->get(route('stock-history.index'));

        $response->assertOk();
    }

    public function test_can_filter_by_product()
    {
        $product = Product::factory()->create();
        StockHistory::factory()->count(3)->create(['product_id' => $product->id]);

        $response = $this->actingAs($this->user)->get(route('stock-history.index', ['product_id' => $product->id]));

        $response->assertOk();
    }

    public function test_can_filter_by_type()
    {
        StockHistory::factory()->count(3)->create(['type' => 'purchase']);

        $response = $this->actingAs($this->user)->get(route('stock-history.index', ['type' => 'purchase']));

        $response->assertOk();
    }
}
