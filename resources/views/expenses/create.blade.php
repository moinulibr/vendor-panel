<div class="modal-dialog modal-lg modal-dialog-centered">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Expense</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <form action="{{ route('expenses.update',[$transaction->id]) }}" method="post" id="ajax_form">
      @csrf
      @method('PATCH')

      <div class="modal-body">
        <div class="row g-3">

          <!-- Location -->
          <div class="col-lg-4 col-md-6">
            <label class="form-label">Location <span class="text-danger">*</span></label>
            <select class="form-control" name="location_id">
              <option value="">Select</option>
              @foreach($locations as $location)
                <option value="{{ $location->id }}" {{ $transaction->location_id == $location->id ? 'selected' : '' }}>
                  {{ $location->name }}
                </option>
              @endforeach
            </select>
          </div>

          <!-- Category -->
          <div class="col-lg-4 col-md-6">
            <label class="form-label">Category <span class="text-danger">*</span></label>
            <select class="form-control" name="category_id">
              <option value="">Select</option>
              @foreach($cats as $cat)
                <option value="{{ $cat->id }}" {{ $transaction->category_id == $cat->id ? 'selected' : '' }}>
                  {{ $cat->name }}
                </option>
              @endforeach
            </select>
          </div>

          <!-- Date -->
          <div class="col-lg-4 col-md-6">
            <label class="form-label">Date <span class="text-danger">*</span></label>
            <input type="date" class="form-control" name="transaction_date"
              value="{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('Y-m-d') }}">
          </div>

          <!-- Reference -->
          <div class="col-lg-4 col-md-6">
            <label class="form-label">Reference <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="invoice_no"
              value="{{ $transaction->invoice_no }}">
          </div>

          <!-- Amount -->
          <div class="col-lg-4 col-md-6">
            <label class="form-label">Total Amount <span class="text-danger">*</span></label>
            <input type="number" class="form-control final_amount" name="final_amount"
              value="{{ $transaction->final_amount }}">
          </div>

          <!-- Note -->
          <div class="col-lg-12">
            <label class="form-label">Note</label>
            <textarea class="form-control" rows="2" name="note">{{ $transaction->note }}</textarea>
          </div>

        </div>
      </div>

      <div class="modal-footer d-flex gap-2">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </form>

  </div>
</div>
