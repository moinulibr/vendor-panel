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
            new OA\Parameter(name: "page", in: "query", required: false, schema: new OA\Schema(type: "integer", example: 1)),
            new OA\Parameter(name: "per_page", in: "query", required: false, schema: new OA\Schema(type: "integer", example: 15)),
            new OA\Parameter(name: "channel", in: "query", required: false, schema: new OA\Schema(type: "string", enum: ["app", "web_admin"], example: "app"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Notifications fetched successfully",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Notifications retrieved successfully.",
                        "data" => [
                            "unread_count" => 2,
                            "notifications" => [
                                [
                                    "id" => 1,
                                    "title" => "Order Placed",
                                    "body" => "Your order #1002 has been received.",
                                    "type" => "order_status",
                                    "read_at" => null,
                                    "created_at" => "2026-08-31 10:00:00"
                                ]
                            ]
                        ],
                        "pagination" => [
                            "total" => 1,
                            "count" => 1,
                            "per_page" => 15,
                            "current_page" => 1,
                            "total_pages" => 1,
                            "has_more" => false
                        ]
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthenticated"),
            new OA\Response(response: 500, description: "Server Error")
        ]
    )]
    public function index(Request $request);

    #[OA\Post(
        path: "/api/v1/app/notifications/{id}/read",
        summary: "Mark Single Notification as Read",
        tags: ["Notification"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer", example: 1))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Marked as read successfully",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "Notification marked as read successfully.",
                        "data" => null
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthenticated"),
            new OA\Response(response: 500, description: "Server Error")
        ]
    )]
    public function markAsRead(Request $request, $id);

    #[OA\Post(
        path: "/api/v1/app/notifications/read-all",
        summary: "Mark All Notifications as Read",
        tags: ["Notification"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "All notifications marked as read",
                content: new OA\JsonContent(
                    example: [
                        "success" => true,
                        "message" => "All notifications marked as read successfully.",
                        "data" => null
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthenticated"),
            new OA\Response(response: 500, description: "Server Error")
        ]
    )]
    public function markAllAsRead(Request $request);
}
