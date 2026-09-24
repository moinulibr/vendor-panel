<?php

namespace App\Repositories\Discount;

use App\Models\Discount;
use App\Repositories\Discount\Interface\DiscountRepositoryInterface;

class DiscountRepository implements DiscountRepositoryInterface
{
    public function findValidDiscount(int $userId, float $amount): ?Discount
    {
        return Discount::where('status', 1)
            ->where('start', '<=', now()->toDateString())
            ->where('end', '>=', now()->toDateString())
            ->where(function ($query) use ($userId) {
                $query->whereNull('user_id')
                    ->orWhere('user_id', $userId);
            })
            ->orderBy('priority', 'desc')
            ->first();
    }

    public function findById(int $discountId): ?Discount
    {
        return Discount::find($discountId);
    }
}
