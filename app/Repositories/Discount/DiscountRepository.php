<?php

namespace App\Repositories\Discount;

use App\Models\Discount;
use App\Repositories\Discount\Interface\DiscountRepositoryInterface;

class DiscountRepository implements DiscountRepositoryInterface
{
    public function findValidDiscount(string $title, int $userId, float $amount): ?Discount
    {
        return Discount::where('title',$title)
            ->where('status', 1)
            ->where('start', '<=', now()->toDateString())
            ->where('end', '>=', now()->toDateString())
            ->where('is_mobile_app', 1)
            //->orderBy('priority', 'desc')
            ->first();
    }

    public function findById(int $discountId): ?Discount
    {
        return Discount::find($discountId);
    }
}
