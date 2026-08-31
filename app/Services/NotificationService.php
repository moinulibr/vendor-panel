<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\UserDeviceToken;
use App\Repositories\Notification\Interface\NotificationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FcmNotification;

class NotificationService
{
    protected NotificationRepositoryInterface $notificationRepo;
    protected Messaging $messaging;

    public function __construct(
        NotificationRepositoryInterface $notificationRepo,
        Messaging $messaging
    ) {
        $this->notificationRepo = $notificationRepo;
        $this->messaging = $messaging;
    }

    public function getUserNotifications(User $user, string $channel = 'app', int $perPage = 15): LengthAwarePaginator
    {
        return $this->notificationRepo->getUserNotifications($user, $channel, $perPage);
    }

    public function getUnreadCount(User $user, string $channel = 'app'): int
    {
        return $this->notificationRepo->getUnreadCount($user, $channel);
    }

    public function markAsRead(User $user, int $notificationId): bool
    {
        return $this->notificationRepo->markAsRead($user, $notificationId);
    }

    public function markAllAsRead(User $user): bool
    {
        return $this->notificationRepo->markAllAsRead($user);
    }

    /**
     * Master Notification Dispatcher (DB Store + FCM Push Engine)
     */
    public function sendNotification(
        int $userId,
        string $title,
        string $body,
        string $type = 'system',
        string $targetChannel = 'all',
        array $payloadData = []
    ): Notification {
        // 1. Save in-app notification to Database
        $notification = $this->notificationRepo->createNotification([
            'user_id'        => $userId,
            'title'          => $title,
            'body'           => $body,
            'type'           => $type,
            'target_channel' => $targetChannel,
            'data'           => $payloadData,
        ]);

        // 2. Fetch Active FCM Tokens based on Channel Filter
        $tokenQuery = UserDeviceToken::where('user_id', $userId)
            ->whereNotNull('fcm_token');

        if ($targetChannel === 'app') {
            $tokenQuery->whereIn('device_type', ['android', 'ios']);
        } elseif ($targetChannel === 'web_admin') {
            $tokenQuery->where('device_type', 'web');
        }

        $tokens = array_filter($tokenQuery->pluck('fcm_token')->toArray());

        // 3. Dispatch Push Notification via FCM
        if (!empty($tokens)) {
            $this->sendFcmPush($tokens, $title, $body, $payloadData);
        }

        return $notification;
    }

    /**
     * FCM Multicast Sender
     */
    private function sendFcmPush(array $tokens, string $title, string $body, array $payloadData): void
    {
        try {
            $fcmNotification = FcmNotification::create($title, $body);

            // Safe String Casting for Nested Objects in FCM Data Payload
            $formattedData = array_map(function ($value) {
                return is_array($value) ? json_encode($value) : (string) $value;
            }, $payloadData);

            $message = CloudMessage::new()
                ->withNotification($fcmNotification)
                ->withData($formattedData);

            $report = $this->messaging->sendMulticast($message, $tokens);

            Log::info("FCM Dispatch Success: {$report->successes()->count()}, Failures: {$report->failures()->count()}");
        } catch (\Throwable $e) {
            Log::error("FCM Push Engine Error: " . $e->getMessage());
        }
    }
}
