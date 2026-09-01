<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Requests\Api\V1\App\FcmRemoveTokenRequest;
use App\Http\Requests\Api\V1\App\FcmStoreTokenRequest;
use App\Http\Swagger\FcmNotificationApiDocInterface;
use App\Services\UserDeviceAndFcmTokenService;
use Exception;

class UserDeviceAndFcmTokenController extends BaseApiController implements FcmNotificationApiDocInterface
{
    protected UserDeviceAndFcmTokenService $userDeviceFcmTokenService;

    public function __construct(UserDeviceAndFcmTokenService $userDeviceFcmTokenService)
    {
        $this->userDeviceFcmTokenService = $userDeviceFcmTokenService;
    }

    public function storeFcmToken(FcmStoreTokenRequest $request)
    {
        try {
            $user = $request->user();
            $this->userDeviceFcmTokenService->storeOrUpdateToken($user, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'FCM token saved successfully.',
            ], 201);
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

    public function removeFcmToken(FcmRemoveTokenRequest $request)
    {
        try {
            $user = $request->user();
            $this->userDeviceFcmTokenService->removeToken($user, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'FCM token removed successfully.',
            ], 200);
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
}