<div class="table-responsive">
	<table class="table ">
		<thead class="thead-light">
		    <th>SL</th>
			<th>Title</th>
			<th>Code</th>
			<th>Description</th>
			<th>Type</th>
			<th>Discount</th>
			<th>Valid</th>
			<th>End</th>
			<th>Status</th>
			<th class="no-sort">Action</th>
		</thead>
		<tbody>
			@foreach($items as $i=>$item)
			<tr>
			    <td>
			        {{ $i+1}}
			    </td>
				<td>{{ $item->title}}</td>
				<td>{{ $item->code}}</td>
				<td>{{ $item->note}}</td>
				<td>{{ $item->discount_type}}</td>
				<td>{{ $item->amount}}</td>
				<td>{{ dateFormate($item->start)}}</td>
				<td>{{ dateFormate($item->end)}}</td>
				
				<td>
				    @if($item->status)
				    <span class="badge table-badge bg-success fw-medium fs-10">Active</span>
				    @else
				    <span class="badge table-badge bg-warning fw-medium fs-10">De-Active</span>
				    @endif
				</td>
				
				<td class="action-table-data">
					<div class="edit-delete-action">
					    @can('coupons.edit')
						<a class="me-2 p-2 btn_modal" href="{{ route('coupons.edit',[$item->id])}}">
							<i class="fa fa-edit"></i>
						</a>
						@endcan
						
						@can('coupons.delete')
						<a  href="{{ route('coupons.destroy',[$item->id])}}" class="delete">
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