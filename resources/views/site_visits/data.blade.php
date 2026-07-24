<div class="table-responsive">
	<table class="table ">
		<thead class="thead-light">
		    <th>SL</th>
		    <th class="no-sort">Action</th>
			<th>Project Name</th>
			<th>Reference </th>
			<th>Address</th>
			<th>Contact person name</th>
			<th>Mobile number</th>
			<!--<th>Visiting Date</th>-->
			<!--<th>Next Visiting Date</th>-->
			<!--<th>Description</th>-->
			<!--<th>Note</th>-->
			<th>Status</th>
			
		</thead>
		<tbody>
			@foreach($items as $i=>$item)
			<tr>
			    <td>
			        {{ $i+1}}
			    </td>
			    <td class="action-table-data">
					<div class="dropdown action-dropdown-wrap">
                        <button class="btn btn-sm btn-icon"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                            <i class="fa fa-ellipsis-h"></i>
                        </button>
                
                        <ul class="dropdown-menu dropdown-menu-end action-dropdown">
                            <li>
                                <a class="dropdown-item btn_modal" href="{{ route('site_visits.show',[$item->id])}}">
                                    <i class="fa fa-eye"></i>
                                    <span>View</span>
                                </a>
                            </li>
                            
                            @can('site_visits.update')
                            <li>
                                <a class="dropdown-item btn_modal" href="{{ route('site_visits.edit',[$item->id])}}">
                                    <i class="fa fa-edit"></i>
                                    <span>Edit</span>
                                </a>
                            </li>
                            @endcan
                            
                           @can('site_visits.delete')
                                <li>
                                    <a  href="{{ route('site_visits.destroy',[$item->id])}}" class="dropdown-item text-danger delete">
                                        <i class="fa fa-trash"></i>
                                        <span>Delete</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </div>
				</td>
				<td>{{ $item->project_name}}</td>
				<td>{{ $item->ref_no}}</td>
				<td>{{ $item->address}}</td>
				<td>{{ $item->contact_person}}</td>
				<td>{{ $item->mobile}}</td>
				
				<!--<td>{{ dateFormate($item->visiting_date)}}</td>-->
				<!--<td>{{ dateFormate($item->next_visiting_date)}}</td>-->
				<!--<td>{{ \Illuminate\Support\Str::limit($item->description, 50) }}</td>-->
    <!--            <td>{{ \Illuminate\Support\Str::limit($item->note, 50) }}</td>-->
				<td> 
				    @if($item->status)
        		        <span class="badge table-badge bg-success fw-medium fs-10">Active</span>
        		    @else
        		        <span class="badge table-badge bg-warning fw-medium fs-10">De-Active</span>
        		    @endif
        		</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
<p> {{$items->render()}} </p>