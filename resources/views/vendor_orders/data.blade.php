<div class="table-responsive">
	<table class="table ">
		<thead class="thead-light">
			<tr>
			    <th>SL</th>
			    <th>Action</th>
				<th class="no-sort">
					<label class="checkboxs">
						<input type="checkbox" id="select-all">
						<span class="checkmarks"></span>
					</label>
				</th>
				<th>Customer</th>
				<th>Contact</th>
				<th>Reference</th>
				<th>Date</th>
				<th>Shipping Status</th>
				<th>Grand Total</th>
				<th>Paid</th>
				<th>Due</th>
				<th>Payment Status</th>
				<th>Vendor</th>
				
										
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
                                <a class="dropdown-item btn_modal" href="{{ route('vendor_orders.show',[$item->id])}}">
                                    <i class="fa fa-eye"></i>
                                    <span>View</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item btn_modal" href="{{ route('vendorOrderStatus',[$item->id])}}">
                                    <i class="fa fa-truck"></i>
                                    <span>Shipping</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item btn_print" href="{{ route('orderPrint',[$item->id])}}">
                                    <i class="fa fa-print"></i>
                                    <span>Print</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item btn_modal" href="{{ route('vendor_order_payments.edit',[$item->id])}}">
                                    <i class="fa fa-credit-card"></i>
                                    <span>Payment</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger delete" href="{{ route('vendor_orders.destroy',[$item->id])}}">
                                    <i class="fa fa-trash"></i>
                                    <span>Delete</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </td>
                
				<td>
					<label class="checkboxs">
						<input type="checkbox">
						<span class="checkmarks"></span>
					</label>
				</td>

				<td>{{ $item->transaction->shipping->name ??''}}</td>
				<td>{{ $item->transaction->shipping->phone ??''}}</td>
				<td>{{ $item->invoice_no}}</td>
				<td>{{ dateFormate($item->created_at)}}</td>
				<td>{{ $item->shipping_status}}</td>
				<td>{{ priceFormate($item->final_amount)}}</td>
				<td>{{ priceFormate($paid)}}</td>
				<td>{{ priceFormate($due)}}</td>
				<td>{{ $item->payment_status}}</td>
				<td>{{ $item->user->name ??''}}</td>

				
			</tr>

			@endforeach
		</tbody>
	</table>
</div>
<p> {{$items->render()}} </p>
