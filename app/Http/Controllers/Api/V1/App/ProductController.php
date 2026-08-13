<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Requests\Api\V1\App\ProductFilterRequest;
use App\Http\Resources\Api\V1\App\ProductResource;
use App\Http\Swagger\ProductApiDocInterface;
use App\Services\ProductService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;

class ProductController extends BaseApiController implements ProductApiDocInterface
{
    public function __construct(
        protected ProductService $productService
    ) {}

    public function index(ProductFilterRequest $request): JsonResponse
    {
        try {
            $products = $this->productService->getProductList($request->validated());

            return response()->json([
                'success' => true,
                'data' => ProductResource::collection($products),
                'pagination' => [
                    'has_more' => $products->hasMorePages(),
                    'per_page' => $products->perPage(),
                ]
            ], 200);
        } catch (Exception $e) {
            Log::error('Product List Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch products.',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function categories(): JsonResponse
    {
        try {
            $categories = $this->productService->getCategories();
            return response()->json(['success' => true, 'data' => $categories], 200);
        } catch (Exception $e) {
            Log::error('Category Fetch Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch categories.'], 500);
        }
    }

    public function brands(): JsonResponse
    {
        try {
            $brands = $this->productService->getBrands();
            return response()->json(['success' => true, 'data' => $brands], 200);
        } catch (Exception $e) {
            Log::error('Brand Fetch Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch brands.'], 500);
        }
    }

    public function show(string $identifier): JsonResponse
    {
        try {
            $product = $this->productService->getProductDetails($identifier);
            return response()->json(['success' => true, 'data' => new ProductResource($product)], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Product not found.'], 404);
        } catch (Exception $e) {
            Log::error('Product Details Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch product details.'], 500);
        }
    }
}