<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <form action="{{ route('contacts.update',[$contact->id])}}" method="post" id="ajax_form">
      @method('PATCH')
      @csrf
    <div class="modal-header">
      <h1 class="modal-title">Supplier</h1>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light fw-semibold">Supplier Details</div>
                <div class="card-body">
                  <div class="row g-3">
                    <div class="col-12 col-sm-6 col-lg-6">
                      <label class="form-label">First Name <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" name="name" value="{{ $contact->name }}" placeholder="First Name" required>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-6">
                      <label class="form-label">Email</label>
                      <input type="email" class="form-control" name="email" value="{{ $contact->email }}" placeholder="example@mail.com">
                    </div>

                    <div class="col-12 col-sm-6 col-lg-6">
                      <label class="form-label">Mobile <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" name="mobile" value="{{ $contact->mobile }}" placeholder="+880253653222" required>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-6">
                      <label class="form-label">Image <span class="text-danger">*</span></label>
                      <input type="file" name="image" class="form-control" placeholder="Choose an image" />
                    </div>
                    <div class="col-12 col-sm-6 col-lg-6">
                      <label class="form-label"> Vendor <span class="text-danger">*</span></label>
                      <select class="form-control" name="user_id">
                        <option value="">Select One</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id}}" {{ $user->id==$contact->user_id ? 'selected':''}}> {{ $user->name}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-6">
                        <label class="form-label"> Status</label>
                        <select class="form-control" name="status">
                            <option value="1" {{ '1'==$contact->status ? 'selected':''}}>Active</option>
                            <option value="0" {{ '0'==$contact->status ? 'selected':''}}>De-Active</option>
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
                                <option value="{{ $district->id}}" {{ $contact->p_district == $district->id ? 'selected' : '' }}>{{ $district->name}}</option>
                            @endforeach
                      </select>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-6">
                      <label class="form-label">Thana</label>
                      <select class="form-select select2 upazila_id" name="p_upazila" id="p_upazila"data-selected="{{ $contact->p_upazila }}">

                        <option value="">Select Thana</option>
                        
                      </select>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-6">
                      <label class="form-label">Landmark (Optional)</label>
                      <input type="text" name="p_landmark" class="form-control" placeholder="Tangail Sadar">
                    </div>
                    <div class="col-12 col-sm-6 col-lg-6">
                      <label class="form-label">Full Address <span class="text-danger">*</span></label>
                      <input type="text" name="address" class="form-control" value="{{ $contact->address }}" required placeholder="Tangail Sadar, Dhaka, Bangladesh">
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