<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\DeviceToken\Interface\UserDeviceTokenRepositoryInterface;

class FcmNotificationService
{
    protected UserDeviceTokenRepositoryInterface $tokenRepo;

    public function __construct(UserDeviceTokenRepositoryInterface $tokenRepo)
    {
        $this->tokenRepo = $tokenRepo;
    }

    public function storeOrUpdateToken(User $user, array $data): bool
    {
        return $this->tokenRepo->updateOrCreateToken($user, $data);
    }

    public function removeToken(User $user, string $fcmToken): bool
    {
        return $this->tokenRepo->removeToken($user, $fcmToken);
    }
}