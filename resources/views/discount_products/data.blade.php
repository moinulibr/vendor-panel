<div class="table-responsive">
	<table class="table ">
		<thead class="thead-light">
		    <th>SL</th>
		    <th>Action</th>
			<th>Name</th>
			<!--<th>Category</th>-->
			<!--<th>Brand</th>-->
			<th>Discount Type</th>
			<th>Discount Amount</th>
			<th>Vendor</th>
			<th>Priority</th>
			<th>Status</th>
			<th width="300">Products</th>
		</thead>
		<tbody>
			@foreach($items as $i=>$item)
			
			@php
			    $selectedProducts = $item->discount_prodcuts->map(function($p){
                    return ['sku' => $p->id, 'name' => $p->name];
                });
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
                                <a class="dropdown-item btn_modal" href="{{ route('discounts.show',[$item->id])}}">
                                    <i class="fa fa-eye"></i>
                                    <span>View</span>
                                </a>
                            </li>
                            
                            @can('discounts.edit')
                            
                            <li>
                                <a class="dropdown-item btn_modal" href="{{ route('discount_products.edit',[$item->id])}}">
                                    <i class="fa fa-edit"></i>
                                    <span>Edit</span>
                                </a>
                            </li>
                            @endcan
                            
                            @can('discounts.delete')
                            <li>
                                <a class="dropdown-item text-danger delete" href="{{ route('discounts.destroy',[$item->id])}}">
                                    <i class="fa fa-trash"></i>
                                    <span>Delete</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </td>
				<td>{{ $item->title}}</td>
				<!--<td>{{ $item->category->name ??''}}</td>-->
				<!--<td>{{ $item->brand->name ??''}}</td>-->
				<td>{{ $item->discount_type}}</td>
				<td>{{ $item->amount}}</td>
				<td>{{ $item->user->name ?? 'N/A' }}</td>
				{{-- <td>{{ $item->priority  - getDiscountPriorityNameById((int)$item->priority) }}</td> --}}
				<td>{{ getDiscountPriorityNameById((int)$item->priority) }}</td>
				<td>
				    
				    @if($item->status)
				    <span class="badge table-badge bg-success fw-medium fs-10">Active</span>
				    @else
				    <span class="badge table-badge bg-warning fw-medium fs-10">De-Active</span>
				    @endif
				    
				</td>
				<td>
				    @foreach($selectedProducts as $p)
				    <a class="btn btn-sm btn-primary">{{ $p['name']}}</a>
				    @endforeach
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
<p> {{$items->render()}} </p>