<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Requests\Api\V1\App\ImageSearchRequest;
use App\Http\Requests\Api\V1\App\ProductFilterRequest;
use App\Http\Resources\Api\V1\App\BrandResource;
use App\Http\Resources\Api\V1\App\CategoryResource;
use App\Http\Resources\Api\V1\App\ProductDetailsResource;
use App\Http\Resources\Api\V1\App\ProductResource;
use App\Http\Swagger\ProductApiDocInterface;
use App\Services\ProductService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\Request;

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

    public function show(Request $request, string|int $identifier): JsonResponse
    {
        $locationId = $request->input('location_id');
        $type       = $request->input('type'); // 'single' or 'variable'

        try {
            $product = $this->productService->getProductDetails($identifier, $locationId, $type);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data'    => new ProductDetailsResource($product)
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.'
            ], 404);
        } catch (Exception $e) {
            Log::error('Product Details Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch product details.'
            ], 500);
        }
    }

    public function checkStockQuantity(Request $request, string|int $identifier): JsonResponse
    {
        $locationId = $request->input('location_id');
        $type       = $request->input('type'); // 'single' or 'variable'
        return response()->json([
            'success' => true,
            'data'    => [
                'available_qty' => 50000,
                'in_stock'      => true
            ]
        ], 200);
    }

    public function searchByImage(ImageSearchRequest $request): JsonResponse
    {
        try {
            // TODO: Future Microservice Integration
            // $image = $request->file('image');
            // $matchedProductIds = $this->visionService->getMatchedProductIds($image);
            // Temporary Mocking: Fetching standard products list using existing service logic
            $products = $this->productService->getProductList($request->validated());

            return response()->json([
                'success'    => true,
                'message'    => 'Image search completed successfully.',
                'data'       => ProductResource::collection($products),
                'pagination' => [
                    'has_more' => $products->hasMorePages(),
                    'per_page' => $products->perPage(),
                ]
            ], 200);
        } catch (Exception $e) {
            Log::error('Image Search Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to process image search.'
            ], 500);
        }
    }

    public function categories(): JsonResponse
    {
        try {
            $categories = $this->productService->getCategories();
            
            return response()->json(['success' => true, 'data' => CategoryResource::collection($categories)], 200);
        } catch (Exception $e) {
            Log::error('Category Fetch Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch categories.'], 500);
        }
    }

    public function brands(): JsonResponse
    {
        try {
            $brands = $this->productService->getBrands();
            return response()->json(['success' => true, 'data' => BrandResource::collection($brands)], 200);
        } catch (Exception $e) {
            Log::error('Brand Fetch Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch brands.'], 500);
        }
    }


}