<?php

namespace App\Http\Swagger;

use App\Http\Requests\Api\V1\App\ProductFilterRequest;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

interface ProductApiDocInterface
{
    #[OA\Get(
        path: "/api/v1/app/products",
        summary: "Get Filtered Product List",
        description: "Fetch paginated products with multi-category, brand, vendor, search, and price range filters.",
        tags: ["Product"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "q", in: "query", required: false, schema: new OA\Schema(type: "string"), description: "Search by Name or SKU"),
            new OA\Parameter(name: "category_ids", in: "query", required: false, schema: new OA\Schema(type: "string"), description: "Comma-separated category IDs e.g. 1,2,3"),
            //new OA\Parameter(name: "sub_category_ids", in: "query", required: false, schema: new OA\Schema(type: "string"), description: "Comma-separated sub-category IDs"),
            new OA\Parameter(name: "brand_id", in: "query", required: false, schema: new OA\Schema(type: "integer")),
            new OA\Parameter(name: "user_id", in: "query", required: false, schema: new OA\Schema(type: "integer"), description: "Vendor User ID"),
            //new OA\Parameter(name: "min_price", in: "query", required: false, schema: new OA\Schema(type: "number", format: "float")),
            //new OA\Parameter(name: "max_price", in: "query", required: false, schema: new OA\Schema(type: "number", format: "float")),
            new OA\Parameter(name: "sort_by", in: "query", required: false, schema: new OA\Schema(type: "string", enum: ["latest", "name_asc", "name_desc"])),
            new OA\Parameter(name: "per_page", in: "query", required: false, schema: new OA\Schema(type: "integer", default: 20))
        ],
        responses: [
            new OA\Response(response: 200, description: "Product list retrieved successfully"),
            new OA\Response(response: 401, description: "Unauthenticated"),
            new OA\Response(response: 500, description: "Server Error")
        ]
    )]
    public function index(ProductFilterRequest $request);

    #[OA\Get(
        path: "/api/v1/app/products/{identifier}?type={p_details_type}",
        summary: "Get Single Product Details",
        description: "Fetch product details by Product ID.",
        tags: ["Product"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "identifier", in: "path", required: true, schema: new OA\Schema(type: "string"), description: "Product ID"),
            new OA\Parameter(name: "type", in: "path", required: true, schema: new OA\Schema(type: "string"), description: "single or variable")
        ],
        responses: [
            new OA\Response(response: 200, description: "Product details retrieved"),
            new OA\Response(response: 404, description: "Product not found")
        ]
    )]
    public function show(Request $request, string|int $identifier);

    #[OA\Get(
        path: "/api/v1/app/check-stock-quantity/{identifier}?type={p_details_type}",
        summary: "Check Stock Quantity",
        description: "Fetch product qty by Product ID.",
        tags: ["Product"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "identifier", in: "path", required: true, schema: new OA\Schema(type: "string"), description: "Product ID"),
            new OA\Parameter(name: "type", in: "path", required: true, schema: new OA\Schema(type: "string"), description: "single or variable")
        ],
        responses: [
            new OA\Response(response: 200, description: "Product stock quantity retrieved"),
            new OA\Response(response: 404, description: "Product not found")
        ]
    )]
    public function checkStockQuantity(Request $request, string|int $identifier);

    #[OA\Get(
        path: "/api/v1/app/categories",
        summary: "Get Category Tree List",
        description: "Fetch parent categories along with their child subcategories.",
        tags: ["Product"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(response: 200, description: "Category list retrieved successfully"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function categories();

    #[OA\Get(
        path: "/api/v1/app/brands",
        summary: "Get Brand List",
        description: "Fetch all active brands.",
        tags: ["Product"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(response: 200, description: "Brand list retrieved successfully"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function brands();

}