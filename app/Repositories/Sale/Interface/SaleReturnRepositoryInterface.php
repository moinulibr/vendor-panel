<?php

namespace App\Repositories\Sale\Interface;

use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use App\Models\SaleRefund;

interface SaleReturnRepositoryInterface
{
    public function createSaleReturn(array $data): SaleReturn;
    public function createSaleReturnItem(array $data): SaleReturnItem;
    public function createSaleRefund(array $data): SaleRefund;
    public function getLatestReturnNo(string $prefix): ?string;
    public function getLatestRefundNo(string $prefix): ?string;
}
