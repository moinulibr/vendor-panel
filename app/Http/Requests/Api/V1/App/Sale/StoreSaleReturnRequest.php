<?php

namespace App\Http\Requests\Api\V1\App\Sale;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'return_type'      => 'required|string|in:full,partial,exchange',
            'settlement_type'  => 'required|string|in:cash,original_method,store_credit,exchange',
            'reason'           => 'required|string|max:500',
            'items'            => 'required|array|min:1',
            'items.*.sale_item_id' => 'required|integer|exists:sale_items,id',
            'items.*.qty_returned' => 'required|integer|min:1',
            'items.*.condition'   => 'nullable|string|in:good,damaged,expired',
            'items.*.restock'     => 'nullable|boolean',
        ];
    }
}
