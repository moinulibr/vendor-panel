<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Requests\Api\V1\App\ToggleFavoriteRequest;
use App\Http\Resources\Api\V1\App\FavoriteResource;
use App\Services\FavoriteService;
use Illuminate\Http\JsonResponse;
use Exception;

class SettingsController extends BaseApiController
{
    public function __construct(protected FavoriteService $favoriteService) {}

    public function index(): JsonResponse
    {
        try {
            $favorites = $this->favoriteService->getFavorites(auth()->id());

            return $this->jsonResponse(
                success: true,
                message: 'Favorites fetched successfully.',
                data: FavoriteResource::collection($favorites),
                statusCode: 200
            );
        } catch (Exception $e) {
            return $this->jsonResponse(
                success: false,
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    public function toggle(ToggleFavoriteRequest $request): JsonResponse
    {
        try {
            $result = $this->favoriteService->toggleFavorite(auth()->id(), $request->validated());

            return $this->jsonResponse(
                success: true,
                message: 'Favorite status updated successfully.',
                data: $result,
                statusCode: 200
            );
        } catch (Exception $e) {
            return $this->jsonResponse(
                success: false,
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }
}
