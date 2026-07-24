<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
  <div class="modal-content">
    <form action="{{ route('delivery_charges.update',[$charge->id])}}" method="post" id="ajax_form">
      @method('PATCH')
      @csrf

      <div class="modal-header">
        <h5 class="modal-title">Delivery Charge</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="row g-3">

          <!-- Title -->
          <div class="col-12 col-lg-6">
            <label class="form-label">Title</label>
            <input
              type="text"
              name="title"
              class="form-control"
              placeholder="Enter title"
              value="{{ $charge->title }}"
            />
          </div>

          <!-- Amount -->
          <div class="col-12 col-lg-3">
            <label class="form-label">Amount</label>
            <input
              type="text"
              name="amount"
              class="form-control"
              placeholder="Enter amount"
              value="{{ $charge->amount }}"
            />
          </div>
          
          <!-- Status -->
          <div class="col-12 col-lg-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="status">
              <option value="1" {{ '1' == $charge->status ? 'selected' : '' }}>Active</option>
              <option value="0" {{ '0' == $charge->status ? 'selected' : '' }}>De-Active</option>
            </select>
          </div>

          <!-- Description -->
          <div class="col-12 ">
            <label class="form-label">Description</label>
            <textarea
              name="description"
              class="form-control"
              rows="2"
              placeholder="Enter description"
            >{{ $charge->description }}</textarea>
          </div>
        </div>
      </div>

      <div class="modal-footer d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>

    </form>
  </div>
</div>
