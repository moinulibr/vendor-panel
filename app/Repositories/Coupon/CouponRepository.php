<?php

namespace App\Repositories\Coupon;

use App\Models\Coupon;
use App\Repositories\Coupon\Interface\CouponRepositoryInterface;

class CouponRepository implements CouponRepositoryInterface
{
    public function findValidCoupon(string $code, float $cartTotal): ?Coupon
    {
        $today = now()->format('Y-m-d');

        return Coupon::where('code', $code)
            ->where('status', 1)
            ->where(function ($row) use ($cartTotal) {
                $row->where('minimum_amount', 0)
                    ->orWhereNull('minimum_amount')
                    ->orWhere('minimum_amount', '<=', $cartTotal);
            })
            ->whereDate('start', '<=', $today)
            ->whereDate('end', '>=', $today)
            ->first();
    }
}
