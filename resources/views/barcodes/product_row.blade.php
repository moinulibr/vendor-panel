<tr>
    <td>
        <div class="d-flex align-items-center">
            <a href="javascript:void(0);" class="avatar avatar-md me-2">
                <img src="{{ getImage('images',$item->product->sku)}}" alt="product">
            </a>
            <a href="javascript:void(0);">{{ $item->product->name}}</a>
        </div>												
    </td>
    <td>{{ $item->product->sku}}</td>
    <td>
        <div class="product-quantity border-secondary-transparent">
			                                                      
            <input type="hidden" name="variation_id[]" class="quntity-input" value="{{ $item->id}}">
            <input type="number" name="quantity[]" class="quntity-input" value="1">
           
        </div>
    </td>
    <td class="action-table-data">
        <i data-feather="trash-2" class="feather-trash-2"></i>
    </td>
</tr>