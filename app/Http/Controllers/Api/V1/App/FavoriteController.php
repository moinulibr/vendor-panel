<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Requests\Api\V1\App\ToggleFavoriteRequest;
use App\Http\Resources\Api\V1\App\FavoriteResource;
use App\Http\Swagger\FavoriteApiDocInterface;
use App\Services\FavoriteService;
use Illuminate\Http\JsonResponse;
use Exception;

class FavoriteController extends BaseApiController implements FavoriteApiDocInterface
{
    public function __construct(protected FavoriteService $favoriteService) {}

    public function index(): JsonResponse
    {
        try {
            $favorites = $this->favoriteService->getFavorites(auth()->id());
            return $this->sendResponse(FavoriteResource::collection($favorites), 'Favorites fetched successfully.');
        } catch (Exception $e) {
            return $this->sendError('Failed to fetch favorites.', ['error' => $e->getMessage()]);
        }
    }

    public function toggle(ToggleFavoriteRequest $request): JsonResponse
    {
        try {
            $result = $this->favoriteService->toggleFavorite(auth()->id(), $request->product_id);
            return $this->sendResponse($result, 'Favorite status updated successfully.');
        } catch (Exception $e) {
            return $this->sendError('Failed to update favorite status.', ['error' => $e->getMessage()]);
        }
    }
}
