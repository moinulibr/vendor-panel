<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Requests\Api\V1\App\LoginRequest;
use App\Http\Resources\Api\V1\App\UserResource;
use App\Services\AuthService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Exception;

class AuthController extends BaseApiController
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    #[OA\Post(
        path: "/api/v1/app/login",
        summary: "SR and Merchant Login Endpoint",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["login", "password"],
                properties: [
                    new OA\Property(property: "login", type: "string", example: "SR-101"),
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
    public function login(LoginRequest $request)
    {
        try {
            $result = $this->authService->authenticate($request->validated());

            return $this->jsonResponse(
                true,
                'User logged in successfully.',
                [
                    'token' => $result['token'],
                    'user'  => new UserResource($result['user'])
                ],
                200
            );
        } catch (Exception $e) {
            $statusCode = ($e->getCode() >= 400 && $e->getCode() < 600) ? $e->getCode() : 500;

            return $this->jsonResponse(
                false,
                $e->getMessage(),
                null,
                $statusCode
            );
        }
    }

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
    public function profile(Request $request)
    {
        return $this->jsonResponse(
            true,
            'Profile fetched successfully.',
            new UserResource($request->user()),
            200
        );
    }

    #[OA\Post(
        path: "/api/v1/app/logout",
        summary: "Logout User & Revoke Token",
        tags: ["Authentication"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(response: 200, description: "Logged out successfully")
        ]
    )]
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->jsonResponse(
            true,
            'Logged out successfully.',
            null,
            200
        );
    }
}
