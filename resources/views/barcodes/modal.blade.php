<div class="print-area">
    @foreach($items as $item)
        <div class="barcode-sticker">
            <div class="border-wrapper">
                @if(!empty($item['business_name']))
                    <div class="product-name" style="font-weight: bold; font-size: 12px;">
                        {{ Str::limit($item['business_name'], 20, '...') }}
                    </div>
                @endif
                
                @if(!empty($item['name']))
                    <div class="product-name" style="font-size: 11px;">
                        {{ Str::limit($item['name'], 22, '') }}
                    </div>
                @endif
                
                @if(!empty($item['price']))
                    <div class="price-tag" style="font-weight: bold;">
                        {{ priceFormate($item['price']) }}
                    </div>
                @endif
                
                <div class="barcode-wrapper" style="margin: 5px 0;">
                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($item['barcode'], 'C128', 1.2, 30) }}" alt="barcode" />
                </div>
                
                <!--<div class="sku-tag" style="font-size: 10px;">{{ $item['barcode'] }}</div>-->
            </div>
        </div>
    @endforeach
</div>