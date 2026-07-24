

@php
    $stock_max=$item->stock_manage==1 ?  $item->stock:10000;
@endphp

<div class="product-item py-2 border-bottom">
    <div class="row align-items-center g-3">
        <!-- Product Image -->
        <div class="col-3 col-md-1">
            <div class="product-image text-center">
                <img class="img-fluid object-fit-contain" 
                     src="{{ $item->product_image ? asset('products/'.$item->product_image) : asset('images/no_found.png') }}" 
                     alt="img">
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-9 col-md-4" style="padding-left: 20px;">
            <h6 class="mb-1 fw-semibold">{{ $item->product_name }}
                
                @if($item->type=='variable')
                <br>({{ $item->name }})
                @endif
            </h6>
            <small class="d-block text-muted">Unit Price: {{ $item->price }}</small>
            
            @if($item->discount>0 && $item->discount_object)
            <del class="d-block text-muted">Old Price: {{ $item->old_price }}
                
                ({{ number_format($item->discount_object->amount,0) }} {{$item->discount_object->discount_type =='percentage'?'%':'tk'}} off)
                
            </del>
            @endif
            
            
            @if($item->stock_manage==1)
            <small class="d-block text-muted">In Stock: {{ $item->stock }}</small>
            @endif
        </div>

        <!-- Price + Quantity + Total -->
        <div class="col-12 col-md-6">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                
                <!-- Editable Price -->
                <div class="d-flex align-items-center gap-1">
                    <small class="text-muted">Price:</small>
                    <input type="text" 
                           class="form-control form-control-sm text-center unit_price" 
                           name="unit_price[]" 
                           value="{{ $item->price }}">
                    
                    <input type="hidden" name="old_price[]" value="{{ $item->old_price }}">
                    <input type="hidden" class="discount" name="discount[]" value="{{ $item->discount }}">
                    <input type="hidden" name="discount_id[]" value="{{ $item->discount_id }}">
                           
                </div>

                <!-- Quantity Controls -->
                <div class="quantity-controls d-flex align-items-center gap-2">
                    <button type="button" class="no-print quantity-btn decrease btn btn-outline-secondary btn-sm" data-type="minus">
                        <i class="ti ti-minus"></i>
                    </button>

                    

                    @if(isset($exist))
                        <span class="fw-bold tquantity">{{$item->ordered_qty }}</span>
                        <input type="hidden" name="line_id[]" value="{{ $item->line_id }}">
                        <input type="hidden" class="form-control text-center quantity" 
                               name="quantity[]" value="{{ $item->ordered_qty }}" max="{{ $stock_max }}">
                    @else
                        <span class="fw-bold tquantity">1</span>
                        <input type="hidden" class="form-control text-center quantity" 
                               name="quantity[]" value="1" max="{{ $stock_max }}">
                    @endif

                    <input type="hidden" name="product_id[]" value="{{ $item->product_id }}">
                    <input type="hidden" name="variation_id[]" value="{{ $item->id }}">

                    <button type="button" class="no-print uantity-btn increase btn btn-outline-secondary btn-sm" data-type="plus">
                        <i class="ti ti-plus"></i>
                    </button>
                </div>

                <!-- Total Price -->
                <div class="d-flex align-items-center gap-1 total-highlight rounded">
                    <small class="text-muted">Total Price:</small>
                    <small class="fw-bold text-success">
                        Tk <span class="row_total">{{ $item->price }}</span>
                    </small>
                </div>

            </div>
        </div>

        <!-- Remove Button -->
        <div class="col-12 col-md-1 text-end no-print mt-0 mb-2">
            <button type="button" class="btn btn-outline-danger btn-sm remove_cart_row" data-variation_id="{{ $item->id}}">
                <i class="ti ti-trash"></i>
            </button>
        </div>
    </div>
</div>
