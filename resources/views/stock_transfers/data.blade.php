<div class="table-responsive">
	<table class="table ">
		<thead class="thead-light">
			<tr>
			    <th>SL</th>
			    <th class="no-sort">Action</th>
				<th>Reference</th>
				<th>Date</th>
				<th>User </th>
				<th>Location From </th>
				<th>Location To </th>
				<th>Total</th>
				<th>Paid</th>
				<th>Due</th>
				<th>Payment Status</th>
				<th> Note </th>
			</tr>
		</thead>
		<tbody>
			@foreach($items as $i=>$item)
			
			@php
			    $paid = $item->payments->sum('amount');
			    $due = ($item->final_amount ?? 0) - $item->payments->sum('amount');
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
                                <a class="dropdown-item btn_modal" href="{{ route('stock_transfers.show',[$item->id])}}">
                                    <i class="fa fa-eye"></i>
                                    <span>View</span>
                                </a>
                            </li>
                            
                            <!--<li>-->
                            <!--    <a class="dropdown-item btn_print" href="{{ route('sellPrint',[$item->id])}}">-->
                            <!--        <i class="fa fa-print"></i>-->
                            <!--        <span>Print</span>-->
                            <!--    </a>-->
                            <!--</li>-->
                            
                            @if($due>0)
                            <li>
                                <a class="dropdown-item btn_modal" href="{{ route('transaction_payments.edit',[$item->id])}}">
                                    <i class="fa fa-credit-card"></i>
                                    <span>Payment</span>
                                </a>
                            </li>
                            @endif
                            
                            @can('stock_transfers.delete')
                            <li>
                                <a class="dropdown-item text-danger delete" href="{{ route('stock_transfers.destroy',[$item->id])}}">
                                    <i class="fa fa-trash"></i>
                                    <span>Delete</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </td>
                    
				
				<td>{{ $item->invoice_no}}</td>
				<td>{{ dateFormate($item->transaction_date)}}</td>
				<td>{{ $item->user->name??''}}</td>
				<td>{{ $item->location->name??''}}</td>
				<td>{{ $item->locationTo->name??''}}</td>
				
				<td>{{ priceFormate($item->final_amount)}}</td>
				<td>{{ priceFormate($paid)}}</td>
				<td>{{ priceFormate($due)}}</td>
    				
				<td>{{ $item->payment_status}}</td>
				<td>{{ $item->note}}</td>
			</tr>

			@endforeach
		</tbody>
	</table>
</div>
<p> {{$items->render()}} </p>
