<div class="table-responsive">
	<table class="table ">
		<thead class="thead-light">
			<tr>
			    <th> SL </th>
			    <th>Action</th>
				<th>Customer</th>
				<th>Contact</th>
				<th>Reference</th>
				<th>Date</th>
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
                                        $durl=route('sell_returns.destroy',[$item->id]);
                                    
                                @endphp
                                <a class="dropdown-item btn_modal" href="{{ $url }}">
                                    <i class="fa fa-eye"></i>
                                    <span>View</span>
                                </a>
                            </li>
                            
                            
                            
                            <li>
                                <a class="dropdown-item btn_print" href="{{ route('sellPrint',[$item->return_parent_id])}}">
                                    <i class="fa fa-print"></i>
                                    <span>Print</span>
                                </a>
                            </li>
                            
                      
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
				
				</td>
				<td>{{ dateFormate($item->transaction_date)}}</td>
				
		
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
		        <th colspan="5"> Total </th>
		        
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
