<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
  <div class="modal-content">
    <form action="{{ route('pages.update',[$page->id])}}" method="post" id="ajax_form">
      @method('PATCH')
      @csrf

      <div class="modal-header">
        <h5 class="modal-title">Page</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Scrollable body -->
      <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
        <div class="row g-3">
          <!-- Title -->
          <div class="col-12 col-lg-6">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" placeholder="Enter page title" value="{{ $page->title }}" />
          </div>

          <!-- Page -->
          <div class="col-12 col-lg-3">
            <label class="form-label">Page</label>
            <select class="form-control" name="slug">
              @foreach($types as $k=>$p)
                <option value="{{$k}}" {{ $page->slug == $k ? 'selected' : '' }}>{{$p}}</option>
              @endforeach
            </select>
          </div>

          <!-- Status -->
          <div class="col-12 col-lg-3">
            <label class="form-label"> Status</label>
            <select class="form-control" name="status">
              <option value="1" {{ '1'==$page->status ? 'selected':''}}>Active</option>
              <option value="0" {{ '0'==$page->status ? 'selected':''}}>De-Active</option>
            </select>
          </div>

          <!-- Description -->
          <div class="col-12">
            <label class="form-label">Description</label>
            <textarea name="description" id="editor">{!! $page->description !!}</textarea>
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
