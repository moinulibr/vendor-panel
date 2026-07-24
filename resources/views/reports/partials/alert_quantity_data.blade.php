<div class="table-responsive">
	<table class="table datatable">
		<thead class="thead-light">
			<tr>
				<th>SKU</th>
				<th>Product Name</th>
				<th>Alert Quantity</th>
				<th>Total Quantity</th>
				
			</tr>
		</thead>
		<tbody>
		    
		    @foreach($items as $item)
			<tr>
				<td><a href="{{ route('products.show',[$item->id]) }}">{{ $item->sku}}</a></td>
				<td>
					<div class="d-flex align-items-center">
						<a href="{{ route('products.show',[$item->id]) }}" class="avatar avatar-md"><img src="{{ getImage('products',$item->image)}}" class="img-fluid" alt="img"></a>
						<div class="ms-2">
							<p class="text-dark mb-0"><a href="#">  {{ $item->name}} </a></p>
						</div>
					</div>
				</td>
				<td> {{ $item->stock_alert}} </td>
				<td> {{ $item->stock}} </td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>