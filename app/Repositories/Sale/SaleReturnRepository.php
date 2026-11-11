<?php

namespace App\Repositories\Sale;

use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use App\Models\SaleRefund;
use App\Repositories\Sale\Interface\SaleReturnRepositoryInterface;

class SaleReturnRepository implements SaleReturnRepositoryInterface
{
    public function createSaleReturn(array $data): SaleReturn
    {
        return SaleReturn::create($data);
    }

    public function createSaleReturnItem(array $data): SaleReturnItem
    {
        return SaleReturnItem::create($data);
    }

    public function createSaleRefund(array $data): SaleRefund
    {
        return SaleRefund::create($data);
    }

    public function getLatestReturnNo(string $prefix): ?string
    {
        return SaleReturn::where('return_no', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->lockForUpdate()
            ->value('return_no');
    }

    public function getLatestRefundNo(string $prefix): ?string
    {
        return SaleRefund::where('refund_no', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->lockForUpdate()
            ->value('refund_no');
    }
}
