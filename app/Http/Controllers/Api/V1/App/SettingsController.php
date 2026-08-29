<?php

namespace App\Http\Controllers\Api\V1\App;

use Illuminate\Http\JsonResponse;

class SettingsController extends BaseApiController
{

    public function featureDelivery(): JsonResponse
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


    /**
     * Get features enable/disable status and labels.
     *
     * @return JsonResponse
     */
    public function getFeaturesStatus(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => [
                'sr_user' => [
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
    }
}


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