<?php

namespace App\Repositories\Sale;

use App\Models\Sale;
use App\Models\SaleEditHistory;
use App\Models\SaleEditItem;
use App\Repositories\Sale\Interface\SaleEditRepositoryInterface;

class SaleEditRepository implements SaleEditRepositoryInterface
{
    public function updateOldSaleToHistory(Sale $sale): void
    {
        $sale->update(['is_current' => false]);
    }

    public function createNewVersion(Sale $oldSale, array $newSaleData): Sale
    {
        $newSaleData['parent_id'] = $oldSale->parent_id ?? $oldSale->id;
        $newSaleData['version'] = $oldSale->version + 1;
        $newSaleData['is_current'] = true;
        return Sale::create($newSaleData);
    }

    public function createEditHistory(array $data): SaleEditHistory
    {
        return SaleEditHistory::create($data);
    }

    public function createEditItem(array $data): void
    {
        SaleEditItem::create($data);
    }
}
