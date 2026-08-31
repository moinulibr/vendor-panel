<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Services\FcmNotificationService;
use Exception;

class TestingFeaturesController extends BaseApiController
{
    protected FcmNotificationService $notificationService;
    public function __construct(FcmNotificationService $notificationService){
        $this->notificationService = $notificationService;
    } 

    public function testingNotification(\Illuminate\Http\Request $request)
    {
        try {
            $user = $request->user();
            $this->notificationService->sendToUser(
                userId: $user->id,
                title: "New Order Placed!",
                body: "Your order #105 has been received successfully.",
                type: "order_created",
                targetChannel: "all",
                data: [
                    'order_id' => 105,
                    'click_action' => '/orders/105'
                ]
            );
            return response()->json([
                'success' => true,
                'message' => $user->id.' -  Send Notification successfully.',
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

}