<?php
namespace App\Http\Requests\Api\V1\App;

use Illuminate\Foundation\Http\FormRequest;

class AddRetailerShippingAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'retailer_id'    => ['nullable', 'exists:retailers,id'], // If SR passes it
            'title'          => ['required', 'string', 'max:50'],
            'contact_person' => ['nullable', 'string', 'max:100'],
            'contact_mobile' => ['nullable', 'string'],
            'address'        => ['required', 'string'],
            'division'       => ['nullable', 'string'],
            'district'       => ['nullable', 'string'],
            'upazila'        => ['nullable', 'string'],
            'division_id'    => ['nullable', 'integer'],
            'district_id'    => ['nullable', 'integer'],
            'upazila_id'     => ['nullable', 'integer'],
            'is_default'     => ['nullable', 'boolean'],
        ];
    }
}