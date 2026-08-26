<?php

namespace App\Http\Swagger;

use App\Http\Requests\Api\V1\App\ToggleFavoriteRequest;
use OpenApi\Attributes as OA;

interface FavoriteApiDocInterface
{
    #[OA\Get(
        path: "/api/v1/app/favorites",
        summary: "Get All Favorite Products",
        description: "Fetch list of all favorited/hearted products for the user.",
        tags: ["Favorite"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(response: 200, description: "Favorites retrieved successfully"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function index();

    #[OA\Post(
        path: "/api/v1/app/favorites/toggle",
        summary: "Toggle Product Favorite Status",
        description: "Heart/Unheart a product. If exists it removes, otherwise adds.",
        tags: ["Favorite"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["product_id"],
                properties: [
                    new OA\Property(property: "product_id", type: "integer", example: 10),
                    new OA\Property(property: "type", type: "string", example: 'variable or single'),
                    new OA\Property(property: "variation_id", type: "integer", example: 5, nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Product status updated in favorites"),
            new OA\Response(response: 422, description: "Validation Error")
        ]
    )]
    public function toggle(ToggleFavoriteRequest $request);
}
