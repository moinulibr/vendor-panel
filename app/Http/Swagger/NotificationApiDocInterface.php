<?php

namespace App\Http\Swagger;
/*
use App\Http\Requests\Api\V1\App\FcmRemoveTokenRequest;
use App\Http\Requests\Api\V1\App\FcmStoreTokenRequest;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

interface NotificationApiDocInterface
{
    #[OA\Get(
        path: "/api/v1/app/notifications",
        summary: "Get User Notifications",
        tags: ["Notification"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "page", in: "query", schema: new OA\Schema(type: "integer", example: 1)),
            new OA\Parameter(name: "channel", in: "query", schema: new OA\Schema(type: "string", enum: ["app", "web_admin"], example: "app"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Notifications fetched successfully"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function index(Request $request);

    #[OA\Post(
        path: "/api/v1/app/notifications/{id}/read",
        summary: "Mark Single Notification as Read",
        tags: ["Notification"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Marked as read successfully")
        ]
    )]
    public function markAsRead(Request $request, $id);

    #[OA\Post(
        path: "/api/v1/app/notifications/read-all",
        summary: "Mark All Notifications as Read",
        tags: ["Notification"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(response: 200, description: "All notifications marked as read")
        ]
    )]
    public function markAllAsRead(Request $request);

    #[OA\Post(
        path: "/api/v1/app/store-fcm-token",
        summary: "Store or Update FCM Device Token",
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
            new OA\Response(response: 200, description: "FCM token saved successfully")
        ]
    )]
    public function storeFcmToken(FcmStoreTokenRequest $request);

    #[OA\Post(
        path: "/api/v1/app/remove-fcm-token",
        summary: "Remove FCM Device Token",
        tags: ["Notification"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["remove_scope"],
                properties: [
                    new OA\Property(property: "remove_scope", type: "string", enum: ["current_device", "all_devices"], example: "current_device"),
                    new OA\Property(property: "fcm_token", type: "string", example: "f7a8b9c0d1e2f3...", nullable: true)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "FCM token removed successfully")
        ]
    )]
    public function removeFcmToken(FcmRemoveTokenRequest $request);

}
    */