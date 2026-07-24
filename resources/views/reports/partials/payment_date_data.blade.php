<div class="table-responsive">
	<table class="table">
		<thead class="thead-light">
			<tr>
			    <th>Next Payment Date</th>
			    <th>Paid Date</th>
				<th>Customer Name</th>
				<th>Customer Mobile</th>
				<th>Last Payment</th>
				<th>Current Note</th>
				<th>Note</th>
				<th>Action</th>
				
			</tr>
		</thead>
		<tbody>
			@foreach($items as $item)
                <tr>
                    <td>{{ dateFormate($item->next_payment_date) }}</td>
                    <td>{{ dateFormate($item->current_date) }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->mobile }}</td>
                    <td>{{ number_format($item->current_reveived_amount, 2) }}</td>
                    <td>{{ $item->current_note }}</td>
                    <td>{{ $item->note }}</td>
                    <td>
                        <a class="btn btn-sm btn_modal" href="{{ route('nextPaymentEdit',[$item->id]) }}">
                            <i class="fa fa-edit"></i>
                            <span>Note </span>
                        </a>
                    </td>
                </tr>
            @endforeach
		</tbody>
	</table>
</div>
<p>{{$items->render()}}</p>