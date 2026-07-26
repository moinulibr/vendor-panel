<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Requests\Api\V1\App\LoginRequest;
use App\Http\Resources\Api\V1\App\UserResource;
use App\Http\Swagger\AuthSwagger;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Exception;

class AuthController extends BaseApiController implements AuthSwagger
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        try {
            $result = $this->authService->authenticate($request->validated());

            return $this->jsonResponse(
                success: true,
                message: 'User logged in successfully.',
                data: [
                    'token' => $result['token'],
                    'user'  => new UserResource($result['user']),
                    // ভবিষ্যতে বাড়তি ডেটা এখানে খুব সহজেই দিতে পারবেন
                ],
                statusCode: 200
            );
        } catch (Exception $e) {
            $statusCode = ($e->getCode() >= 400 && $e->getCode() < 600) ? $e->getCode() : 500;

            return $this->jsonResponse(
                success: false,
                message: $e->getMessage(),
                data: null,
                statusCode: $statusCode
            );
        }
    }

    public function profile(Request $request)
    {
        return $this->jsonResponse(
            success: true,
            message: 'Profile fetched successfully.',
            data: [
                'user' => new UserResource($request->user()),
                // এখানে পরবর্তীতে অতিরিক্ত ইউজার ডাটা (যেমন: permissions, settings) যুক্ত করতে পারবেন
            ],
            statusCode: 200
        );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->jsonResponse(
            success: true,
            message: 'Logged out successfully.',
            data: null,
            statusCode: 200
        );
    }
}
