<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
  <div class="modal-content">
    <form action="{{ route('sliders.update',[$slider->id])}}" method="post" id="ajax_form" enctype="multipart/form-data">
      @method('PATCH')
      @csrf

      <div class="modal-header">
        <h5 class="modal-title">Slider</h5>
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
              placeholder="Enter slider title"
              value="{{ $slider->title }}"
            />
          </div>

          <!-- Link -->
          <div class="col-12 col-lg-6">
            <label class="form-label">Link</label>
            <input
              type="text"
              name="link"
              class="form-control"
              placeholder="Enter URL"
              value="{{ $slider->link }}"
            />
          </div>

          <!-- Image -->
          <div class="col-12 col-lg-6">
            <label class="form-label">Image</label>
            <input type="file" name="image" class="form-control" />
            <small class="text-muted">Optional (JPG, PNG)</small>
          </div>

          <!-- Type -->
          <div class="col-12 col-lg-6">
            <label class="form-label">Type</label>
            <select class="form-select" name="type">
              <option value="1" {{ '1' == $slider->type ? 'selected' : '' }}>Slider</option>
              <option value="2" {{ '2' == $slider->type ? 'selected' : '' }}>Mini Slider</option>
              <option value="3" {{ '3' == $slider->type ? 'selected' : '' }}>Mini Banner</option>
            </select>
          </div>

          <!-- Status -->
          <div class="col-12 col-lg-6">
            <label class="form-label">Status</label>
            <select class="form-select" name="status">
              <option value="1" {{ '1' == $slider->status ? 'selected' : '' }}>Active</option>
              <option value="0" {{ '0' == $slider->status ? 'selected' : '' }}>De-Active</option>
            </select>
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
