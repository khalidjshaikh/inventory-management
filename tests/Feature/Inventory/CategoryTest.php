<?php

namespace Tests\Feature\Inventory;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_list_categories()
    {
        Category::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get(route('categories.index'));

        $response->assertOk();
    }

    public function test_can_create_category()
    {
        $response = $this->actingAs($this->user)->post(route('categories.store'), [
            'name' => 'Electronics',
            'description' => 'Electronic items',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', ['name' => 'Electronics']);
    }

    public function test_can_show_category()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user)->get(route('categories.show', $category));

        $response->assertOk();
    }

    public function test_can_update_category()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user)->put(route('categories.update', $category), [
            'name' => 'Updated Name',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', ['name' => 'Updated Name']);
    }

    public function test_can_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('categories.destroy', $category));

        $response->assertRedirect();
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_requires_authentication()
    {
        $response = $this->get(route('categories.index'));
        $response->assertRedirect(route('login'));
    }
}
