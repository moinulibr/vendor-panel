<div class="table-responsive">
	<table class="table">
		<thead class="thead-light">
			<tr>
				<th>SKU</th>
				<th>Product Name</th>
				<th>Category</th>
				<th>Unit</th>
				<th>InStock</th>
			</tr>
		</thead>
		<tbody>
			@foreach($items as $item)
			<tr>
				
				<td>
					<a>{{ $item->sub_sku}}</a>
				</td>
				<td>
					<div class="d-flex align-items-center">
						<a  class="avatar avatar-md">
							<img src="{{ getImage('products',$item->image)}}" class="img-fluid" alt="img"></a>
						<div class="ms-2">
							<p class="text-dark mb-0"><a>{{ $item->name}}</a></p>
						</div>
					</div>
				</td>
				<td>
					{{ $item->category}}
				</td>
				<td>
					{{ $item->unit_name}}						
				</td>
				<td>{{ $item->stock}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
<p> {{$items->render()}} </p>