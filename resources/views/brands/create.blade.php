<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <form action="{{ route('brands.update',[$brand->id])}}" method="post" id="ajax_form">
      @method('PATCH')
      @csrf
    <div class="modal-header">
      <h1 class="modal-title">Brand</h1>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
      
          <div class="col-12">
            <div class="form-group mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ $brand->name }}" placeholder="Enter brand name "/>
            </div>
            <div class="form-group mb-3">
                <label>Name Bangla</label>
                <input type="text" name="bd_name" class="form-control" value="{{ $brand->bd_name }}" placeholder="Enter bangla brand name "/>
            </div>

            <div class="form-group mb-3">
                <label>Image</label>
                <input type="file" name="image" class="form-control" />
            </div>
            
            <div class="form-group">
                <label> Status</label>
                <select class="form-control" name="status">
                  <option value="1" {{ '1'==$brand->status ? 'selected':''}}>Active</option>
                  <option value="0" {{ '0'==$brand->status ? 'selected':''}}>De-Active</option>
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