<?php

namespace App\Repositories\DeviceToken;

use App\Models\User;
use App\Models\UserDeviceToken;
use App\Repositories\DeviceToken\Interface\UserDeviceTokenRepositoryInterface;

class UserDeviceTokenRepository implements UserDeviceTokenRepositoryInterface
{
    public function updateOrCreateToken(User $user, array $data): bool
    {
        // ১. যদি এই fcm_token টি অন্য কোনো ইউজারের ড্রাইভে থাকে, তবে সেটি আগে ডিলিট করে ক্লিন করা হবে
        UserDeviceToken::where('fcm_token', $data['fcm_token'])
            ->where('user_id', '!=', $user->id)
            ->delete();

        // ২. একই ইউজার ও ডিভাইস আইডি বা টোকেনের সাপেক্ষে আপডেট বা ক্রিয়েট করা
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

        // যদি সব ডিভাইস থেকে সরাতে চায়
        if ($data['remove_scope'] === 'all_devices') {
            return (bool) $query->delete();
        }

        // শুধু নির্দিষ্ট বর্তমান ডিভাইস থেকে সরাতে চাইলে
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