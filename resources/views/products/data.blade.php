<div class="table-responsive">
	<table class="table ">
		<thead class="thead-light">
			<tr>
			    <th class="no-sort">SL</th>
			    <th class="no-sort">Action</th>
				<th class="no-sort">
					<label class="checkboxs">
						<input type="checkbox" id="select-all">
						<span class="checkmarks"></span>
					</label>
				</th>
				<th>Product</th>
				<th>SKU</th>
				<th>Type</th>
				<th>Category</th>
				<th>Brand</th>
				<th>Purchse Price</th>
				<th>MRP</th>
				<th>Unit</th>
				<th>Qty</th>
				<th>Ecommerce</th>
				<th>Featured</th>
				<th>Trending</th>
				<th> Vendor </th>
				<th class="no-sort">Status</th>
				<th> Created Date </th>
				
			</tr>
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
                                <a class="dropdown-item btn_modal" href="{{ route('products.show',[$item->id]) }}">
                                    <i class="fa fa-eye"></i>
                                    <span>View</span>
                                </a>
                            </li>
                            
                            @can('products.edit')
                            <li>
                                <a class="dropdown-item btn_modal" href="{{ route('products.edit',[$item->id]) }}">
                                    <i class="fa fa-edit"></i>
                                    <span>Edit</span>
                                </a>
                            </li>
                            @endcan
                            
                            @can('products.delete')
                            <li>
                                <a class="dropdown-item text-danger delete" href="{{ route('products.destroy',[$item->id]) }}">
                                    <i class="fa fa-trash"></i>
                                    <span>Delete</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </td>
                
				<td>
					<label class="checkboxs">
						<input type="checkbox" value="{{ $item->id}}" class="checkbox">
						<span class="checkmarks"></span>
					</label>
				</td>
				<td>
					<div class="d-flex align-items-center">
						<a href="{{ route('products.show',[$item->id]) }}" class="btn_modal avatar avatar-md bg-light-900 p-1 me-2">
							<img class="object-fit-contain" src="{{ getImage('products',$item->image)}}" alt="img">
						</a>
						<a href="javascript:void(0);">{{ $item->name}}</a>
					</div>
				</td>
				<td>{{ $item->sku}}</td>
				<td>{{ $item->type}}</td>
				<td>{{ $item->category->name ?? ''}}</td>
				<td>{{ $item->brand->name ?? ''}}</td>
				<td>{{ priceFormate($item->purchase_price)}}</td>
				<td>{{ priceFormate($item->sell_price)}}</td>
				<td>{{ $item->unit->name ?? ''}}</td>
				<td>{{ $item->stock ?? 0}}</td>
				<td>{{ $item->is_ecom == '1' ? 'Active':'De-active'}}</td>
				<td>{{ $item->is_feature == '1' ? 'Active':'De-active'}}</td>
				<td>{{ $item->is_reco == '1' ? 'Active':'De-active'}}</td>
				<td>{{ $item->user->name ??''}}</td>
				<td>
				    @if($item->status)
				    <span class="badge table-badge bg-success fw-medium fs-10">Active</span>
				    @else
				    <span class="badge table-badge bg-warning fw-medium fs-10">De-Active</span>
				    @endif
				    
				</td>
				
              	<td>{{ $item->created_at->format('d-m-Y h:i:s A') }}</td>
				
			</tr>

			@endforeach
		</tbody>
	</table>
</div>
<p> {{$items->render()}} </p>
