<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <form action="{{ route('locations.update',[$location->id])}}" method="post" id="ajax_form" enctype="multipart/form-data">
      @method('PATCH')
      @csrf

      <div class="modal-header">
        <h5 class="modal-title">Location</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="row g-3">

            <!-- Name -->
            <div class="col-12 col-sm-6 col-lg-6">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter location name"
                     value="{{ $location->name }}" />
              </div>
    
            <!-- Code -->
            <div class="col-12 col-sm-6 col-lg-6">
                <label class="form-label">Code</label>
                <input type="text" name="code" class="form-control" placeholder="Enter location code"
                       value="{{ $location->code }}" />
            </div>
    
            <!-- Email -->
            <div class="col-12 col-sm-6 col-lg-6">
                <label class="form-label">Email</label>
                <input type="text" name="email" class="form-control" placeholder="Enter email address"
                       value="{{ $location->email }}" />
            </div>
    
            <!-- Mobile -->
            <div class="col-12 col-sm-6 col-lg-6">
                <label class="form-label">Mobile</label>
                <input type="text" name="mobile" class="form-control" placeholder="Enter mobile number"
                       value="{{ $location->mobile }}" />
            </div>
    
            <!-- Image -->
            <div class="col-12 col-sm-6 col-lg-6">
                <label class="form-label">Image</label>
                <input type="file" name="image" class="form-control" />
            </div>
          
            <div class="col-12 col-sm-6 col-lg-6">
                <label class="form-label"> Status:</label>
                <select class="form-control" name="status">
                    <option value="1" {{ $location->status == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $location->status == 0 ? 'selected' : '' }}>De-Active</option>
                </select>
            </div>
            
            <!-- Address -->
            <div class="col-12">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control" placeholder="Enter full address"
                       value="{{ $location->address }}" />
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
