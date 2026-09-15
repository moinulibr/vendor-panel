<?php

namespace App\Http\Swagger;

use OpenApi\Attributes as OA;

interface SettingsSwagger
{
    #[OA\Get(
        path: "/api/v1/app/feature/delivery",
        summary: "Get delivery charge settings",
        description: "Fetch dynamic delivery charge configurations and type rules.",
        tags: ["Settings"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Delivery charge settings fetched successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(
                                    property: "delivery_charge",
                                    type: "object",
                                    properties: [
                                        new OA\Property(property: "type", type: "string", example: "string"),
                                        new OA\Property(property: "value", type: "string", example: "আলোচনা সাপেক্ষ"),
                                        new OA\Property(property: "note", type: "string", example: "if type is string, then value will be string. if type is integer, then value will be also integer.")
                                    ]
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function featureDelivery();

    #[OA\Get(
        path: "/api/v1/app/features/coupon-and-discount",
        summary: "Get coupon and discount feature rules",
        description: "Fetch rules, limits, and interaction policies for coupons and product discounts.",
        tags: ["Settings"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Feature settings fetched successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Feature settings fetched successfully"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(
                                    property: "coupon",
                                    type: "object",
                                    properties: [
                                        new OA\Property(property: "isApplicable", type: "boolean", example: true),
                                        new OA\Property(property: "minOrderAmount", type: "integer", example: 500),
                                        new OA\Property(property: "maxDiscountAmount", type: "integer", example: 2000),
                                        new OA\Property(property: "allowOnDiscountedItem", type: "boolean", example: false),
                                        new OA\Property(property: "warningMessage", type: "string", example: "Coupon cannot be applied if cart contains discounted items."),
                                        new OA\Property(property: "alertMessage", type: "string", example: "Minimum order amount for using coupon is ৳500.")
                                    ]
                                ),
                                new OA\Property(
                                    property: "discount",
                                    type: "object",
                                    properties: [
                                        new OA\Property(property: "isApplicable", type: "boolean", example: true),
                                        new OA\Property(property: "allowWithCoupon", type: "boolean", example: false),
                                        new OA\Property(property: "maxDiscountPercent", type: "integer", example: 50),
                                        new OA\Property(property: "warningMessage", type: "string", example: "Product discount is not combinable with promotional coupons."),
                                        new OA\Property(property: "alertMessage", type: "string", nullable: true, example: null)
                                    ]
                                ),
                                new OA\Property(
                                    property: "globalRules",
                                    type: "object",
                                    properties: [
                                        new OA\Property(property: "allowBothInSingleOrder", type: "boolean", example: true),
                                        new OA\Property(property: "stackDiscountAndCoupon", type: "boolean", example: false),
                                        new OA\Property(property: "conflictPolicyMessage", type: "string", example: "You can only apply coupon on non-discounted products in your cart.")
                                    ]
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function featureCouponAndDiscount();

    #[OA\Get(
        path: "/api/v1/app/features/status",
        summary: "Get features permission status based on user type",
        description: "Returns feature capabilities, active user type flags, and UI labels.",
        security: [["sanctum" => []]],
        tags: ["Settings"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Features status fetched successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "loged_in_user_type_id", type: "integer", example: 5),
                                new OA\Property(property: "applicable_user_type_id", type: "integer", example: 5),
                                new OA\Property(property: "user_type_id_", type: "integer", example: 5),
                                new OA\Property(property: "user_type_label", type: "string", example: "Dealer"),
                                new OA\Property(
                                    property: "user_type_id_5",
                                    type: "object",
                                    properties: [
                                        new OA\Property(
                                            property: "auth",
                                            type: "object",
                                            properties: [
                                                new OA\Property(property: "can_register", type: "boolean", example: true),
                                                new OA\Property(property: "can_login", type: "boolean", example: true),
                                                new OA\Property(property: "can_forgot_password", type: "boolean", example: true),
                                                new OA\Property(property: "can_reset_password", type: "boolean", example: true)
                                            ]
                                        ),
                                        new OA\Property(
                                            property: "order",
                                            type: "object",
                                            properties: [
                                                new OA\Property(property: "can_create", type: "boolean", example: true),
                                                new OA\Property(property: "can_edit", type: "boolean", example: true),
                                                new OA\Property(property: "can_delete", type: "boolean", example: false),
                                                new OA\Property(property: "can_view", type: "boolean", example: true),
                                                new OA\Property(property: "can_list", type: "boolean", example: true),
                                                new OA\Property(property: "can_change_status", type: "boolean", example: true)
                                            ]
                                        ),
                                        new OA\Property(
                                            property: "cart",
                                            type: "object",
                                            properties: [
                                                new OA\Property(property: "can_create", type: "boolean", example: true),
                                                new OA\Property(property: "can_edit", type: "boolean", example: true),
                                                new OA\Property(property: "can_delete", type: "boolean", example: true),
                                                new OA\Property(property: "can_list", type: "boolean", example: true),
                                                new OA\Property(property: "can_checkout", type: "boolean", example: true)
                                            ]
                                        ),
                                        new OA\Property(
                                            property: "favorite",
                                            type: "object",
                                            properties: [
                                                new OA\Property(property: "can_create", type: "boolean", example: true),
                                                new OA\Property(property: "can_edit", type: "boolean", example: true),
                                                new OA\Property(property: "can_delete", type: "boolean", example: true),
                                                new OA\Property(property: "can_list", type: "boolean", example: true),
                                                new OA\Property(property: "can_view", type: "boolean", example: true),
                                                new OA\Property(property: "can_toggle", type: "boolean", example: true),
                                                new OA\Property(property: "can_add_to_cart", type: "boolean", example: true),
                                                new OA\Property(property: "can_remove_from_cart", type: "boolean", example: true),
                                                new OA\Property(property: "can_favorite_to_cart", type: "boolean", example: true)
                                            ]
                                        ),
                                        new OA\Property(
                                            property: "shipping_address",
                                            type: "object",
                                            properties: [
                                                new OA\Property(property: "can_add", type: "boolean", example: true),
                                                new OA\Property(property: "can_edit", type: "boolean", example: true),
                                                new OA\Property(property: "can_delete", type: "boolean", example: true)
                                            ]
                                        ),
                                        new OA\Property(
                                            property: "product_filter_labels",
                                            type: "object",
                                            properties: [
                                                new OA\Property(property: "brand", type: "string", example: "Select Brand"),
                                                new OA\Property(property: "category", type: "string", example: "Choose Category"),
                                                new OA\Property(property: "vendor", type: "string", example: "All Vendors")
                                            ]
                                        )
                                    ]
                                )
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function getFeaturesStatus();
}
