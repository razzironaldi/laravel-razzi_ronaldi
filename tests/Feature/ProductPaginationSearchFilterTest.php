<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductPaginationSearchFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(User::factory()->create([
            'role' => 'admin',
        ]));
    }

    public function test_product_list_has_pagination(): void
    {
        Product::factory()->count(5)->create();

        $response = $this->getJson('/api/products?per_page=2');

        $response->assertStatus(200)
            ->assertJsonPath('per_page', 2);

        $this->assertCount(2, $response->json('data'));
    }

    public function test_product_list_can_search_by_name(): void
    {
        Product::factory()->create([
            'name' => 'Keyboard Gaming',
            'sku' => 'PRD-7001',
            'category' => 'Elektronik',
        ]);

        Product::factory()->create([
            'name' => 'Pulpen Hitam',
            'sku' => 'PRD-7002',
            'category' => 'ATK',
        ]);

        $response = $this->getJson('/api/products?search=Keyboard');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'name' => 'Keyboard Gaming',
            ]);
    }

    public function test_product_list_can_filter_by_category(): void
    {
        Product::factory()->create([
            'name' => 'Mouse Wireless',
            'sku' => 'PRD-7003',
            'category' => 'Elektronik',
        ]);

        Product::factory()->create([
            'name' => 'Buku Catatan',
            'sku' => 'PRD-7004',
            'category' => 'ATK',
        ]);

        $response = $this->getJson('/api/products?category=ATK');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'category' => 'ATK',
            ]);
    }

    public function test_product_list_can_filter_by_status(): void
    {
        Product::factory()->create([
            'name' => 'Produk Aktif',
            'sku' => 'PRD-7005',
            'is_active' => true,
        ]);

        Product::factory()->create([
            'name' => 'Produk Nonaktif',
            'sku' => 'PRD-7006',
            'is_active' => false,
        ]);

        $response = $this->getJson('/api/products?is_active=0');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'name' => 'Produk Nonaktif',
            ]);
    }
}
