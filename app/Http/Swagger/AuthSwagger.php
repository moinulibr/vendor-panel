<?php

namespace App\Http\Swagger;

use OpenApi\Attributes as OA;

interface AuthSwagger
{
    #[OA\Post(
        path: "/api/v1/app/login",
        summary: "SR and Merchant Login Endpoint",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["login", "password"],
                properties: [
                    new OA\Property(property: "login", type: "string", example: "01700000000"),
                    new OA\Property(property: "password", type: "string", example: "12345678")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Login Success"),
            new OA\Response(response: 401, description: "Invalid Credentials"),
            new OA\Response(response: 403, description: "Forbidden / Inactive Account")
        ]
    )]
    public function login(\App\Http\Requests\Api\V1\App\LoginRequest $request);
}
