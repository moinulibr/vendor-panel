<?php

namespace App\Repositories\Discount\Interface;

use App\Models\Discount;

interface DiscountRepositoryInterface
{
    public function findValidDiscount(string $title, int $userId, float $amount): ?Discount;
    public function findById(int $discountId): ?Discount;
}