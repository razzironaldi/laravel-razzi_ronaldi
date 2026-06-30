<?php

namespace Tests\Unit;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ProductValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_product_validation_accepts_valid_data(): void
    {
        $request = new StoreProductRequest;

        $validator = Validator::make([
            'name' => 'Monitor LED',
            'sku' => 'PRD-4001',
            'category' => 'Elektronik',
            'price' => 1200000,
            'stock' => 8,
            'is_active' => true,
        ], $request->rules());

        $this->assertFalse($validator->fails());
    }

    public function test_store_product_validation_rejects_invalid_data(): void
    {
        $request = new StoreProductRequest;

        $validator = Validator::make([
            'name' => '',
            'sku' => '',
            'category' => '',
            'price' => -100,
            'stock' => -5,
        ], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
        $this->assertArrayHasKey('price', $validator->errors()->toArray());
        $this->assertArrayHasKey('stock', $validator->errors()->toArray());
    }

    public function test_update_product_validation_rejects_duplicate_sku(): void
    {
        Product::factory()->create([
            'sku' => 'PRD-4002',
        ]);

        $request = new UpdateProductRequest;

        $validator = Validator::make([
            'sku' => 'PRD-4002',
        ], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('sku', $validator->errors()->toArray());
    }
}
