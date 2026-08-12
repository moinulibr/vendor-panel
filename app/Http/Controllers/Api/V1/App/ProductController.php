<?php

namespace App\Http\Controllers\Api\V1\App;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\App\ProductFilterRequest;
use App\Http\Requests\Api\V1\App\StoreProductRequest;
use App\Http\Resources\Api\V1\App\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {}

    public function index(ProductFilterRequest $request): JsonResponse
    {
        $products = $this->productService->getProductList($request->validated());

        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($products),
            'pagination' => [
                'has_more' => $products->hasMorePages(),
                'per_page' => $products->perPage(),
            ]
        ]);
    }

    public function categories(): JsonResponse
    {
        $categories = $this->productService->getCategories();
        return response()->json(['success' => true, 'data' => $categories]);
    }

    public function brands(): JsonResponse
    {
        $brands = $this->productService->getBrands();
        return response()->json(['success' => true, 'data' => $brands]);
    }

    public function show(string $identifier): JsonResponse
    {
        $product = $this->productService->getProductDetails($identifier);
        return response()->json(['success' => true, 'data' => new ProductResource($product)]);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->saveProduct($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Product saved successfully',
            'data' => new ProductResource($product)
        ], 201);
    }
}