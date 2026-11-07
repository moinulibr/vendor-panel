<?php

namespace App\Repositories\Sale\Interface;

use App\Models\Sale;
use App\Models\SaleEditHistory;
use Illuminate\Support\Collection;

interface SaleEditRepositoryInterface
{
    public function createNewVersion(array $data): Sale;

    public function createEditHistory(array $data): SaleEditHistory;

    public function createEditItems(array $items): Collection;

    public function updateOldSaleToHistory(Sale|int $sale, array $attributes = []): bool;
}
