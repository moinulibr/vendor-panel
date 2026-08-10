<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <form action="{{ route('users.update',[$user->id])}}" method="post" id="ajax_form">
      @method('PATCH')
      @csrf
    <div class="modal-header">
      <h1 class="modal-title"> User Update</h1>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group mb-3">
                    <strong>Name:</strong>
                    <input type="text" name="name" value="{{ $user->name}}" placeholder="Name" class="form-control">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group mb-3">
                    <strong>Email:</strong>
                    <input type="email" name="email" value="{{ $user->email}}" placeholder="Email" class="form-control">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group mb-3">
                    <strong>Password:</strong>
                    <input type="password" name="password" placeholder="Password" class="form-control">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group mb-3">
                    <strong>Confirm Password:</strong>
                    <input type="password" name="confirm-password" placeholder="Confirm Password" class="form-control">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group mb-3">
                    <strong>Role:</strong>
                    <select name="roles[]" class="form-control">
                        @foreach ($roles as $value => $label)
                            <option value="{{ $value }}" {{ in_array($label, $userRole) ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                         @endforeach
                    </select>
                </div>
            </div>
            
            <div class="form-group mb-3">
                <label> Status</label>
                <select class="form-control" name="status">
                  <option value="1" {{ '1'==$user->status ? 'selected':''}}>Active</option>
                  <option value="0" {{ '0'==$user->status ? 'selected':''}}>De-Active</option>
                </select>
            </div>
            
            
        </div>
    
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="margin-right:4px;">Close</button>
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
    </form>
  </div>
</div>