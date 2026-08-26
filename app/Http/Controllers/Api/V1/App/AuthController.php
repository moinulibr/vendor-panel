<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Requests\Api\V1\App\AddRetailerShippingAddressRequest;
use App\Http\Requests\Api\V1\App\ChangePasswordRequest;
use App\Http\Requests\Api\V1\App\LoginRequest;
use App\Http\Resources\Api\V1\App\UserResource;
use App\Http\Requests\Api\V1\App\UserFilterRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Swagger\AuthSwagger;
use App\Services\AuthService;
use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\App\ResetPasswordRequest;
use App\Http\Requests\Api\V1\App\RegisterRequest;
use App\Http\Requests\Api\V1\App\SendOtpRequest;
use App\Http\Requests\Api\V1\App\UpdateProfilePictureRequest;
use App\Http\Requests\Api\V1\App\UpdateProfileRequest;
use App\Http\Requests\Api\V1\App\UpdateRetailerShippingAddressRequest;
use App\Http\Requests\Api\V1\App\VerifyOtpRequest;
use App\Http\Resources\Api\V1\App\RetailerShippingAddressResource;
use App\Utils\UserType;
use Exception;
use Illuminate\Support\Facades\Log;

class AuthController extends BaseApiController implements AuthSwagger
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function sendOtp(SendOtpRequest $request)
    {
        try {
            $code = $this->authService->sendOtp($request->validated());

            return $this->jsonResponse(
                success: true,
                message: 'OTP sent successfully.',
                data: config('app.env') === 'local' ? ['otp' => $code] : null,
                statusCode: 200
            );
        } catch (Exception $e) {
            return $this->jsonResponse(
                success: false,
                message: $e->getMessage(),
                statusCode: 500
            );
        }
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

    public function register(RegisterRequest $request)
    {
        try {
            $result = $this->authService->registerUser($request->validated());

            return $this->jsonResponse(
                success: true,
                message: 'Registration completed successfully.',
                data: [
                    'token' => $result['token'],
                    'user'  => new UserResource($result['user']),
                ],
                statusCode: 201
            );
        } catch (Exception $e) {
            $statusCode = ($e->getCode() >= 400 && $e->getCode() < 600) ? $e->getCode() : 500;

            return $this->jsonResponse(
                success: false,
                message: $e->getMessage(),
                statusCode: $statusCode
            );
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $this->authService->resetPassword($request->validated());

            return $this->jsonResponse(
                success: true,
                message: 'Password reset successfully.',
                statusCode: 200
            );
        } catch (Exception $e) {
            $statusCode = ($e->getCode() >= 400 && $e->getCode() < 600) ? $e->getCode() : 500;

            return $this->jsonResponse(
                success: false,
                message: $e->getMessage(),
                statusCode: $statusCode
            );
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $user = $request->user();

            $this->authService->changePassword($user, $request->validated());

            return $this->jsonResponse(
                success: true,
                message: 'Password changed successfully.',
                statusCode: 200
            );
        } catch (Exception $e) {
            $statusCode = ($e->getCode() >= 400 && $e->getCode() < 600) ? $e->getCode() : 500;

            return $this->jsonResponse(
                success: false,
                message: $e->getMessage(),
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
                'user' => new UserResource($request->user()->load('retailer')),
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

    public function verifyOtp(VerifyOtpRequest $request)
    {
        try {
            $this->authService->verifyOtpOnly($request->validated());

            return $this->jsonResponse(
                success: true,
                message: 'OTP verified successfully।',
                data: null,
                statusCode: 200
            );
        } catch (Exception $e) {
            $code = ($e->getCode() >= 400 && $e->getCode() < 600) ? $e->getCode() : 500;
            return $this->jsonResponse(false, $e->getMessage(), null, $code);
        }
    }

    public function deleteAccount(Request $request)
    {
        $this->authService->deleteAccount($request->user());

        return response()->json(['success' => true, 'message' => 'Your account has been deleted।']);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $updatedUser = $this->authService->updateProfile($user, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data'    => new UserResource($updatedUser)
        ], 200);
    }

    public function profilePictureUpdate(UpdateProfilePictureRequest $request)
    {
        try {
            $url = $this->authService->profilePictureUpdate($request->user(), $request->file('profile_picture'));

            return $this->jsonResponse(true, 'Profile picture updated।', ['profile_picture_url' => $url], 200);
        } catch (Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function getRetailerShippingAddresses($retailer_id){
        try {
            if (!$retailer_id) {
                throw new Exception("Retailer id is required।", 422);
            }

            $address = $this->authService->getRetailerShippingAddress($retailer_id);

            return $this->jsonResponse(
                success: true,
                message: 'Retailer Shipping Addresses fetched successfully.',
                data: [
                    'shipping_addresses' => RetailerShippingAddressResource::collection($address),
                ],
                statusCode: 200
            );
        } catch (Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function createRetailerShippingAddress(AddRetailerShippingAddressRequest $request)
    {
        try {
            $user = $request->user();
            // Check if Retailer or SR
            if($user->user_type == UserType::RETAILER){
                $retailerId = $user->id;
            }else{
                $retailerId = $request->retailer_id;
            }

            if (!$retailerId) {
                throw new Exception("Retailer id is required।", 422);
            }

            $address = $this->authService->addRetailerShippingAddress($retailerId, $request->validated());
            return $this->jsonResponse(
                success: true,
                message: 'Shipping Address added successfully।',
                data: [
                    'shipping_address' => new RetailerShippingAddressResource($address),
                ],
                statusCode: 201
            );
        } catch (Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function updateRetailerShippingAddress(string|int $shippingAddressId, UpdateRetailerShippingAddressRequest $request)
    {
        try {
            $user = $request->user();
            // Check if Retailer or SR
            if ($user->user_type == UserType::RETAILER) {
                $retailerId = $user->id;
            } else {
                $retailerId = $request->retailer_id;
            }
    
            $address = $this->authService->updateRetailerShippingAddress($retailerId, $shippingAddressId, $request->validated());
            return $this->jsonResponse(
                success: true,
                message: 'Shipping Address updated successfully.',
                data: [
                    'shipping_address' => new RetailerShippingAddressResource($address),
                ],
                statusCode: 201
            );
        } catch (Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
    public function deleteRetailerShippingAddress(int $shippingAddressId,Request $request)
    {
        $this->authService->deleteRetailerShippingAddress($shippingAddressId, $request->user()->id);

        return response()->json(['status' => true, 'message' => 'Shipping Address Deleted Successfully!']);
    }


    public function vendors(UserFilterRequest $request): JsonResponse
    {
        try {
            $vendors = $this->authService->getVendorList($request->validated());
            return response()->json([
                'success' => true,
                'data' => UserResource::collection($vendors),
                'pagination' => [
                    'has_more' => $vendors->hasMorePages(),
                    'per_page' => $vendors->perPage(),
                ]
            ], 200);
        } catch (Exception $e) {
            Log::error('Vendor List Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch vendors.'], 500);
        }
    }

    // retailers filter
    public function retailers(UserFilterRequest $request): JsonResponse
    {
        try {
            $retailers = $this->authService->getRetailerList($request->validated());
            return response()->json([
                'success' => true,
                'data' => UserResource::collection($retailers),
                'pagination' => [
                    'has_more' => $retailers->hasMorePages(),
                    'per_page' => $retailers->perPage(),
                ]
            ], 200);
        } catch (Exception $e) {
            Log::error('Retailer List Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch retailers.'], 500);
        }
    }
}
