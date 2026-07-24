<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <form action="{{ route('site_visits.update',[$site_visit->id])}}" method="post" id="ajax_form">
      @method('PATCH')
      @csrf

      <div class="modal-header">
        <h5 class="modal-title">Add Site Visit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="row g-3">

            <!-- Project/Site Name -->
            <div class="col-12 col-sm-6">
                <label class="form-label">Project/Site Name<span class="text-danger ms-1">*</span></label>
                <input type="text" class="form-control" name="project_name" value="{{ $site_visit->project_name }}">
            </div>
    
            <!-- Reference -->
            <div class="col-12 col-sm-6">
                <label class="form-label">Reference</label>
                <input type="text" class="form-control" name="ref_no" value="{{ $site_visit->ref_no }}">
            </div>
    
            <!-- Contact Person -->
            <div class="col-12 col-sm-6">
                <label class="form-label">Contact Person</label>
                <input type="text" class="form-control" name="contact_person" value="{{ $site_visit->contact_person }}">
            </div>
    
            <!-- Mobile Number -->
            <div class="col-12 col-sm-6">
                <label class="form-label">Mobile Number</label>
                <input type="text" class="form-control" name="mobile" value="{{ $site_visit->mobile }}">
            </div>
    
            <!-- Visiting Date -->
            <div class="col-12 col-sm-6">
                <label class="form-label">Visiting Date<span class="text-danger ms-1">*</span></label>
                <div class="input-group">
                  <span class="input-group-text"><i data-feather="calendar"></i></span>
                  <input type="date" name="visiting_date" value="{{ $site_visit->visiting_date }}" class="form-control">
                </div>
            </div>
    
            <!-- Next Visiting Date -->
            <div class="col-12 col-sm-6">
                <label class="form-label">Next Visiting Date<span class="text-danger ms-1">*</span></label>
                <div class="input-group">
                  <span class="input-group-text"><i data-feather="calendar"></i></span>
                  <input type="date" name="next_visiting_date" value="{{ $site_visit->next_visiting_date }}" class="form-control">
                </div>
            </div>
              
            <!-- Address -->
            <div class="col-12 col-sm-6">
                <label class="form-label">Address</label>
                <textarea class="form-control" rows="2" name="address" placeholder="Address">{{ $site_visit->address }}</textarea>
            </div>
    
            <!-- Note -->
            <div class="col-12 col-sm-6">
                <label class="form-label">Note</label>
                <textarea class="form-control" rows="2" name="note" placeholder="Note">{{ $site_visit->note }}</textarea>
            </div>
    
            <!-- Description -->
            <div class="col-12 col-sm-6">
                <label class="form-label">Description</label>
                <textarea class="form-control" rows="2" name="description" placeholder="Description">{{ $site_visit->description }}</textarea>
            </div>
            <div class="col-12 col-sm-6 col-lg-6">
                <label class="form-label"> Status:</label>
                <select class="form-control" name="status">
                    <option value="1" {{ $site_visit->status == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $site_visit->status == 0 ? 'selected' : '' }}>De-Active</option>
                </select>
            </div>
        </div>
      </div>

      <div class="modal-footer d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </form>
  </div>
</div>
