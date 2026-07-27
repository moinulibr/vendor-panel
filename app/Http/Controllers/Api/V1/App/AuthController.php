<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Requests\Api\V1\App\LoginRequest;
use App\Http\Resources\Api\V1\App\UserResource;
use App\Http\Swagger\AuthSwagger;
use App\Services\AuthService;
use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\App\ResetPasswordRequest;
use App\Http\Requests\Api\V1\App\RegisterRequest;
use App\Http\Requests\Api\V1\App\SendOtpRequest;
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
                data: config('app.env') === 'local' ? ['debug_code' => $code] : null,
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
    /*
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

    public function sendOtp(SendOtpRequest $request)
    {
        $result = $this->authService->sendOtp($request->mobile, $request->purpose);

        return response()->json([
            'status'     => true,
            'message'    => 'OTP সফলভাবে পাঠানো হয়েছে।',
            'debug_code' => config('app.env') === 'local' ? $result['code'] : null,
        ]);
    }

    public function register(RegisterRequest $request)
    {
        if (!$this->authService->verifyOtp($request->mobile, $request->otp, 'register')) {
            return response()->json(['status' => false, 'message' => 'ওটিপি মেয়াদোত্তীর্ণ বা ভুল।'], 422);
        }

        $user  = $this->authService->registerUser($request->validated());
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'রেজিস্ট্রেশন সফল হয়েছে।',
            'token'   => $token,
            'user'    => $user->load('retailer'),
        ], 201);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        if (!$this->authService->verifyOtp($request->mobile, $request->otp, 'reset_password')) {
            return response()->json(['status' => false, 'message' => 'ওটিপি মেয়াদোত্তীর্ণ বা ভুল।'], 422);
        }

        $this->authService->resetPassword($request->mobile, $request->password);

        return response()->json(['status' => true, 'message' => 'পাসওয়ার্ড সফলভাবে পরিবর্তন করা হয়েছে।']);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['status' => false, 'message' => 'বর্তমান পাসওয়ার্ডটি ভুল।'], 422);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return response()->json(['status' => true, 'message' => 'পাসওয়ার্ড সফলভাবে আপডেট করা হয়েছে।']);
    }
    */
    public function deleteAccount(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        $user->delete();

        return response()->json(['status' => true, 'message' => 'অ্যাকাউন্ট সফলভাবে মুছে ফেলা হয়েছে।']);
    }
   
}
