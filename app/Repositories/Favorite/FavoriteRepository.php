<?php

namespace App\Repositories\Favorite;

use App\Models\Favorite;
use App\Repositories\Favorite\Interface\FavoriteRepositoryInterface;
use Illuminate\Support\Collection;

class FavoriteRepository implements FavoriteRepositoryInterface
{
    public function getUserFavorites(int $userId): Collection
    {
        return Favorite::with('product')->where('user_id', $userId)->get();
    }

    public function toggleFavorite(int $userId, int $productId): array
    {
        $favorite = Favorite::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($favorite) {
            $favorite->delete();
            return ['status' => 'removed', 'is_favorite' => false];
        }

        Favorite::create(['user_id' => $userId, 'product_id' => $productId]);
        return ['status' => 'added', 'is_favorite' => true];
    }
}
