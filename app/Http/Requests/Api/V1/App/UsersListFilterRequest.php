<?php

namespace App\Http\Requests\Api\V1\App;

use App\Utils\UserType;
use Illuminate\Foundation\Http\FormRequest;

class UsersListFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $allowedUserTypes = [UserType::DEALER, UserType::GENERAL_APP_CUSTOMER];

        return [
            'q'        => 'nullable|string|max:255',
            'status'   => 'nullable|in:0,1,active,inactive',
            'sort'     => 'nullable|string|in:asc,desc,latest',
            'per_page' => 'nullable|integer|min:1|max:100',
            'user_types'=> 'nullable|array',
            'user_types.*' => 'in:' . implode(',', $allowedUserTypes),
        ];
    }
}