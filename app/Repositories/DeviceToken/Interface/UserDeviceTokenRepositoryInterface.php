<?php

namespace App\Repositories\DeviceToken\Interface;

use App\Models\User;

interface UserDeviceTokenRepositoryInterface
{
    public function updateOrCreateToken(User $user, array $data): bool;
    public function removeToken(User $user, string $fcmToken): bool;
}