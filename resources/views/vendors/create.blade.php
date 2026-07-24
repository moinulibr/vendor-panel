<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <form action="{{ route('vendors.store')}}" method="post" id="ajax_form">
        @csrf
        <div class="modal-header">
          <h1 class="modal-title">Vendor</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

            <div class="row g-3">

                <!-- First & Last Name -->
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>First Name:</strong><span class="text-red">*</span>
                        <input type="text" name="name" placeholder="First Name" class="form-control">
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Last Name:</strong>
                        <input type="text" name="last_name" placeholder="Last Name" class="form-control">
                    </div>
                </div>

                <!-- Email & Mobile -->
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Email:</strong><span class="text-red">*</span>
                        <input type="email" name="email" placeholder="Email" class="form-control">
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Mobile:</strong><span class="text-red">*</span>
                        <input type="number" name="mobile" placeholder="Mobile" class="form-control">
                    </div>
                </div>

                <!-- Shop Name & Trade License -->
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Shop Name:</strong><span class="text-red">*</span>
                        <input type="text" name="shop_name" placeholder="Shop Name" class="form-control">
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Trade License:</strong><span class="text-red">*</span>
                        <input type="text" name="trade_license" placeholder="Trade License" class="form-control">
                    </div>
                </div>

                <!-- Fax & Website -->
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>FAX:</strong>
                        <input type="text" name="fax" placeholder="FAX" class="form-control">
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Website:</strong>
                        <input type="text" name="website" placeholder="https://www.example.com" class="form-control">
                    </div>
                </div>

                <!-- Password & Confirm Password -->
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Password:</strong><span class="text-red">*</span>
                        <input type="password" name="password" placeholder="Password" class="form-control">
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Confirm Password:</strong><span class="text-red">*</span>
                        <input type="password" name="password_confirmation" placeholder="Confirm Password" class="form-control">
                    </div>
                </div>

                <!-- Gender -->
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Gender:</strong>
                        <select name="gender" class="form-control">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <!-- Status -->
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Status:</strong>
                        <select name="status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">De-Active</option>
                        </select>
                    </div>
                </div>

                <!-- Date of Birth -->
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Date Of Birth:</strong>
                        <input type="date" name="dob" class="form-control">
                    </div>
                </div>

                <!-- Role -->
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Role:</strong>
                        <select name="roles[]" class="form-control">
                            @foreach ($roles as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Our Mission:</strong>
                        <textarea name="our_mission" rows="5" placeholder="Enter your Our Mission" class="form-control"></textarea>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Our Vision:</strong>
                        <textarea name="our_vision" rows="5" placeholder="Enter your Our Vision" class="form-control"></textarea>
                    </div>
                </div>
                
                <!-- Address -->
                <div class="col-12">
                    <div class="form-group">
                        <strong>Business Address:</strong>
                        <textarea name="address" rows="3" placeholder="Enter your full business address" class="form-control"></textarea>
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
