<?php

namespace App\Services;

use App\Models\Notification as DbNotification;
use App\Models\UserDeviceToken;
use Exception;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class FcmNotificationService
{
    protected Messaging $messaging;

    public function __construct(
        Messaging $messaging
    ) {
        $this->messaging = $messaging;
    }


    /**
     * Send Notification to a specific User
     */
    public function sendToUser(
        ?int $userId,
        string $title,
        string $body,
        string $type = 'system',
        string $targetChannel = 'app',
        array $data = []
    ): ?DbNotification {

        // 1. Save data to database as in-app notification history
        $dbNotification = DbNotification::create([
            'user_id'        => $userId,
            'title'          => $title,
            'body'           => $body,
            'type'           => $type,
            'target_channel' => $targetChannel,
            'data'           => $data,
            'read_at'        => null,
        ]);

        // 2. Send FCM push notification if target channel is app or all
        if (in_array($targetChannel, ['app', 'all']) && $userId) {
            $this->dispatchFcmToUserDevices($userId, $title, $body, $data);
        }

        // 3. Web Socket/Reverb event broadcast
        if (in_array($targetChannel, ['web_admin', 'all'])) {
            // broadcast(new \App\Events\AdminNotificationEvent($dbNotification))->toOthers();
        }

        return $dbNotification;
    }

    /**
     * Dispatch FCM Push Notification to all active user devices
     */
    protected function dispatchFcmToUserDevices(int $userId, string $title, string $body, array $data = []): void
    {
        $tokens = UserDeviceToken::where('user_id', $userId)
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->toArray();

        if (empty($tokens)) {
            return;
        }

        // Convert all values in data payload to string (FCM V1 Requirement)
        $formattedData = array_map(fn($val) => is_array($val) ? json_encode($val) : (string)$val, $data);

        $message = CloudMessage::new()
            ->withNotification(FirebaseNotification::create($title, $body))
            ->withData($formattedData);

        try {
            $this->messaging->sendMulticast($message, $tokens);
        } catch (\Throwable $e) {
            Log::error("FCM Multicast Error for User ID {$userId}: " . $e->getMessage());
        }
    }

    /**
     * Send Push Notification to Multiple FCM Tokens (Bulk / Campaign)
     */
    public function sendToMultipleUsers(array $fcmTokens, string $title, string $body, array $data = []): array
    {
        $validTokens = array_values(array_filter($fcmTokens));

        if (empty($validTokens)) {
            return ['success' => 0, 'failure' => 0];
        }

        $formattedData = array_map(fn($val) => is_array($val) ? json_encode($val) : (string)$val, $data);

        // Fixed: Use FirebaseNotification alias instead of App\Models\Notification
        $message = CloudMessage::new()
            ->withNotification(FirebaseNotification::create($title, $body))
            ->withData($formattedData);

        try {
            $report = $this->messaging->sendMulticast($message, $validTokens);

            return [
                'success' => $report->successes()->count(),
                'failure' => $report->failures()->count(),
            ];
        } catch (\Throwable $e) {
            Log::error("FCM Multicast Error: " . $e->getMessage());
            return ['success' => 0, 'failure' => 0];
        }
    }
}
