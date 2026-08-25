<?php

namespace App\Http\Swagger;

use App\Http\Requests\Api\V1\App\AddToCartRequest;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

interface CartApiDocInterface
{
    #[OA\Get(
        path: "/api/v1/app/cart",
        summary: "Get User Cart Items & Summary",
        description: "Fetch all active cart items and calculations for the authenticated user.",
        tags: ["Cart"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(response: 200, description: "Cart fetched successfully"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function index();

    #[OA\Post(
        path: "/api/v1/app/cart",
        summary: "Add Item to Cart",
        description: "Add a product or product variation to the user's cart.",
        tags: ["Cart"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["product_id", "quantity"],
                properties: [
                    new OA\Property(property: "product_id", type: "integer", example: 10),
                    new OA\Property(property: "variation_id", type: "integer", example: 5, nullable: true),
                    new OA\Property(property: "quantity", type: "integer", example: 1)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Item added to cart successfully"),
            new OA\Response(response: 422, description: "Validation Error"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function store(AddToCartRequest $request);

    #[OA\Put(
        path: "/api/v1/app/cart/{id}",
        summary: "Update Cart Item Quantity",
        description: "Update quantity of a specific item in the cart.",
        tags: ["Cart"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["quantity"],
                properties: [
                    new OA\Property(property: "quantity", type: "integer", example: 3)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Cart item updated successfully"),
            new OA\Response(response: 404, description: "Item not found")
        ]
    )]
    public function update(Request $request, int $id);

    #[OA\Delete(
        path: "/api/v1/app/cart/{id}",
        summary: "Remove Single Item from Cart",
        description: "Delete a cart item by its ID.",
        tags: ["Cart"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Item removed from cart successfully")
        ]
    )]
    public function destroy(int $id);

    #[OA\Delete(
        path: "/api/v1/app/cart/clear",
        summary: "Clear Entire Cart",
        description: "Remove all items from the current user's cart.",
        tags: ["Cart"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(response: 200, description: "Cart cleared successfully")
        ]
    )]
    public function clear();
}
