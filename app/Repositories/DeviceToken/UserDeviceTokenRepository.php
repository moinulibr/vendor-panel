<?php

namespace App\Repositories\DeviceToken;

use App\Models\User;
use App\Models\UserDeviceToken;
use App\Repositories\DeviceToken\Interface\UserDeviceTokenRepositoryInterface;

class UserDeviceTokenRepository implements UserDeviceTokenRepositoryInterface
{
    public function updateOrCreateToken(User $user, array $data): bool
    {
        // device_id থাকলে সেটা দিয়ে ম্যাচ করাবে, না থাকলে fcm_token দিয়ে ইউনিভার্সাল আপডেট করবে
        $matchAttributes = [
            'user_id' => $user->id,
        ];

        if (!empty($data['device_id'])) {
            $matchAttributes['device_id'] = $data['device_id'];
        } else {
            $matchAttributes['fcm_token'] = $data['fcm_token'];
        }

        UserDeviceToken::updateOrCreate(
            $matchAttributes,
            [
                'fcm_token'   => $data['fcm_token'],
                'device_type' => $data['device_type'] ?? null,
            ]
        );

        return true;
    }

    public function removeToken(User $user, string $fcmToken): bool
    {
        return (bool) UserDeviceToken::where('user_id', $user->id)
            ->where('fcm_token', $fcmToken)
            ->delete();
    }
}