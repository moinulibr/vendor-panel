<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <form action="{{ route('nextPaymentUpdate',[$item->id])}}" method="post" id="ajax_form">
        @csrf
    <div class="modal-header">
      <h1 class="modal-title">Next Payment Note Update</h1>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                
                <div class="card-body">
                  <div class="row g-3">
                    <div class="col-12 col-sm-6 col-lg-6">
                        <label class="form-label">Note <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="note" placeholder="Note" required> {{ $item->note }} </textarea>
                    </div>
                    
                    <div class="col-12 col-sm-6 col-lg-6">
                        <label class="form-label">Next Payment Date <span class="text-danger">*</span></label>
                        <input type="date" name="next_payment_date" value="{{ $item->next_payment_date }}" class="form-control next_payment_date">
                    </div>
                    
                    
                  </div>
                </div>
            </div>
            
        </div>
    </div>
    <div class="modal-footer d-flex gap-2">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
    </form>
  </div>
</div>