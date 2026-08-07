<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\DeviceToken\Interface\UserDeviceTokenRepositoryInterface;
use Exception;

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

    public function removeToken(User $user, array $data): bool
    {
        if (!$this->tokenRepo->findDeviceToken($user, $data['fcmToken'])) {
            throw new Exception("Token not found.", 422);
        }
        return $this->tokenRepo->removeToken($user, $data);
    }
}