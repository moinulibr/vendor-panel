<div class="table-responsive">
	<table class="table">
		<thead class="thead-light">
			<tr>
				<th>Sale Invoice </th>
				<th>SKU</th>
				<th>Product Name</th>
				<th>Brand</th>
				<th>Category</th>
				<th>Purchase Qty</th>
				<th>Purchase Amount</th>
				<th>Subtotal</th>
			</tr>
		</thead>
		<tbody>
			@foreach($items as $item)
                <tr>
                    <td>{{ $item->invoice_no }}</td>
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
                    <td>{{ $item->purchase_qty }}</td>
                    <td>{{ number_format($item->price, 2) }}</td>
                    <td>{{ number_format($item->purchase_amount, 2) }}</td>
                </tr>
            @endforeach
		</tbody>
	</table>
</div>
<p>{{$items->render()}}</p>