<?php

namespace App\Repositories\Favorite\Interface;

use Illuminate\Support\Collection;

interface FavoriteRepositoryInterface
{
    public function getUserFavorites(int $userId): Collection;
    public function toggleFavorite(int $userId, int $productId): array;
}
