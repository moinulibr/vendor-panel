<?php

namespace App\Services;

use App\Utils\UserType;

class SettingService
{
    /**
     * Get Delivery Charge Configurations
     */
    public function getDeliverySettings(): array
    {
        return [
            'delivery_charge' => [
                'type'  => 'string',
                'value' => 'আলোচনা সাপেক্ষ',
                'note'  => 'If type is string, value is descriptive. If integer, it represents direct currency amount.'
            ]
        ];
    }

    /**
     * Get Coupon and Discount Rules
     */
    public function getCouponAndDiscountSettings(): array
    {
        return [
            'coupon' => [
                'isApplicable'          => true,
                'minOrderAmount'        => 500,
                'maxDiscountAmount'     => 2000,
                'allowOnDiscountedItem' => false,
                'warningMessage'        => 'Coupon cannot be applied if cart contains discounted items.',
                'alertMessage'          => 'Minimum order amount for using coupon is ৳500.',
            ],
            'discount' => [
                'isApplicable'       => true,
                'allowWithCoupon'    => false,
                'maxDiscountPercent' => 50,
                'warningMessage'     => 'Product discount is not combinable with promotional coupons.',
                'alertMessage'       => null,
            ],
            'globalRules' => [
                'allowBothInSingleOrder' => true,
                'stackDiscountAndCoupon' => false,
                'conflictPolicyMessage'  => 'You can only apply coupon on non-discounted products in your cart.',
            ]
        ];
    }

    /**
     * Get Feature Status, Cart & Quotation Expiry Rules
     */
    public function getFeaturesStatus(?object $user): array
    {
        $userTypeId = $user?->user_type ?? UserType::SR;

        return [
            "loged_in_user_type_id"   => $userTypeId,
            "applicable_user_type_id" => UserType::SR,
            "user_type_id_"           => UserType::SR,
            "user_type_label"         => UserType::getLabel(UserType::SR),
            "user_type_id_" . UserType::SR => [
                'auth' => [
                    'can_register'        => true,
                    'can_login'           => true,
                    'can_forgot_password' => true,
                    'can_reset_password'  => true,
                ],
                'order' => [
                    'can_create'        => true,
                    'can_edit'          => true,
                    'can_delete'        => false,
                    'can_view'          => true,
                    'can_list'          => true,
                    'can_change_status' => true,
                ],
                'cart' => [
                    'can_create'         => true,
                    'can_edit'           => true,
                    'can_delete'         => true,
                    'can_list'           => true,
                    'can_checkout'       => true,
                    // New Expiry Configs
                    'expire_in_minutes'  => 1440, // ২৪ ঘণ্টা (Minutes-এ রাখা স্ট্যান্ডার্ড)
                    'auto_clear_expired' => true,
                    'expiry_note'        => 'Cart items will automatically expire and clear after 24 hours of inactivity.'
                ],
                'quotation' => [
                    'can_create'        => true,
                    'can_view'          => true,
                    'can_list'          => true,
                    'can_accept'        => true,
                    'can_reject'        => true,
                    // New Expiry Configs
                    'expire_in_days'    => 7, // ৭ দিন ভ্যালিডিটি
                    'auto_cancel'       => true,
                    'expiry_note'       => 'Quotations will automatically expire after 7 days from generation.'
                ],
                'favorite' => [
                    'can_create'           => true,
                    'can_edit'             => true,
                    'can_delete'           => true,
                    'can_list'             => true,
                    'can_view'             => true,
                    'can_toggle'           => true,
                    'can_add_to_cart'      => true,
                    'can_remove_from_cart' => true,
                    'can_favorite_to_cart' => true,
                ],
                'shipping_address' => [
                    'can_add'    => true,
                    'can_edit'   => true,
                    'can_delete' => true,
                ],
                'product_filter_labels' => [
                    'brand'    => 'Select Brand',
                    'category' => 'Choose Category',
                    'vendor'   => 'All Vendors',
                ],
            ]
        ];
    }
}
