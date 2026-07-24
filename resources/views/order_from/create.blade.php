<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <form action="{{ route('order_from.store')}}" method="post" id="ajax_form">
      @csrf
    <div class="modal-header">
      <h1 class="modal-title">Order From</h1>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
      
          <div class="col-12">
            <div class="form-group mb-3">
                <label> Title</label>
                <input type="text" name="title" class="form-control"  placeholder="Enter Title "/>
            </div>
            
            <div class="form-group">
                <label> Status</label>
                <select class="form-control" name="status">
                  <option value="1" >Active</option>
                  <option value="0">De-Active</option>
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