<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Controllers\Controller;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "OriginalPonno SR & Retailer - Mobile App API",
    description: "RESTful API documentation for SR & Retailer mobile application."
)]
#[OA\Server(
    url: "http://127.0.0.1:8000",
    description: "Local Development Server"
)]
#[OA\SecurityScheme(
    securityScheme: "sanctum",
    type: "http",
    scheme: "bearer",
    bearerFormat: "Sanctum",
    description: "Enter Bearer Token here (Format: Bearer <token>)"
)]
abstract class BaseApiController extends Controller
{
    /**
     * Common JSON Response Format Helper
     */
    protected function jsonResponse(bool $success, string $message, mixed $data = null, int $statusCode = 200)
    {
        $response = [
            'success' => $success,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }
}
