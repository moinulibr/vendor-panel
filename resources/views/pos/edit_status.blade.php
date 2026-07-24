<div class="modal-dialog modal-md">
	<form action="{{ route('updateSellStatus',[$order->id])}}" method="post" id="ajax_form">
		@csrf
	<div class="modal-content">		
		<div class="modal-header">
	      <h1 class="modal-title">Order Shipping Update </h1>
	      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	    </div>

		<div class="card border-0">
			<div class="card-body pb-0">
				<div class="invoice-box table-height" style="max-width: 1600px;width:100%;padding: 0;font-size: 14px;line-height: 24px;color: #555;">
					<div class="row">
						<div class="col-md-6">
							<label> Status</label>
							<select class="form-control" name="shipping_status">
								@foreach($statuses as $k=>$st)
								<option value="{{$k}}" {{ $k==$order->shipping_status ?'selected':''}}>{{ $st}}</option>
								@endforeach
							</select>
						</div>

						<div class="col-md-6">
							<label> Shipping Date</label>
							<input type="text" name="shipped_date" class="form-control datetime" value="{{ $order->shipped_date}}">
						</div>

						<div class="col-md-12">
							<label> Note </label>
							<textarea name="shipping_note" class="form-control" placeholder="status note">{{ $order->shipping_note}}</textarea>
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
	</form>
</div>