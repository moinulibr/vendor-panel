@extends('layouts.app')
@section('content')
<div class="content">
	<div class="page-header">
		<div class="add-item d-flex">
			<div class="page-title">
				<h4 class="fw-bold">Sell return </h4>
				<h6>Update Sell return</h6>
			</div>
		</div>
	</div>
	<!-- /product list -->

	<form action="{{ route('sell_returns.update',[$sell->id])}}" method="post" id="ajax_form">
              @method('PATCH')
              @csrf
	<div class="card">
		<div class="card-header">
		    
		    <div class="row">
		        
		        <div class="col-md-4">
                    <div class="p-3 border rounded bg-light h-100">
                        <p class="mb-1"><strong>Invoice No:</strong> {{ $sell->invoice_no }}</p>
                        
                        <p class="mb-1"><strong>Date:</strong> {{ $sell->transaction_date }}</p>
                    
                        
                        <p class="mb-0"><strong>Shipping Status:</strong> {{ $sell->shipping_status }}</p>
                       
                    </div>
                </div>

                @if($sell->contact)
                <div class="col-md-4">
                    <div class="p-3 border rounded h-100">
                        <h6 class="mb-2 text-primary fw-bold">Customer</h6>
                        <p class="mb-1">{{ $sell->contact->name }}</p>
                        <p class="mb-1">{{ $sell->contact->mobile }}</p>
                        <p class="mb-0 text-muted">{{ $sell->contact->address }}</p>
                    </div>
                </div>
                @endif
                
                <div class="col-md-4">
                    <div class="p-3 border rounded h-100">
                        <p class="mb-1"><strong>Location:</strong> {{ $sell->location->name ??'' }}</p>
                    </div>
                </div>
                
		    </div>
		</div>	
		<div class="card-body p-0">	
		  
			<div class="row p-2">
    			<div class="col-sm-12">
    				<table class="table bg-gray" id="sell_return_table">
    		          	<thead>
    			            <tr class="bg-green">
    			              	<th>#</th>
    			              	<th>Product Name </th>
    			              	<th>Unit price </th>
    			              	<th>Sell Quantity</th>
    			              	<th>Return Quantity</th>
    			              	<th>Return Subtotal</th>
    			            </tr>
    			        </thead>
    			        <tbody>
    			          	@foreach($sell->lines as $sell_line)
    			          		@php
    
    				                $unit_name = $sell_line->product->unit->name ??'';
    
    				                
    
    				            @endphp
    			            <tr>
    			              	<td>{{ $loop->iteration }}</td>
    			              	<td>
    			                	{{ $sell_line->product->name }}
    			                 	@if( $sell_line->product->type == 'variable')
    			                  	- {{ $sell_line->variations->product_variation->name}}
    			                  	- {{ $sell_line->variations->name}}
    			                 	@endif
    			              	</td>
    			              	<td><span class="display_currency" data-currency_symbol="true">{{ $sell_line->price }}</span></td>
    			              	<td>{{ $sell_line->formatted_qty }} {{$unit_name}}</td>
    			              	
    			              	<td>
    					            <input type="text" name="products[{{$loop->index}}][quantity]" value="{{$sell_line->quantity_returned}}"
    					            class="form-control input-sm quantity" max="{{$sell_line->quantity}}"
    					            >
    					            <input name="products[{{$loop->index}}][unit_price]" type="hidden" class="unit_price" value="{{$sell_line->price }}">
    					            <input name="products[{{$loop->index}}][sell_line_id]" type="hidden" value="{{$sell_line->id}}">
    			              	</td>
    			              	<td>
    			              		<div class="return_subtotal"></div>
    			              	</td>
    			            </tr>
    			          	@endforeach
    		          	</tbody>
    		        </table>
    			</div>
    			<div class="col-3 p-3">
    			    <label> Amount </label>
    			    
    			    <input name="final_amount" type="number" class="final_amount form-control" value="0" readonly>
    			    
    			</div>
    			
    			
    			<div class="col-12">
    			    <button class="btn btn-sm btn-primary" type="submit"> Save </button>
    			</div>
    			
    		</div>
		</div>
	</div>
	</form>
	<!-- /product list -->
</div>
@endsection

@push('js')



<script type="text/javascript">

    $(document).ready(function(){
        calculateSum();
        $(document).on('blur',".quantity, .unit_price",function(e) {
    
            calculateSum();    
        });
    
    
        function calculateSum() {
    
    
            let sub_total=0;
    
            let tblrows = $("#sell_return_table tbody tr");
            tblrows.each(function (index) {
                let tblrow = $(this);
    
                let product_qty=Number(tblrow.find('td input.quantity').val());
                let product_amount=Number(tblrow.find('td input.unit_price').val());
    
                let product_row_total=(product_qty *product_amount);
                tblrow.find('td.return_subtotal').text(product_row_total.toFixed(2));
                sub_total+=product_row_total;
             
                
            });
    
            $('input.final_amount').val(sub_total.toFixed(2));
        }
    })
  
</script>

@endpush


