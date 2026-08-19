<?php
namespace App\Http\Swagger;

use App\Http\Requests\Api\V1\App\AddRetailerShippingAddressRequest;
use App\Http\Requests\Api\V1\App\ChangePasswordRequest;
use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\App\LoginRequest;
use App\Http\Requests\Api\V1\App\SendOtpRequest;
use App\Http\Requests\Api\V1\App\RegisterRequest;
use App\Http\Requests\Api\V1\App\ResetPasswordRequest;
use App\Http\Requests\Api\V1\App\UpdateProfilePictureRequest;
use App\Http\Requests\Api\V1\App\UpdateRetailerShippingAddressRequest;
use App\Http\Requests\Api\V1\App\VerifyOtpRequest;
use App\Http\Requests\Api\V1\App\UserFilterRequest;
use OpenApi\Attributes as OA;

interface AuthSwagger
{
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
                    new OA\Property(property: "purpose", type: "string", example: "login", enum: ["login", "register", "reset_password"]),
                    new OA\Property(property: "check_user", type: "string", example: "exist/new", enum: ["exist", "new"])
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
        path: "/api/v1/app/login",
        summary: "SR and Retailer Dual Login (Password & OTP)",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["mobile", "login_type"],
                properties: [
                    new OA\Property(property: "mobile", type: "string", example: "01700000001"),
                    new OA\Property(property: "login_type", type: "string", example: "password", enum: ["password", "otp"]),
                    new OA\Property(property: "password", type: "string", example: "12345678", nullable: true),
                    new OA\Property(property: "otp", type: "string", example: "1234", nullable: true)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Login Success"),
            new OA\Response(response: 401, description: "Invalid Credentials"),
            new OA\Response(response: 422, description: "Validation Error")
        ]
    )]
    public function login(LoginRequest $request);

    #[OA\Post(
        path: "/api/v1/app/register",
        summary: "SR and Retailer Registration",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "mobile", "otp", "user_type"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "John Doe"),
                    new OA\Property(property: "email", type: "string", example: "johndoe@example.com", nullable: true),
                    new OA\Property(property: "mobile", type: "string", example: "01700000001"),
                    //new OA\Property(property: "otp", type: "string", example: "1234"),
                    new OA\Property(property: "password", type: "string", example: "12345678", nullable: true),
                    new OA\Property(property: "access_type", type: "integer", example: "2", enum: ["2"]),
                    new OA\Property(property: "user_type", type: "integer", example: "4 or 5, 4 = SR; 5 = RETAILER", enum: ["4,5"]),
                    new OA\Property(property: "shop_name", type: "string", example: "Fresh Store", nullable: true),
                    new OA\Property(property: "address", type: "string", example: "Mirpur-10, Dhaka", nullable: true)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Registration Success"),
            new OA\Response(response: 422, description: "Validation Error / Invalid OTP")
        ]
    )]
    public function register(RegisterRequest $request);

    #[OA\Post(
        path: "/api/v1/app/reset-password",
        summary: "Reset Password (via OTP)",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["mobile", "password", "password_confirmation"],
                properties: [
                    new OA\Property(property: "mobile", type: "string", example: "01700000001"),
                    //new OA\Property(property: "reset_by", type: "string", example: "otp", enum: ["otp", "old_password"]),
                    //new OA\Property(property: "otp", type: "string", example: "1234", nullable: true),
                    //new OA\Property(property: "old_password", type: "string", example: "oldpass123", nullable: true),
                    new OA\Property(property: "password", type: "string", example: "newpass123"),
                    new OA\Property(property: "password_confirmation", type: "string", example: "newpass123")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Password reset Successfully"),
            new OA\Response(response: 422, description: "Validation Error")
        ]
    )]
    public function resetPassword(ResetPasswordRequest $request);


    #[OA\Post(
        path: "/api/v1/app/change-password",
        summary: "Change Password",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["current_password", "password", "password_confirmation"],
                properties: [
                    //new OA\Property(property: "mobile", type: "string", example: "01700000001"),
                    //new OA\Property(property: "reset_by", type: "string", example: "otp", enum: ["otp", "old_password"]),
                    //new OA\Property(property: "otp", type: "string", example: "1234", nullable: true),
                    new OA\Property(property: "current_password", type: "string", example: "oldpass123", nullable: true),
                    new OA\Property(property: "password", type: "string", example: "newpass123"),
                    new OA\Property(property: "password_confirmation", type: "string", example: "newpass123")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Password Changed Successfully"),
            new OA\Response(response: 422, description: "Validation Error")
        ]
    )]
    public function changePassword(ChangePasswordRequest $request);

    #[OA\Get(
        path: "/api/v1/app/profile",
        summary: "Get Authenticated User Profile",
        tags: ["Authentication"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(response: 200, description: "Profile fetched successfully"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function profile(Request $request);

    #[OA\Post(
        path: "/api/v1/app/update-profile",
        summary: "Update User Profile Information",
        description: "Updates authenticated user's profile and retailer details",
        tags: ["Authentication"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "mobile"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Md. Moinul Islam"),
                    new OA\Property(property: "email", type: "string", format: "email", example: "moinul@example.com", nullable: true),
                    new OA\Property(property: "mobile", type: "string", example: "01711112233"),
                    new OA\Property(property: "shop_name", type: "string", example: "PureOlaa Enterprise", nullable: true),
                    new OA\Property(property: "address", type: "string", example: "Mirpur-10, Dhaka", nullable: true),
                    new OA\Property(property: "trade_license", type: "string", example: "TRD-123456", nullable: true)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Profile updated successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Profile updated successfully"),
                        //new OA\Property(property: "data", ref: "#/components/schemas/UserResource")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validation Error"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]

    #[OA\Post(
        path: "/api/v1/app/logout",
        summary: "Logout Current Session",
        tags: ["Authentication"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(response: 200, description: "Logged out successfully")
        ]
    )]
    public function logout(Request $request);

    #[OA\Delete(
        path: "/api/v1/app/delete-account",
        summary: "Delete Authenticated User Account",
        tags: ["Authentication"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(response: 200, description: "Account deleted successfully"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function deleteAccount(Request $request);

    #[OA\Post(
        path: "/api/v1/app/verify-otp",
        summary: "Verify OTP Code Only",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["mobile", "otp", "purpose"],
                properties: [
                    new OA\Property(property: "mobile", type: "string", example: "01700000001"),
                    new OA\Property(property: "otp", type: "string", example: "1234"),
                    new OA\Property(property: "purpose", type: "string", example: "reset_password / register / login", enum: ["login", "register", "reset_password"])
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "OTP Verified Successfully"),
            new OA\Response(response: 422, description: "Invalid or Expired OTP")
        ]
    )]
    public function verifyOtp(VerifyOtpRequest $request);


    #[OA\Post(
        path: "/api/v1/app/update-profile-picture",
        summary: "Upload or Update Profile Picture",
        tags: ["Authentication"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    required: ["profile_picture"],
                    properties: [
                        new OA\Property(property: "profile_picture", type: "string", format: "binary")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Profile Picture Updated Successfully")
        ]
    )]
    public function profilePictureUpdate(UpdateProfilePictureRequest $request);

    #[OA\Post(
        path: "/api/v1/app/create-retailer-shipping-address",
        summary: "Add Shipping Address for Retailer",
        tags: ["Shipping Address Management"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["title", "address"],
                properties: [
                    new OA\Property(property: "retailer_id", type: "integer", example: 1, nullable: true),
                    new OA\Property(property: "title", type: "string", example: "Main Shop"),
                    new OA\Property(property: "contact_person", type: "string", example: "Mr. Rahim"),
                    new OA\Property(property: "contact_mobile", type: "string", example: "01700000000"),
                    new OA\Property(property: "address", type: "string", example: "Shop #12, Market Rd, Mirpur"),
                    new OA\Property(property: "division", type: "string", example: "Dhaka"),
                    new OA\Property(property: "district", type: "string", example: "Dhaka"),
                    new OA\Property(property: "area", type: "string", example: "Market Rd, Mirpur"),
                    new OA\Property(property: "upazila", type: "string", example: "Mirpur"),
                    new OA\Property(property: "division_id", type: "integer", example: "1"),
                    new OA\Property(property: "district_id", type: "integer", example: "2"),
                    new OA\Property(property: "upazila_id", type: "integer", example: "3"),
                    new OA\Property(property: "is_default", type: "boolean", example: true)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Shipping Address Created")
        ]
    )]
    public function createRetailerShippingAddress(AddRetailerShippingAddressRequest $request);

    #[OA\Get(
        path: "/api/v1/app/get-retailer-shipping-addresses/{retailerId}",
        summary: "Get Retailer Shipping Addresses By Retailer ID",
        tags: ["Shipping Address Management"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "retailerId",
                in: "path",
                required: true,
                description: "Target Retailer ID",
                schema: new OA\Schema(type: "integer", example: 3)
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Retailer Shipping Addresses fetched successfully"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function getRetailerShippingAddresses($retailerId);


    #[OA\Post(
        path: "/api/v1/app/update-retailer-shipping-address/{shippingAddressId}",
        summary: "Update Shipping Address for Retailer",
        tags: ["Shipping Address Management"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["title", "address"],
                properties: [
                    new OA\Property(property: "retailer_id", type: "integer", example: 1, nullable: true),
                    new OA\Property(property: "title", type: "string", example: "Main Shop"),
                    new OA\Property(property: "contact_person", type: "string", example: "Mr. Rahim"),
                    new OA\Property(property: "contact_mobile", type: "string", example: "01700000000"),
                    new OA\Property(property: "address", type: "string", example: "Shop #12, Market Rd, Mirpur"),
                    new OA\Property(property: "division", type: "string", example: "Dhaka"),
                    new OA\Property(property: "district", type: "string", example: "Dhaka"),
                    new OA\Property(property: "area", type: "string", example: "Market Rd, Mirpur"),
                    new OA\Property(property: "upazila", type: "string", example: "Mirpur"),
                    new OA\Property(property: "division_id", type: "integer", example: "1"),
                    new OA\Property(property: "district_id", type: "integer", example: "2"),
                    new OA\Property(property: "upazila_id", type: "integer", example: "3"),
                    new OA\Property(property: "is_default", type: "boolean", example: true)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Shipping Address Updated Successfully")
        ]
    )]
    public function updateRetailerShippingAddress(string|int $shippingAddressId, UpdateRetailerShippingAddressRequest $request);


    #[OA\Delete(
        path: "/api/v1/app/delete-retailer-shipping-address/{shippingAddressId}",
        summary: "Delete Retailer Shipping Address",
        description: "Delete a specific shipping address by ID",
        tags: ["Shipping Address Management"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "shippingAddressId",
                in: "path",
                required: true,
                description: "ID of the shipping address to delete",
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Shipping Address Deleted Successfully"),
            new OA\Response(response: 401, description: "Unauthenticated"),
            new OA\Response(response: 404, description: "Shipping Address Not Found")
        ]
    )]
    public function deleteRetailerShippingAddress(int $shippingAddressId, Request $request);


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
}