<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserDeviceToken;
use App\Repositories\Notification\Interface\NotificationRepositoryInterface;
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
        )
    {
        $this->notificationRepo = $notificationRepo;
        $this->messaging = $messaging;
    }

    public function getUserNotifications(User $user, string $channel = 'app', int $perPage = 15)
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
     * 🚀 Master Notification Dispatcher (Supports DB + FCM Push)
     *
     * @param int $userId Recipient User ID
     * @param string $title Title
     * @param string $body Message body
     * @param string $type e.g. order_created, order_cancelled
     * @param string $targetChannel 'app', 'web_admin', or 'all'
     * @param array $payloadData Custom JSON payload
     */
    public function sendNotification(
        int $userId,
        string $title,
        string $body,
        string $type = 'system',
        string $targetChannel = 'all',
        array $payloadData = []
    ): void {
        // ১. ডাটাবেজে রেকর্ড সেভ করা
        $this->notificationRepo->createNotification([
            'user_id'        => $userId,
            'title'          => $title,
            'body'           => $body,
            'type'           => $type,
            'target_channel' => $targetChannel,
            'data'           => $payloadData,
        ]);

        // ২. ডেসটিনেশন ফিল্টার করে একটিভ টোকেন বের করা
        $tokenQuery = UserDeviceToken::where('user_id', $userId);

        if ($targetChannel === 'app') {
            $tokenQuery->whereIn('device_type', ['android', 'ios']);
        } elseif ($targetChannel === 'web_admin') {
            $tokenQuery->where('device_type', 'web');
        }

        $tokens = $tokenQuery->pluck('fcm_token')->toArray();

        // ৩. Firebase HTTP v1 API দিয়ে মাল্টিকাস্ট পুশ সেশন এক্সিকিউট করা
        if (!empty($tokens)) {
            $this->sendFcmPush($tokens, $title, $body, $payloadData);
        }
    }

    /**
     * Kreait Firebase SDK FCM Push Engine
     */
    private function sendFcmPush(array $tokens, string $title, string $body, array $payloadData): void
    {
        try {
            $fcmNotification = FcmNotification::create($title, $body);

            // Payload data string এ কনভার্ট করা (FCM requirements)
            $formattedData = array_map('strval', $payloadData);

            $message = CloudMessage::new()
                ->withNotification($fcmNotification)
                ->withData($formattedData);

            // Multiple device tokens এ একসাথে পাঠাতে sendMulticast
            $report = $this->messaging->sendMulticast($message, $tokens);

            Log::info("FCM Sent Success: {$report->successes()->count()}, Failures: {$report->failures()->count()}");
        } catch (\Exception $e) {
            Log::error("FCM Push Dispatch Error: " . $e->getMessage());
        }
    }
}