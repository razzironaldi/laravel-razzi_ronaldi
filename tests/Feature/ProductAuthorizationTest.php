<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_product_list(): void
    {
        $response = $this->getJson('/api/products');

        $response->assertStatus(401);
    }

    public function test_regular_user_cannot_create_product(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/products', [
            'name' => 'Produk User',
            'sku' => 'PRD-6001',
            'category' => 'ATK',
            'price' => 10000,
            'stock' => 5,
        ]);

        $response->assertStatus(403);
    }

    public function test_regular_user_cannot_update_product(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $product = Product::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->putJson("/api/products/{$product->id}", [
            'stock' => 99,
        ]);

        $response->assertStatus(403);
    }

    public function test_regular_user_cannot_delete_product(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $product = Product::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(403);
    }
}
