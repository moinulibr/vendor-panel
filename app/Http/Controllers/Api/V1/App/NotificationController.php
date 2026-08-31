<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Resources\Api\V1\App\NotificationResource;
use App\Http\Swagger\NotificationApiDocInterface;
use App\Services\NotificationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends BaseApiController implements NotificationApiDocInterface
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get paginated user notifications
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $channel = $request->get('channel', 'app');
            $perPage = (int) $request->get('per_page', 15);

            /** @var \Illuminate\Pagination\LengthAwarePaginator $notifications */
            $notifications = $this->notificationService->getUserNotifications($user, $channel, $perPage);
            $unreadCount = $this->notificationService->getUnreadCount($user, $channel);

            // CHANGED: Custom Standard Response matching your provided sample
            return response()->json([
                'success' => true,
                'message' => 'Notifications retrieved successfully.',
                'data'    => [
                    'unread_count'  => $unreadCount,
                    'notifications' => NotificationResource::collection($notifications->items()),
                ],
                'pagination' => [
                    'total'        => $notifications->total(),
                    'count'        => $notifications->count(),
                    'per_page'     => $notifications->perPage(),
                    'current_page' => $notifications->currentPage(),
                    'total_pages'  => $notifications->lastPage(),
                    'has_more'     => $notifications->hasMorePages(),
                ]
            ], 200);
        } catch (Exception $e) {
            Log::error('Fetch Notifications Error: ' . $e->getMessage());

            // CHANGED: Standard Error Response
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch notifications.',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Mark single notification as read
     */
    public function markAsRead(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();
            $this->notificationService->markAsRead($user, (int) $id);

            // CHANGED: Standard Success Response
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read successfully.',
                'data'    => null
            ], 200);
        } catch (Exception $e) {
            Log::error('Mark Notification Read Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notification as read.',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $this->notificationService->markAllAsRead($user);

            // CHANGED: Standard Success Response
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read successfully.',
                'data'    => null
            ], 200);
        } catch (Exception $e) {
            Log::error('Mark All Notifications Read Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to mark all notifications as read.',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
