<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <form action="{{ route('variant_attributes.update',[$item->id])}}" method="post" id="ajax_form">
      @method('PATCH')
      @csrf
    <div class="modal-header">
      <h1 class="modal-title">Variant</h1>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
      
          <div class="col-12">
            <div class="form-group mb-3">
                <label> Variant Name </label>
                <input type="text" name="name" class="form-control" value="{{ $item->name }}"  placeholder="Enter Variant name "/>
            </div>

            <div class="form-group mb-3">
                <label> Variant Values</label>
                <input type="text" name="valus" class="form-control" value="{{ $item->valus }}"  placeholder="Enter variant values e.g.: Red, Black"/>
                <span class="tag-text d-flex text-danger">Enter value separated by comma</span>
            </div>

            <div class="form-group">
                <label> Status</label>
                <select class="form-control" name="status">
                  <option value="1" {{ '1'==$item->status ? 'selected':''}}>Active</option>
                  <option value="0" {{ '0'==$item->status ? 'selected':''}}>De-Active</option>
                </select>
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