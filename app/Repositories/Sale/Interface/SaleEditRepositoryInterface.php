<?php

namespace App\Repositories\Sale\Interface;

use App\Models\Sale;
use App\Models\SaleEditHistory;

interface SaleEditRepositoryInterface
{
    public function updateOldSaleToHistory(Sale $sale): void;
    public function createNewVersion(Sale $oldSale, array $newSaleData): Sale;
    public function createEditHistory(array $data): SaleEditHistory;
    public function createEditItem(array $data): void;
}
