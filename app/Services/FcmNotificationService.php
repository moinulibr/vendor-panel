<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\UserDeviceToken;
use App\Repositories\DeviceToken\Interface\UserDeviceTokenRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class FcmNotificationService
{
    protected UserDeviceTokenRepositoryInterface $tokenRepo;
    protected Messaging $messaging;
    public function __construct(
        UserDeviceTokenRepositoryInterface $tokenRepo,
        Messaging $messaging
    )
    {
        $this->tokenRepo = $tokenRepo;
        $this->messaging = $messaging;
    }

    public function storeOrUpdateToken(User $user, array $data): bool
    {
        return $this->tokenRepo->updateOrCreateToken($user, $data);
    }

    public function removeToken(User $user, array $data): bool
    {
        if (!$this->tokenRepo->findDeviceToken($user, $data['fcmToken'])) {
            throw new Exception("Token not found.", 422);
        }
        return $this->tokenRepo->removeToken($user, $data);
    }


    /**
     * sendToUser function
     *
     * @param integer|null $userId
     * @param string $title
     * @param string $body
     * @param string $type
     * @param string $targetChannel
     * @param array $data
     * @return Notification|null
     */
    public function sendToUser(
        ?int $userId,
        string $title,
        string $body,
        string $type = 'system',
        string $targetChannel = 'app',
        array $data = []
    ): ?Notification {

        //1. Save data to database as in-app notification history
        $dbNotification = Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'body' => $body,
            'type' => $type,
            'target_channel' => $targetChannel,
            'data' => $data,
            'read_at' => null,
        ]);

        //2. if the target channel is mobile app or all, then send fcm push notification
        if (in_array($targetChannel, ['app', 'all']) && $userId) {
            $this->dispatchFcmToUserDevices($userId, $title, $body, $data);
        }

        //3. if the target channel is web admin or all, then Web Socket/Reverb event broadcast event
        if (in_array($targetChannel, ['web_admin', 'all'])) {
            // broadcast(new \App\Events\AdminNotificationEvent($dbNotification))->toOthers();
        }

        return $dbNotification;
    }

    /**
     * dispatchFcmToUserDevices function
     *
     * @param integer $userId
     * @param string $title
     * @param string $body
     * @param array $data
     * @return void
     */
    protected function dispatchFcmToUserDevices(int $userId, string $title, string $body, array $data = []): void
    {
        // Collect user's all FCM tokens
        $tokens = UserDeviceToken::where('user_id', $userId)
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->toArray();

        if (empty($tokens)) {
            return;
        }

        // value of $data will be string 
        $formattedData = array_map(fn($val) => is_array($val) ? json_encode($val) : (string)$val, $data);

        $message = CloudMessage::new()
            ->withNotification(FirebaseNotification::create($title, $body))
            ->withData($formattedData);

        try {
            // send multicast to multiple device
            $this->messaging->sendMulticast($message, $tokens);
        } catch (\Throwable $e) {
            Log::error("FCM Multicast Error for User ID {$userId}: " . $e->getMessage());
        }
    }


    public function sendToMultipleUsers(array $fcmTokens, string $title, string $body, array $data = []): array
    {
        $validTokens = array_values(array_filter($fcmTokens));

        if (empty($validTokens)) {
            return ['success' => 0, 'failure' => 0];
        }

        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body))
            ->withData($data);

        try {
            // send all tokens  firebase sendmulticast
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


/*
$notificationService->sendToUser(
    userId: $user->id,
    title: "New Order Placed!",
    body: "Your order #105 has been received successfully.",
    type: "order_created",
    targetChannel: "all", // মোবাইল অ্যাপে FCM যাবে + ডাটাবেজে সেভ হবে + ওয়েব অ্যাডমিনে রিয়েলটাইম অ্যালার্ট হবে
    data: [
        'order_id' => 105,
        'click_action' => '/orders/105'
    ]
);
*/