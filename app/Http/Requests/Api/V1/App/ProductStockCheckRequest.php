<?php

namespace App\Http\Requests\Api\V1\App;

use App\Utils\UserType;
use Illuminate\Foundation\Http\FormRequest;

class ProductStockCheckRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isSrUser = auth()->check() && auth()->user()->user_type == UserType::SR;

        return [
            'user_base_id'          => [
                $isSrUser ? 'required' : 'nullable',
                'integer'
            ],
        ];
    }
}