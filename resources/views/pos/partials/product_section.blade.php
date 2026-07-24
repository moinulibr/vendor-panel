@foreach($items as $item)

@php
    $p_price=getProductDiscount($item);
    $discount_price=$p_price['discount_price'];
    $discount=$p_price['discount'];

@endphp

<div class="product-card d-flex align-items-center">
    <!-- Product Image -->
    <div class="product-image flex-shrink-0">
        <img class="img-fluid object-fit-contain" src="{{ getImage('products',$item->image)}}" alt="img">
    </div>

    <!-- Product Info -->
    <div class="flex-grow-1 ms-2" style="min-width:0;">
        <div class="fw-semibold text-break" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
            {{ $item->name }}
        </div>
        
        @if($discount_price>0)
            <span class="text-red">{{ priceFormate($item->sell_price - $discount_price) }}</span>
            <del class="text-muted">{{ priceFormate($item->sell_price) }}</del>
            <span class="text-muted">({{ number_format($discount->amount,0) }} {{$discount->discount_type =='percentage'?'%':'tk'}} off)</span>
        @else
        
        <small class="text-muted d-block">Price: {{ priceFormate($item->sell_price) }}</small>
        <small class="text-muted">{{ $item->brand_name}}</small>
        @endif
    </div>

    <!-- Button -->
    <button class="btn btn-dark btn-sm ms-2 flex-shrink-0" onclick="productEntry({{ $item->variation_id }})">
        <i class="bi bi-plus"></i> Add to Cart
    </button>
</div>
@endforeach
<div  id="product_paginate"> {{$items->render()}} </div >