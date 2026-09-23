<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Swagger\SettingsSwagger;
use App\Services\SettingService;
use App\Utils\UserType;
use Illuminate\Http\JsonResponse;

class SettingsController extends BaseApiController implements SettingsSwagger
{
    public function __construct(
        protected SettingService $settingService
    ) {}

    public function featureDelivery(): JsonResponse
    {
        return $this->jsonResponse(
            success: true,
            message: 'Feature delivery settings fetched successfully.',
            data: $this->settingService->getDeliverySettings(),
            statusCode: 200
        );
    }

    public function featureCouponAndDiscount(): JsonResponse
    {
        return $this->jsonResponse(
            success: true,
            message: 'Feature coupon and discount settings fetched successfully',
            data: $this->settingService->getCouponAndDiscountSettings(),
            statusCode: 200
        );
    }

    /**
     * Get features enable/disable status, cart & quotation expiry rules and labels.
     */
    public function getFeaturesStatus(): JsonResponse
    {
        $user = auth()->user();

        return $this->jsonResponse(
            success: true,
            message: 'Features status fetched successfully.',
            data: $this->settingService->getFeaturesStatus($user),
            statusCode: 200
        );
    }
}






/*public function featureDelivery(): JsonResponse
{
    return response()->json([
        'success' => true,
        'data'    => [
            'delivery_charge' => [
                'type' => 'string',
                'value' => 'আলোচনা সাপেক্ষ',
                'note' => 'if type is string, then value will be string. if type is integer, then value will be also integer.'
            ]
        ],
    ], 200);
}
public function featureCouponAndDiscount(): JsonResponse
{
    return response()->json([
        'success' => true,
        'message' => 'Feature settings fetched successfully',
        'data'    => [
            // ১. কুপন সেটিংস ও রুলস
            'coupon' => [
                'isApplicable'        => true,  // মোবাইল অ্যাপে কুপন অপশন অন/অফ
                'minOrderAmount'      => 500,   // কুপন অ্যাপ্লাই করতে অন্তত কত টাকার অর্ডার লাগবে
                'maxDiscountAmount'   => 2000,  // কুপন দিয়ে সর্বোচ্চ কত টাকা পর্যন্ত ছাড় পাওয়া যাবে
                'allowOnDiscountedItem' => false, // প্রোডাক্টে অলরেডি ডিসকাউন্ট থাকলে এই কুপন কাজ করবে কিনা
                'warningMessage'      => 'Coupon cannot be applied if cart contains discounted items.', // নিয়ম না মানলে ওয়ার্নিং
                'alertMessage'        => 'Minimum order amount for using coupon is ৳500.',
            ],

            // ২. প্রোডাক্ট ডিসকাউন্ট সেটিংস ও রুলস
            'discount' => [
                'isApplicable'        => true,  // প্রোডাক্ট ডিসকাউন্ট ওভারঅল সিস্টেম অন/অফ
                'allowWithCoupon'     => false, // প্রোডাক্টে ডিসকাউন্ট থাকা অবস্থায় এক্সট্রা কুপন ছাড় দেওয়া যাবে কিনা
                'maxDiscountPercent'  => 50,    // যেকোনো প্রোডাক্টে সর্বোচ্চ কত % পর্যন্ত ডিসকাউন্ট হতে পারে
                'warningMessage'      => 'Product discount is not combinable with promotional coupons.',
                'alertMessage'        => null,
            ],

            // ৩. গ্লোবাল কার্ট ও ইন্টারঅ্যাকশন রুলস (Coupon + Discount Combined Rules)
            'globalRules' => [
                'allowBothInSingleOrder' => true, // একই অর্ডারে ডিসকাউন্টেড প্রোডাক্ট + নন-ডিসকাউন্টেড প্রোডাক্টে কুপন দুটো একসাথে এলাউড কিনা
                'stackDiscountAndCoupon' => false, // একই নির্দিষ্ট প্রোডাক্টের ওপর ডিসকাউন্ট + কুপন দুটি একসাথে ওভারল্যাপ করবে কিনা
                'conflictPolicyMessage'  => 'You can only apply coupon on non-discounted products in your cart.',
            ]
        ],
    ], 200);
}
*/
/*public function getFeaturesStatus(): JsonResponse
{
    return response()->json([
        'success' => true,
        'data'    => [
            "loged_in_user_type_id" => auth()->user()->user_type,
            "applicable_user_type_id" => UserType::SR,
            "user_type_id_" => UserType::SR,
            "user_type_label" => UserType::getLabel(UserType::SR),
            "user_type_id_".UserType::SR => [
                'auth' => [
                    'can_register'        => true,
                    'can_login'           => true,
                    'can_forgot_password' => true,
                    'can_reset_password'  => true,
                ],
                'order' => [
                    'can_create' => true,
                    'can_edit'   => true,
                    'can_delete' => false,
                    'can_view'   => true,
                    'can_list'   => true,
                    'can_change_status' => true,
                ],
                'cart' => [
                    'can_create' => true,
                    'can_edit'   => true,
                    'can_delete' => true,
                    'can_list'   => true,
                    'can_checkout' => true
                ],
                'favorite' => [
                    'can_create' => true,
                    'can_edit'   => true,
                    'can_delete' => true,
                    'can_list'   => true,
                    'can_view'   => true,
                    'can_toggle' => true,
                    'can_add_to_cart' => true,
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
        ],
    ], 200);
}*/



//global function for helper file
/*if (!function_exists('setting')) {
    function setting($key, $default = null) {
        // ডাটাবেস টেবিল থেকে $key অনুযায়ী ভ্যালু তুলে আনার লজিক
        // return DB::table('settings')->where('key', $key)->value('value') ?? $default;
        return $default;
    }
}*/
/*
public function getFeaturesStatus()
{
    return response()->json([
        'success' => true,
        'data'    => [
            'auth' => [
                'can_register'        => (bool) setting('can_sr_register', true),
                'can_login'           => (bool) setting('can_sr_login', true),
                'can_forgot_password' => (bool) setting('can_sr_forgot_password', true),
                'can_reset_password'  => (bool) setting('can_sr_reset_password', true),
            ],
            'order' => [
                'can_create' => (bool) setting('can_sr_order_create', true),
                'can_edit'   => (bool) setting('can_sr_order_edit', false),
            ],
            'cart' => [
                'can_create' => (bool) setting('can_sr_cart_create', true),
            ],
            'favorite' => [
                'can_create' => (bool) setting('can_sr_favorite_create', true),
            ],
            'shipping_address' => [
                'can_add'    => (bool) setting('can_sr_add_shipping_address', true),
                'can_edit'   => (bool) setting('can_sr_edit_shipping_address', true),
                'can_delete' => (bool) setting('can_sr_delete_shipping_address', false),
            ],
            'product_filter_labels' => [
                'brand'    => setting('filter_brand_label', 'Select Brand'),
                'category' => setting('filter_category_label', 'Choose Category'),
                'vendor'   => setting('filter_vendor_label', 'All Vendors'),
            ],
        ],
    ], 200);
}
*/