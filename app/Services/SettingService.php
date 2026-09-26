<?php

namespace App\Services;

use Carbon\Carbon;
use App\Utils\UserType;

class SettingService
{
    public $bufferThresholdDefaultValue = 10;
    public $extendDurationDefaultValue = 5;
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
            ],
            "delivery_duration" => [
                "isPartial" => true,
                "min_days" => 3,
                "max_days" => 7
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
                'minOrderAmount'        => 1000,
                'maxDiscountAmount'     => 1000,
                'allowOnDiscountedItem' => false,
                'warningMessage'        => 'Coupon cannot be applied if cart contains discounted items.',
                'alertMessage'          => 'Minimum order amount for using coupon is ৳500.',
            ],
            'discount' => [
                'isApplicable'       => true,
                'allowWithCoupon'    => false,
                'minOrderAmount'     => 1000,
                'maxDiscountAmount'  => 1000,
                'maxDiscountPercent' => 50,
                'warningMessage'     => 'Product discount is not combinable with promotional coupons.',
                'alertMessage'       => null,
            ],
            'globalRules' => [
                'allowBothInSingleOrder' => true,
                'stackDiscountAndCoupon' => true,
                'conflictPolicyMessage'  => 'You can only apply coupon on non-discounted products in your cart.',
            ]
        ];
    }


    /**
     * durationUnit function
     *
     * @param string $unit
     * @return string
     */
    public function durationUnit(string $unit): string
    {
        return match (strtolower($unit)) {
            'minute', 'minutes' => 'minutes',
            'hour', 'hours'     => 'hours',
            'day', 'days'       => 'days',
            default             => 'days',
        };
    }
    /**
     * Convert Expiry Value & Unit into a Future Carbon Timestamp
     * Supports: 'minutes', 'hours', 'days'
     */
    public function getExpiryTimestamp(int $value, string $unit = 'days'): Carbon
    {
        return match (strtolower($unit)) {
            'minute', 'minutes' => now()->addMinutes($value),
            'hour', 'hours'     => now()->addHours($value),
            'day', 'days'       => now()->addDays($value),
            default             => now()->addDays($value),
        };
    }

    /**
     * Global Cart Expiry Rules
     */
    public function getCartExpiryConfig(): array
    {
        $unit = $this->durationUnit('days');
        $unitValue = 2;
        return [
            'unit'               => $unit, // day
            'value'              => $unitValue,
            'buffer_grace_unit'  => $this->durationUnit('hours'), // hour
            'buffer_threshold'   => 5, // 5 $this->durationUnit('hours') - If the user becomes active within 5 hours before the expiry time. //থ্রেশহোল্ড - // থ্রেশহোল্ড /দোরগোড়া", "সীমানা" বা "সূচনা বিন্দু" বর্ডার লাইন বা ভ্যালু
            'extend_unit'        => $this->durationUnit('hours'),
            'extend_duration'    => 3, // 3 $this->durationUnit('hours')
            'auto_clear_expired' => true,
            'expiry_note'        => 'Cart items will expire after '. $unitValue . ' ' . $unit . '.',
        ];
    }

    /**
     * Helper to get Calculated Cart Expiry Timestamp directly
     */
    public function getCartExpiresAt(): Carbon
    {
        $config = $this->getCartExpiryConfig();
        return $this->getExpiryTimestamp($config['value'], $config['unit']);
    }

    /**
     * Global Quotation Expiry Rules
     */
    public function getQuotationExpiryConfig(): array
    {
        return [
            'value'          => 7,
            'unit'           => 'days',         // Options: 'minutes', 'hours', 'days'
            'expire_in_days' => 7,
            'auto_cancel'    => true,
            'expiry_note'    => 'Quotations will automatically expire after 7 days from generation.'
        ];
    }

    /**
     * Helper to get Calculated Quotation Expiry Timestamp directly
     */
    public function getQuotationExpiresAt(): Carbon
    {
        $config = $this->getQuotationExpiryConfig();
        return $this->getExpiryTimestamp($config['value'], $config['unit']);
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
