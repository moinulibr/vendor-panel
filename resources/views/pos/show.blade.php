<div class="modal-dialog sales-details-modal">
	<div class="modal-content">		
		<div class="modal-header">
	      <h1 class="modal-title">Sell Detail </h1>
	      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	    </div>

		<div class="card border-0">
			<div class="card-body pb-0">
				<div class="invoice-box table-height" style="max-width: 1600px;width:100%;padding: 0;font-size: 14px;line-height: 24px;color: #555;">
					<div class="row">
						<div class="col-md-3">
							<p class="mb-0"><strong>Invoice No :</strong><span> {{ $transaction->invoice_no}} </span></p>
							<p class="mb-0"><strong>Shipping Status :</strong><span> {{ ucfirst($transaction->shipping_status) }}</span></p>
							<p class="mb-0"><strong>Transaction Date :</strong><span>  {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d-m-Y') }}</span></p>
							<p class="mb-0"><strong>Delivery date :</strong><span> {{ \Carbon\Carbon::parse($transaction->delivery_date)->format('d-m-Y') }}</span></p>
							@if($transaction->next_payment_date)
                                <p class="mb-0"><strong>Next Payment Date:</strong> {{ \Carbon\Carbon::parse($transaction->next_payment_date)->format('d-m-Y') }}</p>
                            @endif
						</div>

						@if($transaction->contact)
						<div class="col-md-3">
							<h4 class="mb-1"> Customer </h4>
							<p class="mb-1"> {{ $transaction->contact->name}} </p>
							<p class="mb-1"> {{ $transaction->contact->mobile}} </p>
							<p class="mb-1"> {{ $transaction->contact->address}} </p>
						</div>
						
						@endif

						@if($transaction->shipping)
						<div class="col-md-3">
							<h4 class="mb-1"> Customer Shipping </h4>
							<p class="mb-1"> {{ $transaction->shipping->name}} </p>
							<p class="mb-1"> {{ $transaction->shipping->phone}} </p>
							<p class="mb-1"> {{ $transaction->shipping->address}} </p>
						</div>
						@endif
						<div class="col-md-3">
							<p class="mb-0">Location :<span> {{ $transaction->location->name??''}} </span></p>
						</div>
						
					</div>
					<h5 class="order-text pt-2"> Product Details </h5>
					<div class="table-responsive no-pagination mb-3">
						<table class="table table-bordered table-stripped table-hovered">
							<thead>
								<tr>
									<th>Name</th>
									<th>Sku</th>
									<th> Quantity </th>
									<th> Unit Price</th>
									<th> Subtotal</th>
									
								</tr>
							</thead>
							<tbody>
							    @php
                                    $sub_total=0;
                                @endphp
                                        
								@foreach($transaction->lines as $line)
								
								@php
                                    $sub_total +=$line->price * $line->quantity;
                                @endphp
                                        
								<tr>
									<td>{{ $line->product->name??''}} 
										{{ $line->product && $line->product->type=='variable' && $line->variation ? $line->variation->name:''}}</td>
									<td>{{ $line->product->sku ??''}}</td>
									<td>{{ $line->quantity}}</td>
									<td>{{ $line->price}}</td>
									<td>{{ $line->price *$line->quantity}}</td>
								</tr>
								@endforeach
								
							</tbody>
						</table>
					</div>

					<div class="row">
						<div class="col-md-8">
							<div class="table-responsive no-pagination mb-3">
								<table class="table table-bordered table-stripped table-hovered">
									<thead>
										<tr class="bg-green">
											<th>Date</th>
											<th>Amount</th>
											<th>Method </th>
											<th>Note</th>
											<th>Account</th>
											<th>User</th>
											
										</tr>
									</thead>
									<tbody>
										@foreach($transaction->payments as $payment)
										<tr>
											<td>{{ $payment->paid_on}} </td>
											<td>{{ $payment->amount}}</td>
											<td>{{ $payment->method}}</td>
											<td>{{ $payment->note}}</td>
											<td>{{ $payment->note}}</td>
											<td>{{ $payment->user->name??''}}</td>
										</tr>
										@endforeach
										
									</tbody>
								</table>
							</div>
						</div>

						<div class="col-md-4">
							<div class="table-responsive no-pagination mb-3">
								<table class="table table-bordered table-stripped table-hovered">
									<tbody>
										<tr>
											<th>SubTotal</th>
											<td> {{ $sub_total }}</td>
										</tr>
										<tr>
											<th>Discount {{ $transaction->discount_type=='Percentage' ?$transaction->discount_amount.' %':''}}</th>
											<td>{{ priceFormate($transaction->cal_discount)}}</td>
										</tr>
										
										@if($transaction->shipping_charge)
										<tr>
											<th>Shipping Charge</th>
											<td>{{ priceFormate($transaction->shipping_charge)}}</td>
										</tr>
										@endif
										
										<tr>
											<th>Final Amount</th>
											<td>{{ priceFormate($transaction->final_amount)}}</td>
										</tr>
										<tr>
											<th>Paid</th>
											<td>{{ priceFormate($transaction->payments->sum('amount'))}}</td>
										</tr>
										<tr>
											<th>Due</th>
											<td>{{ priceFormate($transaction->final_amount - $transaction->payments->sum('amount'))}}</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>

					</div>
				</div>

			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
		</div>
	</div>
</div>