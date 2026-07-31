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
            'mobile'       => $this->mobile,
            //'user_type'   => $this->user_type,
            'status'      => (bool) $this->status == 1 ? "active" : 'inactive',
            'access_type' => $this->access_type,

            'profile_picture' => $this->image
                ? (filter_var($this->image, FILTER_VALIDATE_URL)
                    ? $this->image
                    : asset('storage/' . $this->image))
                : asset('image/default-avatar.png'),

            'retailer'    => $this->whenLoaded('retailer', function () {
                return [
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