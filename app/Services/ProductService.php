<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class ProductService
{
    public function __construct(
        private readonly ProductRepository $repository
    ) {}

    public function paginate(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->repository->paginate($filters, $perPage);
    }

    public function create(array $data): Product
    {
        $this->validateBusinessRules($data);

        return $this->repository->create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $this->validateBusinessRules($data);

        return $this->repository->update($product, $data);
    }

    public function delete(Product $product): bool
    {
        return $this->repository->delete($product);
    }

    private function validateBusinessRules(array $data): void
    {
        if (array_key_exists('price', $data) && (int) $data['price'] < 0) {
            throw ValidationException::withMessages([
                'price' => 'Harga produk tidak boleh negatif.',
            ]);
        }

        if (array_key_exists('stock', $data) && (int) $data['stock'] < 0) {
            throw ValidationException::withMessages([
                'stock' => 'Stok produk tidak boleh negatif.',
            ]);
        }
    }
}
