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

    public function toggleFavorite(int $userId, array $data): array
    {
        if($data['type'] == "single"){
            $data['variation_id'] = null;
        }else{
            $data['variation_id'] = $data['variation_id'] ?? null;
        }
        $favorite = Favorite::where('user_id', $userId)->where('product_id', $data['product_id'])
        ->where('variation_id', $data['variation_id'])
        ->where('type', $data['type'])
        ->first();

        if ($favorite) {
            $favorite->delete();
            return ['status' => 'removed', 'is_favorite' => false];
        }

        Favorite::create([
            'type' => $data['type'], 
            'variation_id' => $data['variation_id'],
            'product_id' =>  $data['product_id'],
            'user_id' => $userId, //retailer id
            'created_by' => auth()->user()->id,
            'favorite_from' => $data['favorite_from'] ?? 'moible_app'
            ]);
        return ['status' => 'added', 'is_favorite' => true];
    }
}
