<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Requests\Api\V1\App\FcmRemoveTokenRequest;
use App\Http\Requests\Api\V1\App\FcmStoreTokenRequest;
use App\Http\Resources\Api\V1\App\NotificationResource;
//use App\Http\Swagger\FcmNotificationApiDocInterface;
use App\Services\FcmNotificationService;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends BaseApiController
{
    protected NotificationService $notificationService;
    protected FcmNotificationService $fcmService;

    public function __construct(NotificationService $notificationService, FcmNotificationService $fcmService)
    {
        $this->notificationService = $notificationService;
        $this->fcmService = $fcmService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $channel = $request->get('channel', 'app');
        $notifications = $this->notificationService->getUserNotifications($user, $channel, $request->get('per_page', 15));
        $unreadCount = $this->notificationService->getUnreadCount($user, $channel);

        return $this->sendSuccessResponse([
            'unread_count'  => $unreadCount,
            'notifications' => NotificationResource::collection($notifications)->response()->getData(true)
        ], 'Notifications retrieved successfully.');
    }

    public function markAsRead(Request $request, $id)
    {
        $user = $request->user();
        $this->notificationService->markAsRead($user, (int) $id);

        return $this->sendSuccessResponse([], 'Notification marked as read.');
    }

    public function markAllAsRead(Request $request)
    {
        $user = $request->user();
        $this->notificationService->markAllAsRead($user);

        return $this->sendSuccessResponse([], 'All notifications marked as read.');
    }

    public function storeFcmToken(FcmStoreTokenRequest $request)
    {
        $user = $request->user();
        $this->fcmService->storeOrUpdateToken($user, $request->validated());

        return $this->sendSuccessResponse([], 'FCM token saved successfully.');
    }

    public function removeFcmToken(FcmRemoveTokenRequest $request)
    {
        $user = $request->user();
        $this->fcmService->removeToken($user, $request->validated());

        return $this->sendSuccessResponse([], 'FCM token(s) removed successfully.');
    }
}