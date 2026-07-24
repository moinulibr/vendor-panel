<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <form action="{{ route('discounts.update',[$discount->id])}}" method="post" id="ajax_form">
    @method('PATCH')
    @csrf
    <div class="modal-header">
      <div class="page-title">
        <h4>{{$discount->title?'Edit':'Add'}} Discount</h4>
      </div>
      <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="mb-3">
                  <label class="form-label">Discount Name<span class="text-danger ms-1">*</span></label>
                  <input type="text" class="form-control" name="title" value="{{ $discount->title }}">
                </div>
            </div>
            
            <div class="col-12 col-sm-6 col-lg-6">
              <label class="form-label"> Vendor <span class="text-danger">*</span></label>
              <select class="form-control" name="user_id">
                <option value="">Select One</option>
                @foreach($users as $user)
                    <option value="{{ $user->id}}" {{ $user->id==$discount->user_id ? 'selected':''}}> {{ $user->name}}</option>
                @endforeach
              </select>
            </div>
            
            
            <div class="col-12 col-md-6 col-lg-6">
              <div class="form-group">
                  <label> Category</label>
                  <select class="form-control" name="category_id">
                    <option value="">Select One</option>
                    @foreach($cats as $cat)
                    <option value="{{ $cat->id}}" {{ $cat->id==$discount->category_id ? 'selected':''}}> {{ $cat->name}}</option>
                    @endforeach
                  </select>
              </div>
            </div>

            <div class="col-12 col-md-6 col-lg-6">
                <div class="form-group">
                    <label>Brand</label>
                    <select class="form-control" name="brand_id">
                        <option value="">Select One</option>
                        @foreach($brands as $brand)
                        <option value="{{ $brand->id}}" {{ $brand->id==$discount->brand_id ? 'selected':''}}> {{ $brand->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            

            <div class="col-12 col-md-6 col-lg-6">
                <div class="mb-3">
                  <label class="form-label">Type<span class="text-danger ms-1">*</span></label>
                  <select class="form-control" name="discount_type">
                    <option value="">Choose Type</option>
                    <option value="fixed" {{ $discount->discount_type=='fixed'?'selected':''}}>Fixed</option>
                    <option value="percentage" {{ $discount->discount_type=='percentage'?'selected':''}}>Percentage</option>
                  </select>
                </div>
            </div>
    
            <div class="col-12 col-md-6 col-lg-6">
                <div class="mb-3">
                  <label class="form-label">Discount Amount<span class="text-danger ms-1">*</span></label>
                  <input type="text" class="form-control" name="amount" value="{{ $discount->amount }}">
                </div>
            </div>
     
    
            <div class="col-12 col-md-6 col-lg-6">
                <div class="mb-3">
                  <label class="form-label">Start Date<span class="text-danger ms-1">*</span></label>
                  
                  <div class="input-groupicon calender-input">
                    <i data-feather="calendar" class="info-img"></i>
                    <input type="date" name="start" value="{{ $discount->start}}" class="datetimepicker form-control p-2" placeholder="dd/mm/yyy" >
                  </div>
                </div>
            </div>
            
            <div class="col-12 col-md-6 col-lg-6">
                <div class="mb-3">
                  <label class="form-label">End Date<span class="text-danger ms-1">*</span></label>
                  
                  <div class="input-groupicon calender-input">
                    <i data-feather="calendar" class="info-img"></i>
                    <input type="date" name="end" value="{{ $discount->end}}" class="datetimepicker form-control p-2" placeholder="dd/mm/yyy" >
                  </div>
                </div>
            </div>
            
            <div class="col-12 col-md-6 col-lg-6">
                <div class="mb-3">
                  <label class="form-label">Priority<span class="text-danger ms-1">*</span></label>
                  <select class="form-control" name="priority">
                    <option value="">Select One</option>
                    @foreach(discountPriorityList() as $priority)
                        <option value="{{ $priority['id']}}" {{ $priority['id'] == $discount->priority ? 'selected':''}} > {{ $priority['label']}}</option>
                    @endforeach
                  </select>
                  {{-- <input type="number" class="form-control" name="priority" value="{{ $discount->priority }}"> --}}
                </div>
            </div>
            
            <div class="col-12 col-md-6 col-lg-6">
                 <div class="mb-3">
                    <label class="form-label d-block">Status</label>
                    <div class="form-check form-switch">
                      <input 
                        class="form-check-input" 
                        type="checkbox" 
                        role="switch"
                        id="discountStatus"
                        name="status"
                        value="1"
                        {{ $discount->status==1 ? 'checked' : '' }}
                      >
                      <label class="form-check-label" for="discountStatus">
                        Active
                      </label>
                    </div>
                </div>
            </div>
        </div>                
      </div>
      <div class="modal-footer">
        <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">{{$discount->title?'Update':'Add'}} Discount</button>
      </div>
    </form>


  </div>
</div>