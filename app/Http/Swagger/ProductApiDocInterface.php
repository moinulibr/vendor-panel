<?php

namespace App\Http\Swagger;

use App\Http\Requests\Api\V1\App\ProductFilterRequest;
use App\Http\Requests\Api\V1\App\UserFilterRequest;
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
            new OA\Parameter(name: "sub_category_ids", in: "query", required: false, schema: new OA\Schema(type: "string"), description: "Comma-separated sub-category IDs"),
            new OA\Parameter(name: "brand_id", in: "query", required: false, schema: new OA\Schema(type: "integer")),
            new OA\Parameter(name: "user_id", in: "query", required: false, schema: new OA\Schema(type: "integer"), description: "Vendor User ID"),
            new OA\Parameter(name: "min_price", in: "query", required: false, schema: new OA\Schema(type: "number", format: "float")),
            new OA\Parameter(name: "max_price", in: "query", required: false, schema: new OA\Schema(type: "number", format: "float")),
            new OA\Parameter(name: "sort_by", in: "query", required: false, schema: new OA\Schema(type: "string", enum: ["latest", "price_low", "price_high", "name_asc", "name_desc"])),
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

    #[OA\Get(
        path: "/api/v1/app/vendors",
        summary: "Get Vendor List",
        description: "Fetch list of active vendors for filters and dropdowns.",
        tags: ["User & Vendor"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "q", in: "query", required: false, schema: new OA\Schema(type: "string"), description: "Search by vendor name, email or mobile"),
            new OA\Parameter(name: "status", in: "query", required: false, schema: new OA\Schema(type: "string", enum: ["1", "0", "active", "inactive"])),
            new OA\Parameter(name: "sort", in: "query", required: false, schema: new OA\Schema(type: "string", enum: ["asc", "desc", "latest"])),
            new OA\Parameter(name: "per_page", in: "query", required: false, schema: new OA\Schema(type: "integer", default: 20))
        ],
        responses: [
            new OA\Response(response: 200, description: "Vendor list retrieved successfully"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function vendors(UserFilterRequest $request);

    #[OA\Get(
        path: "/api/v1/app/retailers",
        summary: "Get Retailer (Shop Owner) List",
        description: "Fetch list of active retailers along with shop details (SR selection mode).",
        tags: ["User & Vendor"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "q", in: "query", required: false, schema: new OA\Schema(type: "string"), description: "Search by name, email or mobile"),
            new OA\Parameter(name: "status", in: "query", required: false, schema: new OA\Schema(type: "string", enum: ["1", "0", "active", "inactive"])),
            new OA\Parameter(name: "sort", in: "query", required: false, schema: new OA\Schema(type: "string", enum: ["asc", "desc", "latest"])),
            new OA\Parameter(name: "per_page", in: "query", required: false, schema: new OA\Schema(type: "integer", default: 20))
        ],
        responses: [
            new OA\Response(response: 200, description: "Retailer list retrieved successfully"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function retailers(UserFilterRequest $request);

    #[OA\Get(
        path: "/api/v1/app/products/{identifier}",
        summary: "Get Single Product Details",
        description: "Fetch product details by Product ID or Slug.",
        tags: ["Product"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "identifier", in: "path", required: true, schema: new OA\Schema(type: "string"), description: "Product ID or Slug")
        ],
        responses: [
            new OA\Response(response: 200, description: "Product details retrieved"),
            new OA\Response(response: 404, description: "Product not found")
        ]
    )]
    public function show(string $identifier);

}