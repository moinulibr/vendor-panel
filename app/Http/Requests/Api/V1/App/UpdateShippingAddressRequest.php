<?php
namespace App\Http\Requests\Api\V1\App;

use App\Utils\UserType;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShippingAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isNotRetailer = auth()->check() && (int) auth()->user()->user_type == UserType::SR;
        
        return [
            'title'          => ['required', 'string', 'max:50'],
            'contact_person' => ['nullable', 'string', 'max:100'],
            'contact_mobile' => ['nullable', 'string'],
            'address'        => ['required', 'string'],
            'division'       => ['nullable', 'string'],
            'district'       => ['nullable', 'string'],
            'area'           => ['nullable', 'string', 'max:200'],
            'upazila'        => ['nullable', 'string'],
            'division_id'    => ['nullable', 'integer'],
            'district_id'    => ['nullable', 'integer'],
            'upazila_id'     => ['nullable', 'integer'],
            'is_default'     => ['nullable', 'boolean'],
            'user_detail_id'  => [
                $isNotRetailer ? 'required' : 'nullable',
                'integer',
                'exists:user_details,id',
            ],
            'user_base_id'  => [
                $isNotRetailer ? 'required' : 'nullable',
                'integer',
                'exists:users,id',
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'user_base_id.required' => 'The user base id field is required when you are acting as an SR  user.',
            'user_detail_id.required' => 'The user detail id field is required when you are acting as an SR  user.',
        ];
    }
}