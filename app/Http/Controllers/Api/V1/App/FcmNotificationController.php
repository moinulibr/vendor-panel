<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Requests\Api\V1\App\FcmRemoveTokenRequest;
use App\Http\Requests\Api\V1\App\FcmStoreTokenRequest;
use App\Http\Swagger\FcmNotificationApiDocInterface;
use App\Services\FcmNotificationService;
use Exception;

class FcmNotificationController extends BaseApiController implements FcmNotificationApiDocInterface
{
    protected FcmNotificationService $fcmService;

    public function __construct(FcmNotificationService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    public function storeFcmToken(FcmStoreTokenRequest $request)
    {
        try {
            $user = $request->user();
            $this->fcmService->storeOrUpdateToken($user, $request->validated());

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
            $this->fcmService->removeToken($user, $request->validated());

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