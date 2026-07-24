<div class="table-responsive">
	<table class="table ">
		<thead class="thead-light">
			<tr>
			    <th>SL</th>
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
				
			
				<th>Status</th>
				<th>Grand Total</th>
				<th>Paid</th>
				<th>Due</th>
				<th>Payment Status</th>
				<th>Biller</th>
				
										
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
                             
                                    $url=route('sells.show',[$item->id]);
                                    $durl=route('sells.destroy',[$item->id]);
                                    
                                @endphp
                                <a class="dropdown-item btn_modal" href="{{ $url }}">
                                    <i class="fa fa-eye"></i>
                                    <span>View</span>
                                </a>
                            </li>
                            
                            @if($item->is_pos)
                            @can('pos.edit')
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('pos.edit',[$item->id])}}">
                                    <i class="fa fa-edit"></i>
                                    <span>Edit</span>
                                </a>
                            </li>
                            @endcan
                            @endif
                            
                            @canany(['sells.shiping_update', 'pos.shiping_update'])
                            <li>
                                <a class="dropdown-item btn_modal" href="{{ route('sellStatus',[$item->id])}}">
                                    <i class="fa fa-truck"></i>
                                    <span>Shipping</span>
                                </a>
                            </li>
                            @endcan
                            
                            <li>
                                <a class="dropdown-item print-btn btn_print" href="{{ route('sellPrint',[$item->id])}}">
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
                            
                            @canany(['sells.delete'])
                            
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
				<td>{{ $contact->phone ??$contact->mobile}}</td>
				<td>
				    {{ $item->invoice_no}}
				
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
				

				<td>{{ $item->shipping_status}}</td>
				<td>{{ priceFormate($item->final_amount)}}</td>
				<td>{{ priceFormate($paid)}}</td>
				<td>{{ priceFormate($due)}}</td>
				<td>{{ $item->payment_status}}</td>
				<td>{{ $item->user->name??''}}</td>

				
			</tr>

			@endforeach
		</tbody>
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
    
    document.addEventListener("DOMContentLoaded", function () {
        const isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
    
        document.querySelectorAll('.btn-print').forEach(function (btn) {
            if (isMobile) {
                
                // Mobile হলে class remove
                btn.classList.remove('btn_print');
                // Mobile হলে target add
                btn.setAttribute('target', '_blank');
            } else {
                // Desktop/Laptop হলে target remove
                btn.removeAttribute('target');
            }
        });
    });

</script>
