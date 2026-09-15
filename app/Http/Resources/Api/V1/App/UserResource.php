<?php

namespace App\Http\Resources\Api\V1\App;

use App\Utils\UserType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $comments = [];
        foreach (UserType::list() as $value => $label) {
            $comments[] = strtoupper(str_replace([' ', '(', ')'], ['_', '', ''], $label)) . " = {$value}";
        }
        $commentString = implode('; ', $comments);

        $isSwitchingAccount = $this->user_type == UserType::GENERAL_APP_CUSTOMER ? true : false;

        return [
            'id'          => $this->id,
            'user_id'     => $this->id,
            'name'        => $this->name,
            'email'       => $this->email,
            'mobile'      => $this->mobile,
            'status'      => (bool) $this->status == 1 ? "active" : 'inactive',
            'user_type'   => $this->user_type,
            'user_type_label' => UserType::getLabel($this->user_type),
            'user_account'=> [
                'isEnableToSwitchingAccount' => $isSwitchingAccount,
                'switchingFrom' => $isSwitchingAccount ? $this->user_type : false,
                'switchingTo' => $isSwitchingAccount ? ($this->user_type == UserType::GENERAL_APP_CUSTOMER ? UserType::DEALER : UserType::GENERAL_APP_CUSTOMER) : false,
                'isCustomFormNeedToSwitching' => $this->user_type == UserType::GENERAL_APP_CUSTOMER ? true : false
            ],
            'access_type' => $this->access_type,

            'profile_picture' => $this->image
                ? (filter_var($this->image, FILTER_VALIDATE_URL) ? $this->image : asset('storage/' . $this->image))
                : asset('image/default-avatar.png'), //asset(Storage::url($this->image)) same result

            'retailer' => $this->whenLoaded('retailer', function () {
                return [
                    'retailer_id'   => $this->retailer->id,
                    'retailer_user_id' => $this->retailer->user_id,
                    'shop_name'     => $this->retailer->shop_name,
                    'trade_license' => $this->retailer->trade_license,
                    'license_image' => $this->retailer->license_image
                        ? asset(Storage::url($this->retailer->license_image)) : null,
                    'address'       => $this->retailer->address,
                    'status'        => $this->retailer->status == 1 || $this->retailer->status == "active" ? "active" : "inactive",
                ];
            }),
            'created_at'  => $this->created_at?->toIso8601String(),
            'note'        => 'user typies ->' . $commentString,
            'user type modified note' => 'We considar this Retailer User is as a Dealer User',
        ];
    }
}