<div class="table-responsive">
	<table class="table ">
		<thead class="thead-light">
			<tr>
			    <th>SL</th>
				<th>Title</th>
				<th>Description</th>
				<th>Status</th>
				<th class="no-sort">Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach($items as $i=>$item)
			<tr>
			    <td>
			        {{ $i+1}}
			    </td>
				<td>{{ $item->title }}</td>
				<td>{{ \Illuminate\Support\Str::limit(strip_tags($item->description), 60) }}</td>
				<td>
				    @if($item->status)
    			    <span class="badge table-badge bg-success fw-medium fs-10">Active</span>
    			    @else
    			    <span class="badge table-badge bg-warning fw-medium fs-10">De-Active</span>
    			    @endif
				</td>
				<td class="action-table-data">
					<div class="edit-delete-action">
						<a class="me-2 p-2 btn_modal" href="{{ route('faq_pages.edit',[$item->id])}}">
							<i class="fa fa-edit"></i>
						</a>
						<a  href="{{ route('faq_pages.destroy',[$item->id])}}" class="delete">
							<i class="fa fa-trash"></i>
						</a>
					</div>
					
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
<p> {{$items->render()}} </p>