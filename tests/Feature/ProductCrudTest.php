<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        Sanctum::actingAs($admin);
    }

    public function test_admin_can_create_product(): void
    {
        $this->actingAsAdmin();

        $response = $this->postJson('/api/products', [
            'name' => 'Laptop Lenovo',
            'sku' => 'PRD-5001',
            'category' => 'Elektronik',
            'price' => 7500000,
            'stock' => 12,
            'is_active' => true,
            'description' => 'Laptop untuk kerja.',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'Laptop Lenovo',
                'sku' => 'PRD-5001',
            ]);

        $this->assertDatabaseHas('products', [
            'sku' => 'PRD-5001',
        ]);
    }

    public function test_authenticated_user_can_view_product_list(): void
    {
        $this->actingAsAdmin();

        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_authenticated_user_can_view_product_detail(): void
    {
        $this->actingAsAdmin();

        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $product->id,
                'sku' => $product->sku,
            ]);
    }

    public function test_admin_can_update_product(): void
    {
        $this->actingAsAdmin();

        $product = Product::factory()->create([
            'stock' => 5,
        ]);

        $response = $this->putJson("/api/products/{$product->id}", [
            'stock' => 30,
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'stock' => 30,
            ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 30,
        ]);
    }

    public function test_admin_can_delete_product(): void
    {
        $this->actingAsAdmin();

        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }
}
