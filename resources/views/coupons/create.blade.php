<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <form action="{{ route('coupons.update',[$coupon->id])}}" method="post" id="ajax_form">
    @method('PATCH')
    @csrf
    <div class="modal-header">
      <div class="page-title">
        <h4>Add Coupon</h4>
      </div>
      <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-lg-6">
            <div class="mb-3">
              <label class="form-label">Coupon Name<span class="text-danger ms-1">*</span></label>
              <input type="text" class="form-control" name="title" value="{{ $coupon->title }}">
            </div>
          </div>
          <div class="col-lg-6">
            <div class="mb-3">
              <label class="form-label">Coupon Code<span class="text-danger ms-1">*</span></label>
              <input type="text" class="form-control" name="code" value="{{ $coupon->code }}">
            </div>
          </div>
          <div class="col-lg-6">
            <div class="mb-3">
              <label class="form-label">Type<span class="text-danger ms-1">*</span></label>
              <select class="form-control" name="discount_type">
                <option value="">Choose Type</option>
                <option value="fixed" {{ $coupon->discount_type=='fixed'?'selected':''}}>Fixed</option>
                <option value="percentage" {{ $coupon->discount_type=='percentage'?'selected':''}}>Percentage</option>
              </select>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="mb-3">
              <label class="form-label">Discount<span class="text-danger ms-1">*</span></label>
              <input type="text" class="form-control" name="amount" value="{{ $coupon->amount }}">
            </div>
          </div>
 

          <div class="col-lg-6">
            <div class="mb-3">
              <label class="form-label">Start Date<span class="text-danger ms-1">*</span></label>
              
              <div class="input-groupicon calender-input">
                <i data-feather="calendar" class="info-img"></i>
                <input type="date" name="start" value="{{ $coupon->start}}" class="datetimepicker form-control p-2" placeholder="dd/mm/yyy" >
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="mb-3">
              <label class="form-label">End Date<span class="text-danger ms-1">*</span></label>
              
              <div class="input-groupicon calender-input">
                <i data-feather="calendar" class="info-img"></i>
                <input type="date" name="end" value="{{ $coupon->end}}" class="datetimepicker form-control p-2" placeholder="dd/mm/yyy" >
              </div>
            </div>
          </div>

        
          <div class="mb-3 summer-description-box">
            <label class="form-label">Description</label>
            <textarea name="note" class="form-control">{{ $coupon->note }}</textarea>
          </div>
          
        
          <div class="m-0">
            <div class="status-toggle modal-status d-flex justify-content-between align-items-center">
              <span class="status-label">Status</span>
              <input type="checkbox" name="status" id="user2" value="1" class="check" {{ $coupon->status==1?'checked':''}}>
              <label for="user2" class="checktoggle"> </label>
            </div>
          </div>
        </div>                
      </div>
      <div class="modal-footer">
        <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Add Coupon</button>
      </div>
    </form>
  </div>
</div>