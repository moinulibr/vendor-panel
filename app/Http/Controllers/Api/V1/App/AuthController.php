<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Requests\Api\V1\App\AddRetailerShippingAddressRequest;
use App\Http\Requests\Api\V1\App\LoginRequest;
use App\Http\Resources\Api\V1\App\UserResource;
use App\Http\Swagger\AuthSwagger;
use App\Services\AuthService;
use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\App\ResetPasswordRequest;
use App\Http\Requests\Api\V1\App\RegisterRequest;
use App\Http\Requests\Api\V1\App\SendOtpRequest;
use App\Http\Requests\Api\V1\App\UpdateProfilePictureRequest;
use App\Http\Requests\Api\V1\App\VerifyOtpRequest;
use App\Http\Resources\Api\V1\App\RetailerShippingAddressResource;
use Exception;

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
                message: 'ওটিপি সফলভাবে ভেরিফাই হয়েছে।',
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

        return response()->json(['status' => true, 'message' => 'অ্যাকাউন্ট সফলভাবে মুছে ফেলা হয়েছে।']);
    }


    public function profilePictureUpdate(UpdateProfilePictureRequest $request)
    {
        try {
            $url = $this->authService->updateProfilePicture($request->user(), $request->file('profile_picture'));

            return $this->jsonResponse(true, 'প্রোফাইল পিকচার আপডেট করা হয়েছে।', ['profile_picture_url' => $url], 200);
        } catch (Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function getRetailerShippingAddresses($retailer_id){
        try {
            if (!$retailer_id) {
                throw new Exception("রিটেইলারের আইডি পাওয়া যায়নি।", 422);
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
            $retailerId = $request->retailer_id ?? optional($user->retailer)->id;

            if (!$retailerId) {
                throw new Exception("রিটেইলারের আইডি পাওয়া যায়নি।", 422);
            }

            $address = $this->authService->addRetailerShippingAddress($retailerId, $request->validated());
            return $this->jsonResponse(
                success: true,
                message: 'এড্রেস সফলভাবে যুক্ত করা হয়েছে।',
                data: [
                    'shipping_address' => new RetailerShippingAddressResource($address),
                ],
                statusCode: 201
            );
        } catch (Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
   
}
