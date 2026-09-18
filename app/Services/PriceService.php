<?php
namespace App\Services;

use App\Repositories\User\Interface\UserRepositoryInterface;
use App\Utils\UserType;

class PriceService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}


    public function getUserTypeWisePrice(int $userId, Object $item)
    {
        if (!$userId) {
            return (float) ($item->sell_price ?? 0);
        }

        $user = $this->userRepository->findById($userId);

        if (!$user) {
            return (float) ($item->sell_price ?? 0);
        }

        return match ((int) $user->user_type) {
            UserType::DEALER => (float) (!empty($item->dealer_price) ? $item->dealer_price : ($item->sell_price ?? 0)),
            UserType::GENERAL_APP_CUSTOMER => (float) (!empty($item->wholesale_price) ? $item->wholesale_price : ($item->sell_price ?? 0)),
            default => (float) ($item->sell_price ?? 0),
        };
    }

    
}
