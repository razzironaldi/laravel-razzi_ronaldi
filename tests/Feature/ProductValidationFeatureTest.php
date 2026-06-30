<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductValidationFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(User::factory()->create([
            'role' => 'admin',
        ]));
    }

    public function test_create_product_requires_valid_payload(): void
    {
        $response = $this->postJson('/api/products', [
            'name' => '',
            'sku' => '',
            'category' => '',
            'price' => -1,
            'stock' => -1,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'name',
                'sku',
                'category',
                'price',
                'stock',
            ]);
    }

    public function test_create_product_rejects_duplicate_sku(): void
    {
        Product::factory()->create([
            'sku' => 'PRD-8001',
        ]);

        $response = $this->postJson('/api/products', [
            'name' => 'Produk Duplikat',
            'sku' => 'PRD-8001',
            'category' => 'Elektronik',
            'price' => 100000,
            'stock' => 5,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'sku',
            ]);
    }
}
