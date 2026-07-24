<div class="table-responsive">
	<table class="table">
		<thead class="thead-light">
			<tr>
			    <th>Date</th>
				<th>Sale Invoice </th>
				<th>Name</th>
				<th>Mobile</th>
				<th>Method</th>
				<th>Amount</th>
				
			</tr>
		</thead>
		<tbody>
			@foreach($items as $item)
                <tr>
                    <td>{{ $item->paid_on }}</td>
                    <td>{{ $item->invoice_no }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->mobile }}</td>
                    <td>{{ $item->method }}</td>
                    <td>{{ number_format($item->amount, 2) }}</td>
                </tr>
            @endforeach
		</tbody>
	</table>
</div>
<p>{{$items->render()}}</p>