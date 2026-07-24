<div class="table-responsive">
	<table class="table ">
		<thead class="thead-light">
			<tr>
			    <th>SL</th>
				<th>Title</th>
				<th>Description</th>
				<th>Amount</th>
				<th>Status</th>
				<th class="no-sort"></th>
			</tr>
		</thead>
		<tbody>
			@foreach($items as $i=>$item)
			<tr>
			    <td>
			        {{ $i+1}}
			    </td>
				<td>{{ $item->title }}</td>
				<td>{{ $item->description }}</td>
				<td>{{ $item->amount }}</td>
				
				<td>
				    @if($item->status)
				    <span class="badge table-badge bg-success fw-medium fs-10">Active</span>
				    @else
				    <span class="badge table-badge bg-warning fw-medium fs-10">De-Active</span>
				    @endif
				</td>
				
				<td class="action-table-data">
					<div class="edit-delete-action">
					    @can('delivery_charges.edit')
						<a class="me-2 p-2 btn_modal" href="{{ route('delivery_charges.edit',[$item->id])}}">
							<i class="fa fa-edit"></i>
						</a>
						@endcan
						
						@can('delivery_charges.delete')
						<a  href="{{ route('delivery_charges.destroy',[$item->id])}}" class="delete">
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