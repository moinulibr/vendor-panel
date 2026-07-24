<div class="table-responsive">
	<table class="table ">
		<thead class="thead-light">
			<tr>
			    <th>SL</th>
				<th>Supplier Name</th>
				<th>Vandor Name</th>
				<th>Reference</th>
				<th>Date</th>
				<th>Status</th>
				<th>Total</th>
				<th>Paid</th>
				<th>Due</th>
				<th>Payment Status</th>
				<th class="no-sort">Action</th>
										
			</tr>
		</thead>
		<tbody>
			@foreach($items as $i=>$item)
			
			@php
			    $paid = $item->payments->sum('amount');
			    $due = ($item->final_amount ?? 0) - $item->payments->sum('amount');
			    
			    $contact=$item->shipping??$item->contact;
			@endphp
			
			<tr>
			    <td>
			        {{ $i+1}}
			    </td>
				<td>{{ $item->contact->name ??''}}</td>
				<td>{{ $item->vendor->name ??''}}</td>
				<td>{{ $item->invoice_no}}</td>
				<td>{{ dateFormate($item->transaction_date)}}</td>
				<td>{{ $item->shipping_status}}</td>
				
				<td>{{ priceFormate($item->final_amount)}}</td>
    				
    				
				<td>{{ $paid}}</td>
				<td>{{ $due}}</td>
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
                                <a class="dropdown-item btn_modal" href="{{ route('purchases.show',[$item->id])}}">
                                    <i class="fa fa-eye"></i>
                                    <span>View</span>
                                </a>
                            </li>
                            
                            @can('purchases.edit')
                            <li>
                                <a class="dropdown-item btn_modal" href="{{ route('purchases.edit',[$item->id])}}">
                                    <i class="fa fa-edit"></i>
                                    <span>Edit</span>
                                </a>
                            </li>
                            @endcan
                            
                            @if($due>0)
                            <li>
                                <a class="dropdown-item btn_modal" href="{{ route('transaction_payments.edit',[$item->id])}}">
                                    <i class="fa fa-credit-card"></i>
                                    <span>Payment</span>
                                </a>
                            </li>
                            @endif
                                
                            
                            @can('purchases.delete')
                            <li>
                                <a class="dropdown-item text-danger delete" href="{{ route('purchases.destroy',[$item->id])}}">
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
