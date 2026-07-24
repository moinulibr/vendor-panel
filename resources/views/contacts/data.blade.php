<div class="table-responsive">
	<table class="table ">
		<thead class="thead-light">
			<tr>
			    <th>SL</th>
			    <th class="no-sort">Action</th>
				<th>Name</th>
				<th>Email</th>
				<th>Phone</th>
				<th>Address</th>
				<th>Total Sell</th>
				<th>Total Sell Paid </th>
				<th>Total Sell Due</th>
				<th>Status</th>
				
			</tr>
		</thead>
		<tbody>
			@foreach($items as $i=>$item)
			
			@php
			    $due=$item->total_sell - $item->total_sell_paid;
			@endphp
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
                                <a class="dropdown-item" href="{{ route('contacts.show',[$item->id])}}">
                                    <i class="fa fa-eye"></i>
                                    <span>View</span>
                                </a>
                            </li>
                            @can('customers.edit')
                            <li>
                                <a class="dropdown-item btn_modal" href="{{ route('customers.edit',[$item->id])}}">
                                    <i class="fa fa-edit"></i>
                                    <span>Edit</span>
                                </a>
                            </li>
                            @endcan
                            
                            @can('customers.due_payment')
                            @if($due>0)
                            <li>
                                <a class="dropdown-item btn_modal" href="{{ route('getContactDue',[$item->id])}}">
                                    <i class="fa fa-edit"></i>
                                    <span>Payment</span>
                                </a>
                            </li>
                            @endif
                            @endcan
                            
                            
                            @can('customers.delete')
                            <li>
                                <a class="dropdown-item text-danger delete" href="{{ route('customers.destroy',[$item->id])}}">
                                    <i class="fa fa-trash"></i>
                                    <span>Delete</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </td>
				<td>
					<div class="d-flex align-items-center">
						<a href="javascript:void(0);" class="avatar avatar-md bg-light-900 p-1 me-2">
							<img class="object-fit-contain" src="{{ getImage('contacts',$item->image)}}" alt="img">
						</a>
						<a href="javascript:void(0);">{{ $item->name}}</a>
					</div>
				</td>
				<td>{{ $item->email}}</td>
				<td>{{ $item->mobile}}</td>
				<td>{{ $item->address}}</td>
				<td>{{ priceFormate($item->total_sell)}}</td>
				<td>{{ priceFormate($item->total_sell_paid)}}</td>
				<td>{{ priceFormate($due)}}</td>
				<td><span class="badge table-badge bg-{{ $item->status=='1' ?'success':'warning'}} fw-medium fs-10">{{ $item->status=='1' ?'Active':'De-active'}}</span></td>

			</tr>
			@endforeach
		</tbody>
	</table>
</div>
<p> {{$items->render()}} </p>