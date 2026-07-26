<?php

namespace App\Http\Swagger;

use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\App\LoginRequest;
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
                required: ["mobile", "password"],
                properties: [
                    new OA\Property(property: "mobile", type: "string", example: "01700000000"),
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
    public function login(LoginRequest $request);

    #[OA\Get(
        path: "/api/v1/app/profile",
        summary: "Get Authenticated Profile Details",
        tags: ["Authentication"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(response: 200, description: "Profile retrieved successfully"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function profile(Request $request);

    #[OA\Post(
        path: "/api/v1/app/logout",
        summary: "Logout User & Revoke Token",
        tags: ["Authentication"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(response: 200, description: "Logged out successfully")
        ]
    )]
    public function logout(Request $request);
}
