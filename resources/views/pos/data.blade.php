<div class="table-responsive">
	<table class="table ">
		<thead class="thead-light">
			<tr>
			    <th> SL </th>
			    <th>Action</th>
				<!--<th class="no-sort">-->
				<!--	<label class="checkboxs">-->
				<!--		<input type="checkbox" id="select-all">-->
				<!--		<span class="checkmarks"></span>-->
				<!--	</label>-->
				<!--</th>-->
				<th>Customer</th>
				<th>Contact</th>
				<th>Reference</th>
				<th>Date</th>
				
				@if($quotation)
				<th>Expired Date</th>
				@endif
				@if(!$quotation)
				<th>Order From</th>
				@endif
				<th>Status</th>
				<th>Grand Total</th>
				<th>Paid</th>
				<th>Due</th>
				<th>Payment Status</th>
				<th>Biller</th>
				
										
			</tr>
		</thead>
		<tbody>
		    @php
		        $totalpaid=0;
		    @endphp
		    @foreach($items as $i=>$item)
			
			@php
			    $paid = $item->payments->sum('amount');
			    $due = ($item->final_amount ?? 0) - $item->payments->sum('amount');
			    
			    $contact=$item->contact??$item->shipping;
			    
			    $totalpaid +=$paid;
			@endphp
    			
			<tr>
			    <td>{{ $i+1}}</td>
			    <td class="action-table-data">
                    <div class="dropdown action-dropdown-wrap">
                        <button class="btn btn-sm btn-icon"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                            <i class="fa fa-ellipsis-h"></i>
                        </button>
                
                        <ul class="dropdown-menu dropdown-menu-end action-dropdown">
                            <li>
                                @php
                                  
                                        $url=route('pos.show',[$item->id]);
                                        $durl=route('pos.destroy',[$item->id]);
                                    
                                @endphp
                                <a class="dropdown-item btn_modal" href="{{ $url }}">
                                    <i class="fa fa-eye"></i>
                                    <span>View</span>
                                </a>
                            </li>
                            
                         
                            @can('pos.edit')
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('pos.edit',[$item->id])}}">
                                    <i class="fa fa-edit"></i>
                                    <span>Edit</span>
                                </a>
                            </li>
                            @endcan
                            
                            @can('pos.edit')
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('sell_returns.edit',[$item->id])}}">
                                    <i class="fa fa-edit"></i>
                                    <span>Return </span>
                                </a>
                            </li>
                            @endcan
                            
                            @if($item->quotation==0)
                            @canany(['pos.shiping_update'])
                            <li>
                                <a class="dropdown-item btn_modal" href="{{ route('sellStatus',[$item->id])}}">
                                    <i class="fa fa-truck"></i>
                                    <span>Shipping</span>
                                </a>
                            </li>
                            @endcan
                            @endif
                            
                            <li>
                                <a class="dropdown-item btn_print" href="{{ route('sellPrint',[$item->id])}}">
                                    <i class="fa fa-print"></i>
                                    <span>Print</span>
                                </a>
                            </li>
                            
                            @if($item->quotation==0)
                            @can('sells.due_payment')
                            @if($due>0)
                            <li>
                                <a class="dropdown-item btn_modal" href="{{ route('transaction_payments.edit',[$item->id])}}">
                                    <i class="fa fa-credit-card"></i>
                                    <span>Payment</span>
                                </a>
                            </li>
                            @endif
                            @endcan
                            @endif
                            
                            @canany(['pos.delete'])
                            
                            <li>
                                <a class="dropdown-item text-danger delete" href="{{ $durl }}">
                                    <i class="fa fa-trash"></i>
                                    <span>Delete</span>
                                </a>
                            </li>
                            @endcanany
                        </ul>
                    </div>
                </td>
                
				<!--<td>-->
				<!--	<label class="checkboxs">-->
				<!--		<input type="checkbox" class="select_item" value="{{ $item->id }}">-->
				<!--		<span class="checkmarks"></span>-->
				<!--	</label>-->
				<!--</td>-->

				<td>{{ $contact->name ??''}}</td>
				<td>{{ $contact->mobile ??$contact->phone}}</td>
				<td>
				    {{ $item->invoice_no}}
				    
				    @if($item->sell_return)
				    <br><span class="text-danger"> Return </span>
				    @endif
				
    				@if($item->cancel_request)
        				<div>
        				    <span class="badge mb-1"
                                  style="background:#ffecec; color:#ff0000; font-weight:500; cursor: pointer;"
                                  data-bs-toggle="popover"
                                  data-bs-trigger="hover"
                                  data-bs-placement="top"
                                  data-bs-html="true"
                                  title="Cancel Note"
                                  data-bs-content="{{ $item->cancel_note }}">
                                Cancel Requested
                            </span>
        				</div>
                    @endif
				</td>
				<td>{{ dateFormate($item->transaction_date)}}</td>
				
				@if($quotation)
				<td>{{ dateFormate($item->price_expiry_date)}}</td>
				@endif
				@if(!$quotation)
				<td>{{ $item->order_from->title??''}}</td>
				@endif
				<td>{{ $item->shipping_status}}</td>
				<td>{{ priceFormate($item->final_amount)}}</td>
				<td>{{ priceFormate($paid)}}</td>
				<td>{{ priceFormate($due)}}</td>
				<td>{{ $item->payment_status}}</td>
				<td>{{ $item->user->name??''}}</td>

				
			</tr>

			@endforeach
		</tbody>
		
		<thead>
		    <tr>
		        <th colspan="7"> Total </th>
		        
		        <th> {{ $items->count() }} </th>
		        <th> {{ priceFormate($items->sum('final_amount')) }} </th>
		        <th> {{ priceFormate($totalpaid) }} </th>
		        <th> {{  priceFormate($items->sum('final_amount') - $totalpaid) }} </th>
		    </tr>
		</thead>
	</table>
</div>
<p> {{$items->render()}} </p>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        })
    });
</script>
