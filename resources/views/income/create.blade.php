<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Income</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <form action="{{ route('incomes.update',[$transaction->id])}}" method="post" id="ajax_form">
      @method('PATCH')
      @csrf

      <div class="modal-body">
        <div class="row g-3">

          <!-- Location -->
          <div class="col-12 col-sm-6 col-lg-4">
            <label class="form-label">Location<span class="text-danger ms-1">*</span></label>
            <select class="form-select" name="location_id">
              <option value="">Select</option>
              @foreach($locations as $location)
                <option value="{{$location->id}}" {{ $transaction->location_id == $location->id ? 'selected' : '' }}>
                    {{$location->name}}
                </option>
              @endforeach
            </select>
          </div>

          <!-- Category -->
          <div class="col-12 col-sm-6 col-lg-4">
            <label class="form-label">Category<span class="text-danger ms-1">*</span></label>
            <select class="form-select" name="category_id">
              <option value="">Select</option>
              @foreach($cats as $cat)
                <option value="{{$cat->id}}" {{$transaction->category_id == $cat->id ? 'selected' : ''}}>
                  {{$cat->name}}
                </option>
              @endforeach
            </select>
          </div>

          <!-- Date -->
          <div class="col-12 col-sm-6 col-lg-4">
            <label class="form-label">Date<span class="text-danger ms-1">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i data-feather="calendar"></i></span>
              <input type="date" class="form-control datetimepicker" placeholder="dd/mm/yyyy" name="transaction_date" value="{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('Y-m-d') }}">
            </div>
          </div>

          <!-- Reference -->
          <div class="col-12 col-sm-6 col-lg-4">
            <label class="form-label">Reference<span class="text-danger ms-1">*</span></label>
            <input type="text" class="form-control" name="invoice_no" value="{{ $transaction->invoice_no }}">
          </div>

          <!-- Total Amount -->
          <div class="col-12 col-sm-6 col-lg-4">
            <label class="form-label">Total Amount<span class="text-danger ms-1">*</span></label>
            <input type="text" class="form-control final_amount" name="final_amount" value="{{ $transaction->final_amount }}">
          </div>

          <!-- Note -->
          <div class="col-12">
            <label class="form-label">Note</label>
            <textarea class="form-control" name="note" rows="2">{{ $transaction->note }}</textarea>
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
