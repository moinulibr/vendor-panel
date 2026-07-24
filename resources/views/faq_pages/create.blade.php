<div class="modal-dialog modal-lg modal-dialog-centered">
  <div class="modal-content">
    <form action="{{ route('faq_pages.update', [$item->id]) }}" method="post" id="ajax_form">
      @method('PATCH')
      @csrf

      <!-- Modal Header -->
      <div class="modal-header">
        <h5 class="modal-title">FAQ Page</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
        <div class="row g-3">

          <!-- Title + Status Row -->
          <div class="col-12 d-flex flex-wrap gap-2">
            <div class="flex-grow-1" style="min-width: 70%;">
              <label class="form-label">Title</label>
              <input type="text" name="title" class="form-control" value="{{ $item->title }}" />
            </div>

            <div style="width: 25%;">
              <label class="form-label">Status</label>
              <select class="form-control" name="status">
                <option value="1" {{ '1'==$item->status ? 'selected':'' }}>Active</option>
                <option value="0" {{ '0'==$item->status ? 'selected':'' }}>De-Active</option>
              </select>
            </div>
          </div>

          <!-- Description Row -->
          <div class="col-12">
            <label class="form-label">Description</label>
            <textarea name="description" id="editor" class="form-control">{!! $item->description !!}</textarea>
          </div>

        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>

    </form>
  </div>
</div>
