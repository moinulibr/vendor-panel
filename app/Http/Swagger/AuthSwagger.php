<?php

namespace App\Http\Swagger;

use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\App\ResetPasswordRequest;
use App\Http\Requests\Api\V1\App\LoginRequest;
use App\Http\Requests\Api\V1\App\RegisterRequest;
use App\Http\Requests\Api\V1\App\SendOtpRequest;
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
                    new OA\Property(property: "mobile", type: "string", example: "01700000001"),
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

    #[OA\Post(
        path: "/api/v1/app/send-otp",
        summary: "Send OTP for Login, Register or Password Reset",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["mobile", "purpose"],
                properties: [
                    new OA\Property(property: "mobile", type: "string", example: "01700000001"),
                    new OA\Property(property: "purpose", type: "string", example: "login", enum: ["login", "register", "reset_password"])
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "OTP Sent Successfully"),
            new OA\Response(response: 422, description: "Validation Error")
        ]
    )]
    public function sendOtp(SendOtpRequest $request);

    #[OA\Post(
        path: "/api/v1/app/register",
        summary: "SR and Merchant Registration Endpoint",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "mobile", "otp", "user_type"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "John Doe"),
                    new OA\Property(property: "mobile", type: "string", example: "01700000000"),
                    new OA\Property(property: "otp", type: "string", example: "1234"),
                    new OA\Property(property: "password", type: "string", example: "12345678"),
                    new OA\Property(property: "user_type", type: "string", example: "retailer", enum: ["sr", "retailer"]),
                    new OA\Property(property: "shop_name", type: "string", example: "Fresh Super Store"),
                    new OA\Property(property: "address", type: "string", example: "Mirpur-10, Dhaka")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Registration Success"),
            new OA\Response(response: 422, description: "Validation Error / Invalid OTP")
        ]
    )]
    public function register(RegisterRequest $request);

    #[OA\Post(
        path: "/api/v1/app/reset-password",
        summary: "Reset Password using Mobile & OTP",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["mobile", "otp", "password", "password_confirmation"],
                properties: [
                    new OA\Property(property: "mobile", type: "string", example: "01700000000"),
                    new OA\Property(property: "otp", type: "string", example: "1234"),
                    new OA\Property(property: "password", type: "string", example: "newpassword123"),
                    new OA\Property(property: "password_confirmation", type: "string", example: "newpassword123")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Password Reset Success"),
            new OA\Response(response: 422, description: "Validation Error")
        ]
    )]
    public function resetPassword(ResetPasswordRequest $request);

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
    /*
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
    */
}
