<?php

namespace App\Repositories\Sale;

use App\Models\Sale;
use App\Models\SaleEditHistory;
use App\Models\SaleEditItem;
use App\Repositories\Sale\Interface\SaleEditRepositoryInterface;
use Illuminate\Support\Collection;

class SaleEditRepository implements SaleEditRepositoryInterface
{
    public function createNewVersion(array $data): Sale
    {
        return Sale::create($data);
    }

    public function createEditHistory(array $data): SaleEditHistory
    {
        return SaleEditHistory::create($data);
    }

    public function createEditItems(array $items): Collection
    {
        if (empty($items)) {
            return collect();
        }

        if (array_is_list($items) === false && isset($items['sale_id'])) {
            $items = [$items];
        }

        $records = collect();
        foreach ($items as $item) {
            $records->push(SaleEditItem::create($item));
        }

        return $records;
    }

    public function updateOldSaleToHistory(Sale|int $sale, array $attributes = []): bool
    {
        $data = array_merge(['is_current' => false], $attributes);

        if ($sale instanceof Sale) {
            return $sale->update($data);
        }

        return (bool) Sale::where('id', $sale)->update($data);
    }
}
