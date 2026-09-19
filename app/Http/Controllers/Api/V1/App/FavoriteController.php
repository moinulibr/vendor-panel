<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Requests\Api\V1\App\ToggleFavoriteListRequest;
use App\Http\Requests\Api\V1\App\ToggleFavoriteRequest;
use App\Http\Resources\Api\V1\App\FavoriteResource;
use App\Http\Swagger\FavoriteApiDocInterface;
use App\Services\FavoriteService;
use App\Utils\UserType;
use Illuminate\Http\JsonResponse;
use Exception;

class FavoriteController extends BaseApiController implements FavoriteApiDocInterface
{
    public function __construct(protected FavoriteService $favoriteService) {}

    public function index(ToggleFavoriteListRequest $request): JsonResponse
    {
        try {
            $retailerId = auth()->id();
            if (auth()->user()->user_type != UserType::SR) {
                $retailerId = $request->retailer_user_id;
            }
            $favorites = $this->favoriteService->getFavorites($retailerId);

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
            $retailerId = auth()->id();
            if (auth()->user()->user_type != UserType::DEALER) {
                $retailerId = $request->retailer_user_id;
            }
            $result = $this->favoriteService->toggleFavorite($retailerId, $request->validated());

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
