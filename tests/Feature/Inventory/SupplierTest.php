<?php

namespace Tests\Feature\Inventory;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_list_suppliers()
    {
        Supplier::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get(route('suppliers.index'));

        $response->assertOk();
    }

    public function test_can_create_supplier()
    {
        $response = $this->actingAs($this->user)->post(route('suppliers.store'), [
            'name' => 'Tech Corp',
            'email' => 'contact@techcorp.com',
            'phone' => '1234567890',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('suppliers', ['name' => 'Tech Corp']);
    }

    public function test_can_show_supplier()
    {
        $supplier = Supplier::factory()->create();

        $response = $this->actingAs($this->user)->get(route('suppliers.show', $supplier));

        $response->assertOk();
    }

    public function test_can_update_supplier()
    {
        $supplier = Supplier::factory()->create();

        $response = $this->actingAs($this->user)->put(route('suppliers.update', $supplier), [
            'name' => 'Updated Corp',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('suppliers', ['name' => 'Updated Corp']);
    }

    public function test_can_delete_supplier()
    {
        $supplier = Supplier::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('suppliers.destroy', $supplier));

        $response->assertRedirect();
        $this->assertDatabaseMissing('suppliers', ['id' => $supplier->id]);
    }
}
