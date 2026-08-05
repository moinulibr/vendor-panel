<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Requests\Api\V1\App\FcmRemoveTokenRequest;
use App\Http\Requests\Api\V1\App\FcmStoreTokenRequest;
use App\Http\Swagger\FcmNotificationApiDocInterface;
use App\Services\FcmNotificationService;

class FcmNotificationController extends BaseApiController implements FcmNotificationApiDocInterface
{
    protected FcmNotificationService $fcmService;

    public function __construct(FcmNotificationService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    public function storeFcmToken(FcmStoreTokenRequest $request)
    {
        $user = $request->user();
        $this->fcmService->storeOrUpdateToken($user, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'FCM token saved successfully.',
        ], 201);
    }

    public function removeFcmToken(FcmRemoveTokenRequest $request)
    {
        $user = $request->user();
        $this->fcmService->removeToken($user, $request->validated()['fcm_token']);

        return response()->json([
            'success' => true,
            'message' => 'FCM token removed successfully.',
        ], 200);
    }
}