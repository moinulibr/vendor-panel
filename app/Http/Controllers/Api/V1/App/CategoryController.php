<?php

namespace App\Http\Controllers\Api\V1\App;

use Exception;

class CategoryController extends BaseApiController
{
    

    public function getCategories(\Illuminate\Http\Request $request)
    {
        try {
            $user = $request->user();
            //$this->fcmService->storeOrUpdateToken($user, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Category facted successfully.',
            ], 201);
        } catch (Exception $e) {
            $statusCode = ($e->getCode() >= 400 && $e->getCode() < 600) ? $e->getCode() : 500;

            return $this->jsonResponse(
                success: false,
                message: $e->getMessage(),
                data: null,
                statusCode: $statusCode
            );
        }
    }

}