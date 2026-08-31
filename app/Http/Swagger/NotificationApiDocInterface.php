<?php

namespace App\Http\Swagger;

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

}