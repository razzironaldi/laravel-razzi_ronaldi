<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Illuminate\Validation\ValidationException;
use Mockery;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_service_can_create_product(): void
    {
        $repository = Mockery::mock(ProductRepository::class);

        $data = [
            'name' => 'Laptop Lenovo',
            'sku' => 'PRD-3001',
            'category' => 'Elektronik',
            'price' => 7500000,
            'stock' => 10,
            'is_active' => true,
        ];

        $repository->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn(new Product($data));

        $service = new ProductService($repository);

        $product = $service->create($data);

        $this->assertSame('Laptop Lenovo', $product->name);
    }

    public function test_service_rejects_negative_stock(): void
    {
        $repository = Mockery::mock(ProductRepository::class);
        $service = new ProductService($repository);

        $this->expectException(ValidationException::class);

        $service->create([
            'name' => 'Produk Error',
            'sku' => 'PRD-3002',
            'category' => 'Elektronik',
            'price' => 50000,
            'stock' => -1,
        ]);
    }

    public function test_service_rejects_negative_price(): void
    {
        $repository = Mockery::mock(ProductRepository::class);
        $service = new ProductService($repository);

        $this->expectException(ValidationException::class);

        $service->create([
            'name' => 'Produk Error',
            'sku' => 'PRD-3003',
            'category' => 'Elektronik',
            'price' => -1000,
            'stock' => 10,
        ]);
    }
}
