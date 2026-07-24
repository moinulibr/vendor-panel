<div class="table-responsive">
	<table class="table">
		<thead class="thead-light">
			<tr>
				<th>Category</th>
				<th>Amount</th>
				<th> Paid Amount</th>
			</tr>
		</thead>
		<tbody>
		    @foreach($items as $item)
			<tr>
				<td>{{ $item->category->name}}</td>
				<td>{{ priceFormate($item->total_amount) }}</td>
				<td>{{ priceFormate($item->total_paid ??0) }}</td>
				
			</tr>
			@endforeach
		</tbody>
	</table>
</div>