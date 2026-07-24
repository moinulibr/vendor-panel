<div class="table-responsive">
	<table class="table ">
		<thead class="thead-light">
			<tr>
			    <th>SL</th>
				<th>Name</th>
				<th class="no-sort">Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach($items as $i=>$item)
			<tr>
			    <td>
			        {{ $i+1}}
			    </td>
				<td>{{ $item->name}}</td>
				<td class="action-table-data">
					<div class="edit-delete-action">
					    @can('permissions.edit')
						<a class="me-2 p-2 btn_modal" href="{{ route('permissions.edit',[$item->id])}}">
							<i class="fa fa-edit"></i>
						</a>
						@endcan
						
						@can('permissions.delete')
						<a  href="{{ route('permissions.destroy',[$item->id])}}" class="delete">
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