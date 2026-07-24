<div class="table-responsive">
	<table class="table ">
		<thead class="thead-light">
			<tr>
			    <th>SL</th>
				<th class="no-sort">
					<label class="checkboxs">
						<input type="checkbox" id="select-all">
						<span class="checkmarks"></span> 
					</label>
				</th>
				<th>Category</th>
				<th>Name Bangla</th>
				<th>Parent Category</th>
				<th> Top </th>
				<th> Menu </th>
				<th> Bottom </th>
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
				<td>
					<label class="checkboxs">
						<input type="checkbox" value="{{ $item->id}}" class="checkbox">
						<span class="checkmarks"></span>
					</label>
				</td>
				<td>
					<div class="d-flex align-items-center">
						<a href="javascript:void(0);" class="avatar avatar-md bg-light-900 p-1 me-2">
							<img class="object-fit-contain" src="{{ getImage('categories',$item->image)}}" alt="img">
						</a>
						<a href="javascript:void(0);">{{ $item->name}}</a>
					</div>
				</td>
				<td>{{ $item->bd_name}}</td>
				<td>{{ $item->parent->name ??''}}</td>
				<td>{{ $item->is_top == '1' ? 'Active':'De-active'}}</td>
				<td>{{ $item->is_menu == '1' ? 'Active':'De-active'}}</td>
				<td>{{ $item->is_home == '1' ? 'Active':'De-active'}}</td>

				<td>
				    @if($item->status)
				    <span class="badge table-badge bg-success fw-medium fs-10">Active</span>
				    @else
				    <span class="badge table-badge bg-warning fw-medium fs-10">De-Active</span>
				    @endif
				</td>
				
				<td class="action-table-data">
                    <div class="dropdown action-dropdown-wrap">
                        <button class="btn btn-sm btn-icon"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                            <i class="fa fa-ellipsis-h"></i>
                        </button>
                
                        <ul class="dropdown-menu dropdown-menu-end action-dropdown">
                            @can('categories.edit')
                            <li>
                                <a class="dropdown-item btn_modal" href="{{ route('categories.edit',[$item->id])}}">
                                    <i class="fa fa-edit"></i>
                                    <span>Edit</span>
                                </a>
                            </li>
                            @endcan
                            
                            @can('categories.delete')
                            <li>
                                <a class="dropdown-item text-danger delete" href="{{ route('categories.destroy',[$item->id])}}">
                                    <i class="fa fa-trash"></i>
                                    <span>Delete</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
<p> {{$items->render()}} </p>