<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <form action="{{ route('users.store')}}" method="post" id="ajax_form">
      @csrf
        <div class="modal-header">
          <h1 class="modal-title">User</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12 col-sm-6 col-lg-6">
                    <div class="form-group mb-3">
                        <strong>Name:</strong>
                        <input type="text" name="name" placeholder="Name" class="form-control">
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-6">
                    <div class="form-group mb-3">
                        <strong>Email:</strong>
                        <input type="email" name="email" placeholder="Email" class="form-control">
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-6">
                    <div class="form-group mb-3">
                        <strong>Password:</strong>
                        <input type="password" name="password" placeholder="Password" class="form-control">
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-6">
                    <div class="form-group mb-3">
                        <strong>Confirm Password:</strong>
                        <input type="password" name="confirm-password" placeholder="Confirm Password" class="form-control">
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-6">
                    <div class="form-group mb-3">
                        <strong>Role:</strong>
                        <select name="roles[]" class="form-control">
                            @foreach ($roles as $value => $label)
                                <option value="{{ $value }}">
                                    {{ $label }}
                                </option>
                             @endforeach
                        </select>
                    </div>
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