<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <form action="{{ route('product_features.update',[$product_feature->id])}}" method="post" id="ajax_form">
      @method('PATCH')
      @csrf
    <div class="modal-header">
      <h1 class="modal-title"> Top Menu </h1>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
      
          <div class="col-12">
            <div class="form-group mb-3">
                <label> Name </label>
                <input type="text" name="name" class="form-control" value="{{ $product_feature->name }}"  placeholder="Enter Top Menu"/>
            </div>
            
            <div class="form-group">
                <label> Status</label>
                <select class="form-control" name="status">
                  <option value="1" {{ '1'==$product_feature->status ? 'selected':''}}>Active</option>
                  <option value="0" {{ '0'==$product_feature->status ? 'selected':''}}>De-Active</option>
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