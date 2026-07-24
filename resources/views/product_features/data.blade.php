<div class="table-responsive">
	<table class="table ">
		<thead class="thead-light">
			<tr>
			    <th>SL</th>
				<th>Name</th>
				<th>Status</th>
				<th class="no-sort text-center">Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach($items as $i=>$item)
			<tr>
			    <td>
			        {{ $i+1}}
			    </td>
				<td> {{ $item->name }} </td>
				<td>
				    @if($item->status)
    			        <span class="badge table-badge bg-success fw-medium fs-10">Active</span>
    			    @else
    			        <span class="badge table-badge bg-warning fw-medium fs-10">De-Active</span>
    			    @endif
				</td>
				<td class="action-table-data">
					<div class="edit-delete-action">
					    @can('top_menus.update')
						<a class="me-2 p-2 btn_modal" href="{{ route('product_features.edit',[$item->id])}}">
							<i class="fa fa-edit"></i>
						</a>
						@endcan
						
						@can('top_menus.delete')
						<a  href="{{ route('product_features.destroy',[$item->id])}}" class="delete">
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