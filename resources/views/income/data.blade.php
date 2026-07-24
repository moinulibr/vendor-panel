<div class="table-responsive">
	<table class="table ">
		<thead class="thead-light">
			<tr>
			    <th>SL</th>
				<th>User Name</th>
				<th>Reference</th>
				<th>Date</th>
				<th>Total</th>
				<th>Paid</th>
				<th>Due</th>
				<th>Payment Status</th>
				<th class="no-sort">Action</th>
										
			</tr>
		</thead>
		<tbody>
			@foreach($items as $i=>$item)
			<tr>
			    <td>
			        {{ $i+1}}
			    </td>
				<td>{{ $item->user->name ??''}}</td>
				<td>{{ $item->invoice_no}}</td>
				<td>{{ dateFormate($item->transaction_date)}}</td>
				<td>{{ $item->final_amount}}</td>
				<td>{{ $item->final_amount}}</td>
				<td>{{ $item->final_amount}}</td>
				<td>{{ $item->payment_status}}</td>
				
				<td class="action-table-data">
                    <div class="dropdown action-dropdown-wrap">
                        <button class="btn btn-sm btn-icon"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                            <i class="fa fa-ellipsis-h"></i>
                        </button>
                
                        <ul class="dropdown-menu dropdown-menu-end action-dropdown">
                            <li>
                                <a class="dropdown-item btn_modal" href="{{ route('incomes.show',[$item->id])}}">
                                    <i class="fa fa-eye"></i>
                                    <span>View</span>
                                </a>
                            </li>
                            @can('incomes.edit')
                            <li>
                                <a class="dropdown-item btn_modal" href="{{ route('incomes.edit',[$item->id])}}">
                                    <i class="fa fa-edit"></i>
                                    <span>Edit</span>
                                </a>
                            </li>
                            @endcab
                            
                            @can('incomes.delete')
                            <li>
                                <a class="dropdown-item text-danger delete" href="{{ route('incomes.destroy',[$item->id])}}">
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
