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
            'phone'       => $this->mobile,
            'user_type'   => $this->user_type,
            'employee_id' => $this->employee_id,
            'shop_name'   => $this->shop_name,
            'address'     => $this->address,
            'is_active'   => (bool) $this->is_active,
        ];
    }
}