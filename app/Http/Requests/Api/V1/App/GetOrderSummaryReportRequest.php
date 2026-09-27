<?php

namespace App\Http\Requests\Api\V1\App;

use App\Utils\UserType;
use Illuminate\Foundation\Http\FormRequest;

class GetOrderSummaryReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isNotRetailer = auth()->check() && (int) auth()->user()->user_type !== UserType::DEALER;

        return [
            'user_base_id' => [
                $isNotRetailer ? 'required' : 'nullable',
                'integer',
                'exists:users,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'user_base_id.required' => 'The user_base_id field is required when acting as an SR or Admin user.',
            'user_base_id.exists'   => 'The specified user_base_id does not exist.',
        ];
    }
}
