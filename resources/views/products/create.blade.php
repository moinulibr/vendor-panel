<div id="productModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <form action="{{ route('products.update',[$product->id])}}" method="post" id="ajax_form">
          @method('PATCH')
          @csrf
          <div class="modal-header">
            <h1 class="modal-title">Product</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row g-3"> <!-- g-3 for better spacing on mobile -->
    
              <!-- Name -->
              <div class="col-12 col-md-6 col-lg-4">
                <div class="form-group">
                  <label>Name</label>
                  <input type="text" name="name" class="form-control" value="{{ $product->name }}" placeholder="Enter product name"/>
                </div>
              </div>
    
              <!-- Name Bangla -->
              <div class="col-12 col-md-6 col-lg-4">
                <div class="form-group">
                  <label>
                    Name Bangla
                    <small class="text-muted fs-10">(This name will appear on SR Panel)</small>
                  </label>
                  <input type="text" name="name_bangla" class="form-control" value="{{ $product->name_bangla }}" placeholder="Enter product bangla name"/>
                </div>
              </div>
    
              <!-- SKU -->
              <div class="col-12 col-md-6 col-lg-4">
                <div class="form-group">
                  <label>Sku</label>
                  <input type="text" name="sku" class="form-control main_sku" value="{{ $product->sku ?? $product->id }}" placeholder="Enter Sku"/>
                </div>
              </div>
    
              <!-- Stock Alert -->
              <div class="col-12 col-md-6 col-lg-4">
                <div class="form-group">
                  <label>Stock Alert</label>
                  <input type="text" name="stock_alert" class="form-control" value="{{ $product->stock_alert }}" placeholder="Enter Stock Alert"/>
                </div>
              </div>
    
              <!-- Purchase Price -->
              <div class="col-12 col-md-6 col-lg-4">
                <div class="form-group">
                  <label>Purchase Price</label>
                  <input type="text" name="purchase_price" class="form-control" value="{{ $product->purchase_price }}" placeholder="Enter Purchase Price"/>
                </div>
              </div>
    
              <!-- Sell Price -->
              <div class="col-12 col-md-6 col-lg-4">
                <div class="form-group">
                  <label>MRP</label>
                  <input type="text" name="sell_price" class="form-control" value="{{ $product->sell_price }}" placeholder="Enter MRP"/>
                </div>
              </div>
    
              <!-- Wholesale Price -->
              <div class="col-12 col-md-6 col-lg-4 d-none">
                <div class="form-group">
                  <label>Wholesale Price</label>
                  <input type="text" name="wholesale_price" class="form-control" value="{{ $product->wholesale_price }}" placeholder="Enter Wholesale Price"/>
                </div>
              </div>
    
              <!-- Category -->
              <div class="col-12 col-md-6 col-lg-4">
                <div class="form-group">
                  <label>Category</label>
                  <select class="form-control category_id select2" name="category_id">
                    <option value="">Select One</option>
                    @foreach($cats as $cat)
                    <option value="{{ $cat->id}}" {{ $cat->id==$product->category_id ? 'selected':''}}> {{ $cat->name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
    
              <!-- Sub Category -->
              <div class="col-12 col-md-6 col-lg-4">
                <div class="form-group">
                  <label>Sub Category</label>
                  <select class="form-control select2 sub_category_id" name="sub_category_id">
                    <option value="">Select One</option>
                    @foreach($sub_cats as $sub_cat)
                    <option value="{{ $sub_cat->id}}" {{ $sub_cat->id==$product->sub_category_id ? 'selected':''}}> {{ $sub_cat->name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
    
              <!-- Brand -->
              <div class="col-12 col-md-6 col-lg-4">
                <div class="form-group">
                  <label>Brand</label>
                  <select class="form-control" name="brand_id">
                    <option value="">Select One</option>
                    @foreach($brands as $brand)
                    <option value="{{ $brand->id}}" {{ $brand->id==$product->brand_id ? 'selected':''}}> {{ $brand->name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
    
              <!-- Unit -->
              <div class="col-12 col-md-6 col-lg-4">
                <div class="form-group">
                  <label>Unit</label>
                  <select class="form-control" name="unit_id">
                    <option value="">Select One</option>
                    @foreach($units as $unit)
                    <option value="{{ $unit->id}}" {{ $unit->id==$product->unit_id ? 'selected':''}}> {{ $unit->name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
    
              <!-- Vendor -->
              <div class="col-12 col-md-6 col-lg-4">
                <div class="form-group">
                  <label>Vendor</label>
                  <select class="form-control" name="user_id">
                    <option value="">Select One</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id}}" {{ $user->id==$product->user_id ? 'selected':''}}> {{ $user->name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
    
              <!-- Image -->
              <div class="col-12 col-md-6 col-lg-4">
                <div class="form-group">
                  <label>Image</label>
                  <input type="file" name="image" class="form-control"/>
                </div>
              </div>
    
                <!-- Multi Image -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="form-group">
                      <label>Multi Image</label>
                      <input type="file" name="images[]" class="form-control" multiple/>
                    </div>
                </div>
    
                <!-- Stock Manage -->
                <div class="col-12 col-md-6 col-lg-2">
                    <div class="form-group">
                      <label>Manageable Stock</label><br>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="stock_manage" id="active" value="1" {{ 1==$product->stock_manage || empty($product->name) ? 'checked':''}}>
                        <label class="form-check-label" for="active">Yes</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="stock_manage" id="inactive" value="" {{ ''==$product->stock_manage && $product->name ? 'checked':''}}>
                        <label class="form-check-label" for="inactive">No</label>
                      </div>
                    </div>
                </div>
                
                <!-- Ecommerce, Featured, Trending -->
                <div class="col-12 col-md-6 col-lg-4">
                  <div class="form-group">
                    <label>Product Flags</label><br>
                
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox" name="is_ecom" id="is_ecom" value="1" {{ 1==$product->is_ecom ? 'checked' : '' }}>
                      <label class="form-check-label" for="is_ecom">Ecommerce</label>
                    </div>
                
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox" name="is_feature" id="is_feature" value="1" {{ 1==$product->is_feature ? 'checked' : '' }}>
                      <label class="form-check-label" for="is_feature">Featured</label>
                    </div>
                
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox" name="is_reco" id="is_reco" value="1" {{ 1==$product->is_reco ? 'checked' : '' }}>
                      <label class="form-check-label" for="is_reco">Trending</label>
                    </div>
                
                  </div>
                </div>
              
                <div class="col-12 col-md-6 col-lg-2">
                    <div class="form-group">
                        <label> Estimate Delivery Day </label><br>
                        <input type="number" name="estimate_delivery_day" class="form-control" value="{{ $product->estimate_delivery_day }}"/>
                    </div>
                </div>
    
              <!-- Warranty Available -->
              <div class="col-12 col-md-6 col-lg-3">
                <div class="form-check mt-3">
                  <input class="form-check-input" type="checkbox" value="1" name="warranty_available" id="warranty_available" {{ 1==$product->warranty_available ? 'checked':''}}>
                  <label class="form-check-label" for="warranty_available">Warranty Available</label>
                </div>
              </div>
    
              <!-- Warranty Days -->
              <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                  <label>Warranty Days</label>
                  <input class="form-control" type="number" name="warranty_days" value="{{ $product->warranty_days}}">
                </div>
              </div>
    
              <!-- Warranty Note -->
              <div class="col-12 col-md-6 col-lg-6">
                <div class="form-group">
                  <label>Warranty Note</label>
                  <textarea class="form-control" name="warranty_note" rows="3">{{ $product->warranty_note }}</textarea>
                </div>
              </div>
    
              <!-- Return Available -->
              <div class="col-12 col-md-6 col-lg-3">
                <div class="form-check mt-3">
                  <input class="form-check-input" type="checkbox" value="1" name="return_available" id="return_available" {{ 1==$product->return_available ? 'checked':''}}>
                  <label class="form-check-label" for="return_available">Return Available</label>
                </div>
              </div>
    
              <!-- Return Days -->
              <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                  <label>Return Days</label>
                  <input class="form-control" type="number" name="return_days" value="{{ $product->return_days}}">
                </div>
              </div>
    
              <!-- Return Note -->
              <div class="col-12 col-md-6 col-lg-6">
                <div class="form-group">
                  <label>Return Note</label>
                  <textarea class="form-control" name="return_note" rows="3">{{ $product->return_note }}</textarea>
                </div>
              </div>
    
              <!-- Description -->
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label>Description</label>
                  <textarea class="form-control" name="description" id="editor" rows="6">{!! $product->description !!}</textarea>
                </div>
              </div>
    
              <!-- Specification -->
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label>Specification</label>
                  <textarea class="form-control" name="specification" id="editor2" rows="6">{!! $product->specification !!}</textarea>
                </div>
              </div>
    
              <!-- Youtube Video Link -->
              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label>Youtube Video Link</label>
                  <input class="form-control" type="text" name="video_link" value="{{ $product->video_link}}" placeholder="Enter Youtube Video Link">
                </div>
              </div>
    
              <!-- Type -->
              <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                  <label>Type</label>
                  <select class="form-control product_type" name="type">
                    <option value="single" {{ 'single'==$product->type ? 'selected':''}}>Single</option>
                    <option value="variable" {{ 'variable'==$product->type ? 'selected':''}}>Variable</option>
                  </select>
                </div>
              </div>
    
              <!-- Status -->
              <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                  <label>Status</label>
                  <select class="form-control" name="status">
                    <option value="1" {{ '1'==$product->status ? 'selected':''}}>Active</option>
                    <option value="0" {{ '0'==$product->status ? 'selected':''}}>De-Active</option>
                  </select>
                </div>
              </div>
    
              <!-- Variable Section (remains full width on all devices) -->
              <div class="col-12 mt-4 variable_section">
                @foreach($variants as $variant)
                @php
                  $values = explode(",", $variant->valus);
                @endphp
                <div class="row align-items-center mb-3">
                  <div class="col-12 col-md-3">
                    <h5 class="fw-semibold mb-0 variants">{{ $variant->name }}</h5>
                  </div>
                  <div class="col-12 col-md-9">
                    @foreach($values as $value)
                    <div class="form-check form-check-inline">
                      <input class="form-check-input {{ $variant->name }}" {{ !empty($newarr) && in_array($value, $newarr) ? 'checked' : '' }} type="checkbox" value="{{ $value }}" id="{{ $value }}">
                      <label class="form-check-label" for="{{ $value }}">{{ $value }}</label>
                    </div>
                    @endforeach
                  </div>
                </div>
                <hr>
                @endforeach
    
                <button type="button" id="generate" class="btn btn-primary mb-3">Generate Variations</button>
    
                <div class="table-responsive">
                  <table class="table table-bordered" id="variationTable">
                    <thead class="table-light">
                      <tr>
                        <th>Variation</th>
                        <th>SKU (auto)</th>
                        <th>Purchase Price</th>
                        <th>Sell Price</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($product->variations as $index => $variation)
                      <tr class="{{ $variation->name }}">
                        <td>
                          {{ $variation->name }}
                          <input type="hidden" name="variations[{{$index}}][name]" value="{{ $variation->name }}">
                        </td>
                        <td>
                          <input type="text" class="form-control" name="variations[{{$index}}][sub_sku]" value="{{ $variation->sub_sku }}">
                        </td>
                        <td>
                          <input type="number" class="form-control" name="variations[{{$index}}][purchase_price]" step="0.01" value="{{ $variation->purchase_price }}" required>
                        </td>
                        <td>
                          <input type="number" class="form-control" name="variations[{{$index}}][sell_price]" step="1" value="{{ $variation->sell_price }}" required>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
    
              <textarea name="variants" class="variant_values" style="display: none">{{ $product->variants }}</textarea>
            </div>
          </div>
    
          <div class="modal-footer d-flex gap-2">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
</div>