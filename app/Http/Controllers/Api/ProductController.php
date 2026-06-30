<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly ProductService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Product::class);

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $perPage = (int) ($validated['per_page'] ?? 10);

        return response()->json($this->service->paginate($validated, $perPage));
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $this->authorize('create', Product::class);

        $product = $this->service->create($request->validated());

        return response()->json([
            'message' => 'Produk berhasil dibuat.',
            'data' => $product,
        ], 201);
    }

    public function show(Product $product): JsonResponse
    {
        $this->authorize('view', $product);

        return response()->json([
            'data' => $product,
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $this->authorize('update', $product);

        $product = $this->service->update($product, $request->validated());

        return response()->json([
            'message' => 'Produk berhasil diperbarui.',
            'data' => $product,
        ]);
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->authorize('delete', $product);

        $this->service->delete($product);

        return response()->json(null, 204);
    }
}
