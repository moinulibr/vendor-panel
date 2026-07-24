<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <form action="{{ route('contact_address.update',[$item->id])}}" method="post" id="ajax_form">
      @method('PATCH')
      @csrf
    <div class="modal-header">
      <h1 class="modal-title">Customer Address Update</h1>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
      
          <div class="col-12">

            
            <!-- Permanent Address -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light fw-semibold">Billing Address</div>
                <div class="card-body">
                  <div class="row g-3">
                      
                    <div class="col-12 col-sm-6 col-lg-6">
                      <label class="form-label"> Name <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" name="name" value="{{ $item->name}}" placeholder="First Name" required>
                    </div>
            
                    <div class="col-12 col-sm-6 col-lg-6">
                      <label class="form-label">Number <span class="text-danger">*</span></label>
                      <input type="text" class="form-control mobile_validation" name="phone" value="{{ $item->phone}}" placeholder="+880253653222" required>
                    </div>
                    
                    <div class="col-12 col-sm-6 col-lg-6">
                      <label class="form-label"> District <span class="text-danger">*</span></label>
                      <select class="form-select select2 district_id" name="district_id" id="district_id" required>
                        <option value="">Select  District </option>
                        @foreach($districts as $district)
                        <option value="{{ $district->id}}" {{ $item->district_id== $district->id ?'selected':''}}>{{ $district->name}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-6">
                      <label class="form-label">Thana</label>
                      <select class="form-select select2 upazila_id" name="upazila_id" id="upazila_id">
                        <option value="">Select Thana</option>
                        
                        @foreach($upazilas as $upazila)
                        <option value="{{ $upazila->id}}" {{ $item->upazila_id== $upazila->id ?'selected':''}}>{{ $upazila->name}}</option>
                        @endforeach
                        
                      </select>
                    </div>
                    
                    <div class="col-12 col-sm-6 col-lg-6">
                      <label class="form-label">Full Address <span class="text-danger">*</span></label>
                      <input type="text" name="address" class="form-control" value="{{ $item->address}}" required placeholder="Tangail Sadar, Dhaka, Bangladesh">
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