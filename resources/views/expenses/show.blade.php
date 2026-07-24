<div class="modal-dialog sales-details-modal">
	<div class="modal-content">		
		<div class="modal-header">
	      <h1 class="modal-title">{{ ucfirst($transaction->type)}} Details </h1>
	      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	    </div>

		<div class="card border-0">
			<div class="card-body pb-0">
				<div class="invoice-box table-height" style="max-width: 1600px;width:100%;padding: 0;font-size: 14px;line-height: 24px;color: #555;">
					<div class="row">
						<div class="col-md-4">
							<p class="mb-0">Invoice No :<span> {{ $transaction->invoice_no}} </span></p>
							<p class="mb-0">Transaction Date :<span> {{ $transaction->transaction_date}} </span></p>
							<p class="mb-0">Payment Status :<span> {{ ucfirst($transaction->payment_status)}}</span></p>
						</div>

						<div class="col-md-4">
							<p class="mb-0">Location :<span> {{ $transaction->location->name ??''}} </span></p>
							<p class="mb-0">Created By :<span> {{ $transaction->user->name??''}} </span></p>
						</div>
						
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
											<td>{{ ucfirst($payment->method)}}</td>
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
											<th> Total</th>
											<td> {{ $transaction->final_amount}}</td>
										</tr>
										<tr>
											<th>Paid</th>
											<td>{{ $transaction->payments->sum('amount')}}</td>
										</tr>
										<tr>
											<th>Due</th>
											<td>{{ $transaction->final_amount - $transaction->payments->sum('amount')}}</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>

					</div>
				</div>

			</div>
		</div>
	</div>
</div>