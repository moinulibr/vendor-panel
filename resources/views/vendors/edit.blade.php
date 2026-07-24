<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <form action="{{ route('vendors.update', [$user->id]) }}" method="post" id="ajax_form">
        @csrf
        @method('PATCH')
        <div class="modal-header">
            <h1 class="modal-title">Edit Vendor</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row g-3">

                <!-- First & Last Name -->
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>First Name:</strong><span class="text-red">*</span>
                        <input type="text" name="name" value="{{ $user->name ?? '' }}" placeholder="First Name" class="form-control">
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Last Name:</strong>
                        <input type="text" name="last_name" value="{{ $user->last_name ?? '' }}" placeholder="Last Name" class="form-control">
                    </div>
                </div>

                <!-- Email & Mobile -->
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Email:</strong><span class="text-red">*</span>
                        <input type="email" name="email" value="{{ $user->email }}" placeholder="Email" class="form-control">
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Mobile:</strong><span class="text-red">*</span>
                        <input type="tel" name="mobile" value="{{ $user->mobile }}" placeholder="Mobile" class="form-control">
                    </div>
                </div>

                <!-- Shop Name & Trade License -->
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Shop Name:</strong><span class="text-red">*</span>
                        <input type="text" name="shop_name" value="{{ $vendorAddress->shop_name ?? '' }}" placeholder="Shop Name" class="form-control">
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Trade License:</strong><span class="text-red">*</span>
                        <input type="text" name="trade_license" value="{{ $vendorAddress->trade_license ?? '' }}" placeholder="Trade License" class="form-control">
                    </div>
                </div>

                <!-- Fax & Website -->
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>FAX:</strong>
                        <input type="text" name="fax" value="{{ $vendorAddress->fax ?? '' }}" placeholder="FAX" class="form-control">
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Website:</strong>
                        <input type="text" name="website" value="{{ $vendorAddress->website ?? '' }}" placeholder="https://www.example.com" class="form-control">
                    </div>
                </div>

                <!-- Password & Confirm Password -->
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Password:</strong>
                        <input type="password" name="password" placeholder="Password" class="form-control">
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Confirm Password:</strong>
                        <input type="password" name="password_confirmation" placeholder="Confirm Password" class="form-control">
                    </div>
                </div>

                <!-- Gender -->
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Gender:</strong>
                        <select name="gender" class="form-control">
                            <option value="male" {{ ($user->gender ?? '')=='male' ? 'selected':'' }}>Male</option>
                            <option value="female" {{ ($user->gender ?? '')=='female' ? 'selected':'' }}>Female</option>
                            <option value="other" {{ ($user->gender ?? '')=='other' ? 'selected':'' }}>Other</option>
                        </select>
                    </div>
                </div>

                <!-- Status -->
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Status:</strong>
                        <select name="status" class="form-control">
                            <option value="1" {{ $user->status=='1' ? 'selected':'' }}>Active</option>
                            <option value="0" {{ $user->status=='0' ? 'selected':'' }}>De-Active</option>
                        </select>
                    </div>
                </div>

                <!-- Date of Birth -->
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Date Of Birth:</strong>
                        <input type="date" name="dob" value="{{ $user->dob ?? '' }}" class="form-control">
                    </div>
                </div>

                <!-- Role -->
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Role:</strong>
                       <select name="roles[]" class="form-control">
                        @foreach ($roles as $value => $label)
                            <option value="{{ $value }}">
                                {{ $label }}
                            </option>
                         @endforeach
                    </select>
                    </div>
                </div>
                
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Our Mission:</strong>
                        <textarea name="our_mission" rows="5" placeholder="Enter your Our Mission" class="form-control">{{ $vendorAddress->our_mission ?? '' }}</textarea>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <strong>Our Vision:</strong>
                        <textarea name="our_vision" rows="5" placeholder="Enter your Our Vision" class="form-control">{{ $vendorAddress->our_vision ?? '' }}</textarea>
                    </div>
                </div>

                <!-- Business Address -->
                <div class="col-12">
                    <div class="form-group">
                        <strong>Business Address:</strong>
                        <textarea name="address" rows="3" placeholder="Enter your full business address" class="form-control">{{ $vendorAddress->address ?? '' }}</textarea>
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
