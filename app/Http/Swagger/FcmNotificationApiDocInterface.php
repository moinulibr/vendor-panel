<?php

namespace App\Http\Swagger;

use App\Http\Requests\Api\V1\App\FcmRemoveTokenRequest;
use App\Http\Requests\Api\V1\App\FcmStoreTokenRequest;
use OpenApi\Attributes as OA;

interface FcmNotificationApiDocInterface
{
    #[OA\Post(
        path: "/api/v1/app/store-fcm-token",
        summary: "Store or Update FCM Device Token",
        description: "Stores or updates the Firebase Cloud Messaging token for push notifications.",
        tags: ["Notification"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["fcm_token"],
                properties: [
                    new OA\Property(property: "fcm_token", type: "string", example: "f7a8b9c0d1e2f3..."),
                    new OA\Property(property: "device_type", type: "string", enum: ["android", "ios", "web"], example: "android", nullable: true),
                    new OA\Property(property: "device_id", type: "string", example: "123e4567-e89b-12d3-a456-426614174000", nullable: true)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "FCM token saved successfully"),
            new OA\Response(response: 422, description: "Validation Error"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function storeFcmToken(FcmStoreTokenRequest $request);

    #[OA\Post(
        path: "/api/v1/app/remove-fcm-token",
        summary: "Remove FCM Device Token",
        description: "Removes an existing FCM token (e.g., on logout).",
        tags: ["Notification"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["remove_scope"],
                properties: [
                    new OA\Property(property: "remove_scope", type: "string", enum: ["current_device", "all_devices"], example: "current_device [current_device or all_devices]"),
                    new OA\Property(property: "fcm_token", type: "string", example: "f7a8b9c0d1e2f3...", nullable: true, description: "Required if remove_scope is current_device")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "FCM token removed successfully"),
            new OA\Response(response: 422, description: "Validation Error"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function removeFcmToken(FcmRemoveTokenRequest $request);
}