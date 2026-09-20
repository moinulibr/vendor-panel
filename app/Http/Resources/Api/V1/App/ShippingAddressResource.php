<?php

namespace App\Http\Resources\Api\V1\App;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShippingAddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'user_id'           => $this->user_id,
            'user_detail_id'    => $this->user_detail_id,
            'title'             => $this->title,
            'contact_person'    => $this->contact_person,
            'contact_mobile'    => $this->contact_mobile,
            'address'           => $this->address,
            'division'          => $this->division,
            'district'          => $this->district,
            'area'              => $this->area,
            'upazila'           => $this->upazila,
            'division_id'       => $this->division_id,
            'district_id'       => $this->district_id,
            'upazila_id'        => $this->upazila_id,
            'status'            => $this->delete_at == null ? "active" : 'deleted',
            'is_default'        => $this->is_default == 1 ? true : false,
            'created_at'        => $this->created_at?->toIso8601String(),
        ];
    }
}