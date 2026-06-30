<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ProductRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new ProductRepository;
    }

    public function test_repository_can_create_product(): void
    {
        $product = $this->repository->create([
            'name' => 'Mouse Wireless',
            'sku' => 'PRD-1001',
            'category' => 'Elektronik',
            'price' => 150000,
            'stock' => 20,
            'is_active' => true,
            'description' => 'Mouse untuk kerja harian.',
        ]);

        $this->assertDatabaseHas('products', [
            'sku' => 'PRD-1001',
            'name' => 'Mouse Wireless',
        ]);

        $this->assertSame('Mouse Wireless', $product->name);
    }

    public function test_repository_can_search_filter_and_paginate_products(): void
    {
        Product::factory()->create([
            'name' => 'Keyboard Gaming',
            'sku' => 'PRD-2001',
            'category' => 'Elektronik',
            'is_active' => true,
        ]);

        Product::factory()->create([
            'name' => 'Pulpen Biru',
            'sku' => 'PRD-2002',
            'category' => 'ATK',
            'is_active' => true,
        ]);

        $result = $this->repository->paginate([
            'search' => 'Keyboard',
            'category' => 'Elektronik',
            'is_active' => true,
        ], 5);

        $this->assertSame(1, $result->total());
        $this->assertSame('Keyboard Gaming', $result->items()[0]->name);
    }

    public function test_repository_can_update_product(): void
    {
        $product = Product::factory()->create([
            'stock' => 5,
        ]);

        $updated = $this->repository->update($product, [
            'stock' => 15,
        ]);

        $this->assertSame(15, $updated->stock);
    }

    public function test_repository_can_delete_product(): void
    {
        $product = Product::factory()->create();

        $result = $this->repository->delete($product);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }
}
