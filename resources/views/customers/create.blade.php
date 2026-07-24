<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <form action="{{ route('customers.store')}}" method="post" id="ajax_form">
      @csrf
    <div class="modal-header">
      <h1 class="modal-title">Customer Add </h1>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
      
          <div class="col-12">

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light fw-semibold">Customer Details</div>
                <div class="card-body">
                  <div class="row g-3">
                    <div class="col-12 col-sm-6 col-lg-6">
                      <label class="form-label">First Name <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" name="name" placeholder="First Name" required>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-6">
                      <label class="form-label">Last Name </label>
                      <input type="text" class="form-control" name="last_name" placeholder="Last Name">
                    </div>
                    <div class="col-12 col-sm-6 col-lg-6">
                      <label class="form-label">Number <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" name="mobile" placeholder="+880253653222" required>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-6">
                      <label class="form-label">Email</label>
                      <input type="email" class="form-control" name="email" placeholder="example@mail.com">
                    </div>
                    <div class="col-12 col-sm-6 col-lg-6">
                        <label class="form-label"> Status</label>
                        <select class="form-control" name="status">
                          <option value="1">Active</option>
                          <option value="0">De-Active</option>
                        </select>
                    </div>
                  </div>
                </div>
            </div>
            
            <!-- Permanent Address -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light fw-semibold">Billing Address</div>
                <div class="card-body">
                  <div class="row g-3">
                    <div class="col-12 col-sm-6 col-lg-6">
                      <label class="form-label"> District <span class="text-danger">*</span></label>
                      <select class="form-select select2 district_id" name="p_district" id="p_district" required>
                        <option value="">Select  District </option>
                        @foreach($districts as $district)
                        <option value="{{ $district->id}}">{{ $district->name}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-6">
                      <label class="form-label">Thana</label>
                      <select class="form-select select2 upazila_id" name="p_upazila" id="p_upazila">
                        <option value="">Select Thana</option>
                        
                      </select>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-6">
                      <label class="form-label">Landmark (Optional)</label>
                      <input type="text" name="p_landmark" class="form-control" placeholder="Tangail Sadar">
                    </div>
                    <div class="col-12 col-sm-6 col-lg-6">
                      <label class="form-label">Full Address <span class="text-danger">*</span></label>
                      <input type="text" name="address" class="form-control" required placeholder="Tangail Sadar, Dhaka, Bangladesh">
                    </div>
                  </div>
                </div>
                
                <div class="col-12 p-3 mb-3">
                    
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="1" name="same_shipping" id="same_shipping">
                      <label class="form-check-label" for="same_shipping">
                            Same Shipping Address
                      </label>
                    </div>
                </div>
                    
            </div>
            
            <!-- Shipping Address -->
            <div class="card border-0 shadow-sm shipping_form">
                <div class="card-header bg-light fw-semibold">Shipping Address</div>
                <div class="card-body">
                  <div class="row g-3">
                      
                    <div class="col-12 col-sm-6 col-lg-6">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="full_name" placeholder="Full Name">
                    </div>
                    
                    <div class="col-12 col-sm-6 col-lg-6">
                      <label class="form-label">Mobile <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" name="phone" placeholder="+880253653222">
                    </div>
                    
                    <div class="col-12 col-sm-6 col-lg-6">
                        <label class="form-label">District</label>
                        <select class="form-select select2" name="s_district" id="s_district">
                            <option value="">Select District</option>
                            @foreach($districts as $district)
                            <option value="{{ $district->id}}">{{ $district->name}}</option>
                            @endforeach
                      </select>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-6">
                        <label class="form-label"> Thana </label>
                        <select class="form-select select2" name="s_upazila" id="s_upazila">
                            <option value="">Select  Thana </option>
                      </select>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-6">
                      <label class="form-label">Landmark (Optional)</label>
                      <input type="text" class="form-control" placeholder="Tangail Sadar" name="s_landmark">
                    </div>
                    
                    <div class="col-12 col-sm-6 col-lg-6">
                      <label class="form-label">Full Address</label>
                      <input type="text" class="form-control" placeholder="Tangail Sadar, Dhaka, Bangladesh" name="s_address">
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