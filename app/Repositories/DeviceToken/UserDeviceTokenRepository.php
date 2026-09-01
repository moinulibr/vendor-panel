<?php

namespace App\Repositories\DeviceToken;

use App\Models\User;
use App\Models\UserDeviceToken;
use App\Repositories\DeviceToken\Interface\UserDeviceTokenRepositoryInterface;

class UserDeviceTokenRepository implements UserDeviceTokenRepositoryInterface
{
    public function updateOrCreateToken(User $user, array $data): bool
    {
        // 1. - if this fcm_token already exists in another user's device, delete other user's device
        UserDeviceToken::where('fcm_token', $data['fcm_token'])
            ->where('user_id', '!=', $user->id)
            ->delete();

        // 2. - update or create base on same user and device 
        UserDeviceToken::updateOrCreate(
            [
                'user_id'   => $user->id,
                'fcm_token' => $data['fcm_token'],
            ],
            [
                'device_type' => $data['device_type'] ?? null,
                'device_id'   => $data['device_id'] ?? null,
            ]
        );

        return true;
    }

    public function removeToken(User $user, array $data): bool
    {
        $query = UserDeviceToken::where('user_id', $user->id);

        // if remove token from all devices
        if ($data['remove_scope'] === 'all_devices') {
            return (bool) $query->delete();
        }

        // if remove token from current device
        if (!empty($data['fcm_token'])) {
            return (bool) $query->where('fcm_token', $data['fcm_token'])->delete();
        }

        return false;
    }


    public function findDeviceToken(User $user, string $fcmToken): bool
    {
        return (bool) UserDeviceToken::where('user_id', $user->id)
            ->where('fcm_token', $fcmToken)
            ->first();
    }
}