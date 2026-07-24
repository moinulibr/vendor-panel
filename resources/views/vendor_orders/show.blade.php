<div class="modal-dialog sales-details-modal">
	<div class="modal-content">		
		<div class="modal-header">
	      <h1 class="modal-title">Order Details ({{ $order->invoice_no}})</h1>
	      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	    </div>

		<div class="card border-0">
			<div class="card-body pb-0">
				<div class="invoice-box table-height" style="max-width: 1600px;width:100%;padding: 0;font-size: 14px;line-height: 24px;color: #555;">
					<div class="row">
						<div class="col-md-4">
							<p class="mb-0">Main Invoice No :<span> {{ $order->transaction?->invoice_no ?? 'N/A' }} </span></p>
							<p class="mb-0">Transaction Date :<span> {{ dateFormate($order->created_at)}} </span></p>
							<p class="mb-0">Shipping Status :<span> {{ $order->shipping_status}}</span></p>
						</div>

						@if($order->transaction?->shipping)
						<div class="col-md-4">
							<h4 class="mb-1"> Customer </h4>
							<p class="mb-1"> {{ $order->transaction->shipping->name}} </p>
							<p class="mb-1"> {{ $order->transaction->shipping->phone}} </p>
							<p class="mb-1"> {{ $order->transaction->shipping->address}} </p>
						</div>
						@endif
						<div class="col-md-4">
							<p class="mb-0">Location :<span> {{ $order->transaction->location->name ?? 'N/A' }} </span></p>
						</div>
						
					</div>
					<h5 class="order-text"> Product Details </h5>
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
								@foreach($order->lines as $line)
								<tr>
									<td>{{ $line->product->name}} 
										{{ $line->product->type=='variable' ? $line->variation->name:''}}</td>
									<td>{{ $line->product->sku}}</td>
									<td>{{ $line->quantity}}</td>
									<td>{{ priceFormate($line->price)}}</td>
									<td>{{ priceFormate($line->price *$line->quantity)}}</td>
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
											<td> {{ priceFormate($order->final_amount)}}</td>
										</tr>
										<tr>
											<th>Discount</th>
											<td>{{ priceFormate($order->discount_amount)}}</td>
										</tr>
										<tr>
											<th>Shipping Charge</th>
											<td>{{ priceFormate($order->shipping_charge)}}</td>
										</tr>
										<tr>
											<th>Final Amount</th>
											<td>{{ priceFormate($order->final_amount)}}</td>
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
			<button type="submit" class="btn btn-primary">Submit</button>
		</div>
	</div>
</div>