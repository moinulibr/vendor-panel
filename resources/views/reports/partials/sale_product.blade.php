<div class="table-responsive">
	<table class="table">
		<thead class="thead-light">
			<tr>
				<th>SKU</th>
				<th>Product Name</th>
				<th>Brand</th>
				<th>Category</th>
				<th>Sold Qty</th>
				<th>Sold Amount</th>
				<th>Instock Qty</th>
			</tr>
		</thead>
		<tbody>
			@foreach($items as $item)
                <tr>
                    <td>{{ $item->sku }}</td>
                    <td>
					<div class="d-flex align-items-center">
						<a class="avatar avatar-md"><img src="{{ getImage('products',$item->image)}}" class="img-fluid" alt="img"></a>
						<div class="ms-2">
							<p class="text-dark mb-0">{{ $item->name ?? '-' }}</p>
						</div>
					</div>
				</td>
                    <td>{{ $item->brand_name ?? '-' }}</td>
                    <td>{{ $item->category_name ?? '-' }}</td>
                    <td>{{ $item->sold_qty }}</td>
                    <td>{{ number_format($item->sold_amount, 2) }}</td>
                    <td>
                        @if($item->stock_manage)
                            {{ $item->stock ?? 0 }}
                        @else
                        N\A
                        @endif
                        
                    </td>
                </tr>
            @endforeach
		</tbody>
	</table>
</div>
<p>{{$items->render()}}</p>