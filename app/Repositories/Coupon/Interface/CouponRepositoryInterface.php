<?php

namespace App\Repositories\Coupon\Interface;

use App\Models\Coupon;

interface CouponRepositoryInterface
{
    public function findValidCoupon(string $code, float $cartTotal): ?Coupon;
}