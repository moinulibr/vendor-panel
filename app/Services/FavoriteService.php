<?php

namespace App\Services;

use App\Repositories\Favorite\Interface\FavoriteRepositoryInterface;

class FavoriteService
{
    public function __construct(protected FavoriteRepositoryInterface $favoriteRepository) {}

    public function getFavorites(int $userId)
    {
        return $this->favoriteRepository->getUserFavorites($userId);
    }

    public function toggleFavorite(int $userId, int $productId): array
    {
        return $this->favoriteRepository->toggleFavorite($userId, $productId);
    }
}
