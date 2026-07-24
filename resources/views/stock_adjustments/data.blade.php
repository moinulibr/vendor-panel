<div class="table-responsive">
	<table class="table ">
		<thead class="thead-light">
			<tr>
			    <th>SL</th>
				<th>Reference</th>
				<th>User Name</th>
				<th>Location Name</th>
				<th>Date</th>
				<th>Total Amount</th>
				<th> Note </th>
				<th class="no-sort">Action</th>
										
			</tr>
		</thead>
		<tbody>
			@foreach($items as $i=>$item)
			<tr>
			    <td>
			        {{ $i+1}}
			    </td>
				<td>{{ $item->invoice_no}}</td>
				<td>{{ $item->user->name??''}}</td>
				<td>{{ $item->location->name??''}}</td>
				<td>{{ dateFormate($item->transaction_date)}}</td>
				<td>{{ $item->final_amount}}</td>
				<td>{{ $item->note}}</td>
				
				<td class="action-table-data">
					<div class="edit-delete-action">
						<a class="me-2 edit-icon p-2 btn_modal" href="{{ route('stock_adjustments.show',[$item->id])}}">
							<i class="fa fa-eye"></i>
						</a>

						<!--<a class="me-2 p-2 btn_modal" href="{{ route('stock_adjustments.edit',[$item->id])}}">-->
						<!--	<i class="fa fa-edit"></i>-->
						<!--</a>-->
						
						@can('stock_adjustments.delete')
						<a  href="{{ route('stock_adjustments.destroy',[$item->id])}}" class="delete">
							<i class="fa fa-trash"></i>
						</a>
						@endcan
					</div>
					
				</td>
			</tr>

			@endforeach
		</tbody>
	</table>
</div>
<p> {{$items->render()}} </p>
