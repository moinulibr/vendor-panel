<div class="table-responsive">
	<table class="table ">
		<thead class="thead-light">
			<tr>
			    <th>SL</th>
				<th>Title</th>
				<th>Image</th>
				<th>Type</th>
				<th>Status</th>
				<th>Created Date</th>
				
				<th class="no-sort">Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach($items as $i=>$item)
			<tr>
			    <td>
			        {{ $i+1}}
			    </td>
				<td>{{ $item->title}}</td>
				<td><img class="object-fit-contain" src="{{ getImage('sliders',$item->image)}}" width="200" alt="img"> </td>
				<td>
					@if($item->type==1)
					Slider
					@elseif($item->type==2)
					Mini Slider
					@elseif($item->type==3)
					Mini Banner
					@endif
				</td>
				
				<td><span class="badge table-badge bg-{{ $item->status == '1' ? 'success':'warning'}} fw-medium fs-10">{{ $item->status == '1' ? 'Active':'De-active'}}</span></td>

				<td>{{ dateFormate($item->created_at)}}</td>
				<td class="action-table-data">
					<div class="edit-delete-action">
						<a class="me-2 p-2 btn_modal" href="{{ route('sliders.edit',[$item->id])}}">
							<i class="fa fa-edit"></i>
						</a>
						<a  href="{{ route('sliders.destroy',[$item->id])}}" class="delete">
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