<?php

namespace App\Http\Resources\Api\V1\App;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'email'       => $this->email,
            'mobile'      => $this->mobile,
            'status'      => (bool) $this->status == 1 ? "active" : 'inactive',
            'user_type'   => $this->user_type,
            'note'        => 'user type -> ADMIN = 1; STAFF = 2; VENDOR = 3; SR = 4; RETAILER = 5; ECOMMERCE_CUSTOMER = 6; POS_CUSTOMER = 7; RESELLER = 8; DELIVERY_MAN = 9; PLUMBER = 10; GUEST = 11; OTHERS = 12;',
            'access_type' => $this->access_type,

            'profile_picture' => $this->image
                ? (filter_var($this->image, FILTER_VALIDATE_URL)
                    ? $this->image
                    : asset('storage/' . $this->image))
                : asset('image/default-avatar.png'),

            'retailer'    => $this->whenLoaded('retailer', function () {
                return [
                    'retailer_id'   => $this->retailer->id,
                    'retailer_user_id' => $this->retailer->user_id,
                    'shop_name'     => $this->retailer->shop_name,
                    'trade_license' => $this->retailer->trade_license,
                    'address'       => $this->retailer->address,
                    'status'        => $this->retailer->status,
                ];
            }),
            'created_at'  => $this->created_at?->toIso8601String(),

        ];
    }
}