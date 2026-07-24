<div class="modal-dialog modal-xl">
  <div class="modal-content">
    <div class="modal-header">
      <h1 class="modal-title"> Purchases </h1>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <form action="{{ route('purchases.update',[$transaction->id])}}" method="post" id="ajax_form">

      @method('PATCH')
      @csrf

      <div class="modal-body">
        <div class="row g-3"> <!-- g-3 = proper spacing -->
        
          <!-- Location -->
          <div class="col-12 col-sm-6 col-lg-3">
            <div class="form-group">
              <label class="form-label">
                Location <span class="text-danger">*</span>
              </label>
              <select class="form-control w-100" name="location_id">
                <option value="">Select</option>
                @foreach($locations as $location)
                  <option value="{{ $location->id }}"
                    {{ $transaction->location_id == $location->id ? 'selected' : '' }}>
                    {{ $location->name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
        
          <!-- Vendor -->
          <div class="col-12 col-sm-6 col-lg-2">
            <div class="form-group">
              <label class="form-label">Vendor <span class="text-danger">*</span></label>
              <select class="form-control w-100 vendor_id select2" name="vendor_id">
                <option value="">Select One</option>
                @foreach($users as $user)
                  <option value="{{ $user->id }}"
                    {{ $user->id == $transaction->vendor_id ? 'selected' : '' }}>
                    {{ $user->name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
        
          <!-- Supplier -->
          <div class="col-12 col-sm-6 col-lg-2">
            <div class="form-group">
              <label class="form-label">
                Supplier Name <span class="text-danger">*</span>
              </label>
              <select class="form-control w-100 contact_id select2" name="contact_id">
                <option value="">Select</option>
                @foreach($contacts as $contact)
                  <option value="{{ $contact->id }}"
                    {{ $transaction->contact_id == $contact->id ? 'selected' : '' }}>
                    {{ $contact->name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
        
          <!-- Date -->
          <div class="col-12 col-sm-6 col-lg-2">
            <div class="form-group">
              <label class="form-label">
                Date <span class="text-danger">*</span>
              </label>
              <div class="input-group calender-input">
                
                <input type="text"
                  class="form-control datetimepicker"
                  placeholder="dd/mm/yyyy"
                  name="transaction_date"
                  value="{{ $transaction->transaction_date ?? date('Y-m-d') }}">
              </div>
            </div>
          </div>
        
          <!-- Reference -->
          <div class="col-12 col-sm-6 col-lg-3">
            <div class="form-group">
              <label class="form-label">
                Reference <span class="text-danger">*</span>
              </label>
              <input type="text"
                class="form-control"
                name="invoice_no"
                placeholder="Enter the Reference"
                value="{{ $transaction->invoice_no }}">
            </div>
          </div>
        
        </div>

        <div class="row">
          <div class="col-lg-12">
            <div class="modal-body-table mt-3">
                <div class="col-lg-12">
                    <div class="mb-3">
                      <!--<label class="form-label">Product<span class="text-danger ms-1">*</span></label>-->
                      <input type="text" class="form-control" placeholder="Search Product" id="purchases_product">
                    </div>
                </div>
              <div class="table-responsive">
                <table class="table rounded-1" id="purchase_product">
                  <thead>
                    <tr>
                      <th class="bg-secondary-transparent p-3">Product</th>
                      <th class="bg-secondary-transparent p-3">Sku</th>
                      <th class="bg-secondary-transparent p-3">Qty</th>
                      <th class="bg-secondary-transparent p-3">Unit Cost($)</th>
                      <th class="bg-secondary-transparent p-3">Total Cost(%)</th>
                      <th class="bg-secondary-transparent p-3"> Action </th>
                    </tr>
                  </thead>

                  <tbody>
                      @foreach($transaction->lines as $line)
                        @include('purchases.edit_product_row', ['line'=>$line])
                      @endforeach
                  </tbody>
                </table>
              </div>
            </div>

          </div>
          <div class="row">
            
     
            <div class="col-lg-3 col-md-6 col-sm-12">
              <div class="mb-3">
                <label class="form-label">Shipping Charge<span class="text-danger ms-1">*</span></label>
                <input type="text" class="form-control shipping_charge" name="shipping_charge" value="{{ $transaction->shipping_charge}}">
              </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-12">
              <div class="mb-3">
                <label class="form-label">Total Amount<span class="text-danger ms-1">*</span></label>
                <input type="text" class="form-control final_amount" name="final_amount" value="{{ $transaction->final_amount}}">
              </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-12">
              <div class="mb-3">
                <label class="form-label">Status<span class="text-danger ms-1">*</span></label>
                <select class="form-control" name="shipping_status">
                    <option value="received" {{ $transaction->shipping_status=='received'?'selected':''}}>Received</option>
                    <option value="pending" {{ $transaction->shipping_status=='pending'?'selected':''}}>Pending</option>
                    
                    
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-12 mt-3">
          <div class="mb-3 summer-description-box">
            <label class="form-label"> Note </label>
            <textarea class="form-control" name="note">{{ $transaction->note}}</textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </form>
  </div>
</div>

