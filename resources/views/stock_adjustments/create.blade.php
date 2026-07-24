<div class="modal-dialog modal-xl">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Stock Adjustment</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form action="{{ route('stock_adjustments.update',[$transaction->id])}}" method="post" id="ajax_form">
      @method('PATCH')
      @csrf
      <div class="modal-body">
        <div class="row g-3">

          <!-- Location -->
          <div class="col-12 col-sm-6 col-lg-3">
            <label class="form-label">Location<span class="text-danger ms-1">*</span></label>
            <select class="form-select" name="location_id" id="location_id_from">
              <option value="">Select</option>
              @foreach($locations as $location)
                <option value="{{$location->id}}" {{ $transaction->location_id == $location->id ? 'selected' : '' }}>
                  {{$location->name}}
                </option>
              @endforeach
            </select>
          </div>

          <!-- Adjustment Type -->
          <div class="col-12 col-sm-6 col-lg-3">
            <label class="form-label">Adjustment Type<span class="text-danger ms-1">*</span></label>
            <select class="form-select" name="adjustment_type" id="adjustment_type">
              <option value="plus" {{ $transaction->adjustment_type == 'plus' ? 'selected' : '' }}>Plus</option>
              <option value="minus" {{ $transaction->adjustment_type == 'minus' ? 'selected' : '' }}>Minus</option>
            </select>
          </div>

          <!-- Date -->
          <div class="col-12 col-sm-6 col-lg-3">
            <label class="form-label">Date<span class="text-danger ms-1">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i data-feather="calendar"></i></span>
              <input type="text" class="form-control datetimepicker" placeholder="dd/mm/yyyy" name="transaction_date" value="{{ $transaction->transaction_date ?? date('Y-m-d') }}">
            </div>
          </div>
          
          <!-- Reference -->
          <div class="col-12 col-sm-6 col-lg-3">
            <label class="form-label">Reference<span class="text-danger ms-1">*</span></label>
            <input type="text" class="form-control" name="invoice_no" value="{{ $transaction->invoice_no }}">
          </div>

         

          <!-- Products Table -->
          <div class="col-12">
            <div class="modal-body-table mt-3">
                <!-- Product Search -->
              <div class="col-12">
                <!--<label class="form-label">Product<span class="text-danger ms-1">*</span></label>-->
                <input type="text" class="form-control" placeholder="Search Product" id="purchases_product">
              </div>
              <table class="table table-bordered table-hover" id="purchase_product">
                <thead class="table-light">
                  <tr>
                    <th>Product</th>
                    <th>Sku</th>
                    <th>Qty</th>
                    <th>Unit Cost($)</th>
                    <th>Total Cost(%)</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($transaction->lines as $line)
                    @include('stock_adjustments.edit_product_row', ['line'=>$line])
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

          <!-- Total Amount -->
          <div class="col-12 col-sm-6 col-lg-3">
            <label class="form-label">Total Amount<span class="text-danger ms-1">*</span></label>
            <input type="text" class="form-control final_amount" name="final_amount" value="{{ $transaction->final_amount }}">
          </div>

          <!-- Note -->
          <div class="col-12 ">
            <label class="form-label">Note</label>
            <textarea class="form-control" name="note" rows="4">{{ $transaction->note }}</textarea>
          </div>

        </div>
      </div>

      <div class="modal-footer d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </form>
  </div>
</div>
