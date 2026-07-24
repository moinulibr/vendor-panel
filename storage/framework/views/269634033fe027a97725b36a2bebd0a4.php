<div id="productModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <form action="<?php echo e(route('products.update',[$product->id])); ?>" method="post" id="ajax_form">
          <?php echo method_field('PATCH'); ?>
          <?php echo csrf_field(); ?>
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
                  <input type="text" name="name" class="form-control" value="<?php echo e($product->name); ?>" placeholder="Enter product name"/>
                </div>
              </div>
    
              <!-- Name Bangla -->
              <div class="col-12 col-md-6 col-lg-4">
                <div class="form-group">
                  <label>
                    Name Bangla
                    <small class="text-muted fs-10">(This name will appear on SR Panel)</small>
                  </label>
                  <input type="text" name="name_bangla" class="form-control" value="<?php echo e($product->name_bangla); ?>" placeholder="Enter product bangla name"/>
                </div>
              </div>
    
              <!-- SKU -->
              <div class="col-12 col-md-6 col-lg-4">
                <div class="form-group">
                  <label>Sku</label>
                  <input type="text" name="sku" class="form-control main_sku" value="<?php echo e($product->sku ?? $product->id); ?>" placeholder="Enter Sku"/>
                </div>
              </div>
    
              <!-- Stock Alert -->
              <div class="col-12 col-md-6 col-lg-4">
                <div class="form-group">
                  <label>Stock Alert</label>
                  <input type="text" name="stock_alert" class="form-control" value="<?php echo e($product->stock_alert); ?>" placeholder="Enter Stock Alert"/>
                </div>
              </div>
    
              <!-- Purchase Price -->
              <div class="col-12 col-md-6 col-lg-4">
                <div class="form-group">
                  <label>Purchase Price</label>
                  <input type="text" name="purchase_price" class="form-control" value="<?php echo e($product->purchase_price); ?>" placeholder="Enter Purchase Price"/>
                </div>
              </div>
    
              <!-- Sell Price -->
              <div class="col-12 col-md-6 col-lg-4">
                <div class="form-group">
                  <label>MRP</label>
                  <input type="text" name="sell_price" class="form-control" value="<?php echo e($product->sell_price); ?>" placeholder="Enter MRP"/>
                </div>
              </div>
    
              <!-- Wholesale Price -->
              <div class="col-12 col-md-6 col-lg-4 d-none">
                <div class="form-group">
                  <label>Wholesale Price</label>
                  <input type="text" name="wholesale_price" class="form-control" value="<?php echo e($product->wholesale_price); ?>" placeholder="Enter Wholesale Price"/>
                </div>
              </div>
    
              <!-- Category -->
              <div class="col-12 col-md-6 col-lg-4">
                <div class="form-group">
                  <label>Category</label>
                  <select class="form-control category_id select2" name="category_id">
                    <option value="">Select One</option>
                    <?php $__currentLoopData = $cats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cat->id); ?>" <?php echo e($cat->id==$product->category_id ? 'selected':''); ?>> <?php echo e($cat->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </select>
                </div>
              </div>
    
              <!-- Sub Category -->
              <div class="col-12 col-md-6 col-lg-4">
                <div class="form-group">
                  <label>Sub Category</label>
                  <select class="form-control select2 sub_category_id" name="sub_category_id">
                    <option value="">Select One</option>
                    <?php $__currentLoopData = $sub_cats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub_cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($sub_cat->id); ?>" <?php echo e($sub_cat->id==$product->sub_category_id ? 'selected':''); ?>> <?php echo e($sub_cat->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </select>
                </div>
              </div>
    
              <!-- Brand -->
              <div class="col-12 col-md-6 col-lg-4">
                <div class="form-group">
                  <label>Brand</label>
                  <select class="form-control" name="brand_id">
                    <option value="">Select One</option>
                    <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($brand->id); ?>" <?php echo e($brand->id==$product->brand_id ? 'selected':''); ?>> <?php echo e($brand->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </select>
                </div>
              </div>
    
              <!-- Unit -->
              <div class="col-12 col-md-6 col-lg-4">
                <div class="form-group">
                  <label>Unit</label>
                  <select class="form-control" name="unit_id">
                    <option value="">Select One</option>
                    <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($unit->id); ?>" <?php echo e($unit->id==$product->unit_id ? 'selected':''); ?>> <?php echo e($unit->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </select>
                </div>
              </div>
    
              <!-- Vendor -->
              <div class="col-12 col-md-6 col-lg-4">
                <div class="form-group">
                  <label>Vendor</label>
                  <select class="form-control" name="user_id">
                    <option value="">Select One</option>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($user->id); ?>" <?php echo e($user->id==$product->user_id ? 'selected':''); ?>> <?php echo e($user->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                        <input class="form-check-input" type="radio" name="stock_manage" id="active" value="1" <?php echo e(1==$product->stock_manage || empty($product->name) ? 'checked':''); ?>>
                        <label class="form-check-label" for="active">Yes</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="stock_manage" id="inactive" value="" <?php echo e(''==$product->stock_manage && $product->name ? 'checked':''); ?>>
                        <label class="form-check-label" for="inactive">No</label>
                      </div>
                    </div>
                </div>
                
                <!-- Ecommerce, Featured, Trending -->
                <div class="col-12 col-md-6 col-lg-4">
                  <div class="form-group">
                    <label>Product Flags</label><br>
                
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox" name="is_ecom" id="is_ecom" value="1" <?php echo e(1==$product->is_ecom ? 'checked' : ''); ?>>
                      <label class="form-check-label" for="is_ecom">Ecommerce</label>
                    </div>
                
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox" name="is_feature" id="is_feature" value="1" <?php echo e(1==$product->is_feature ? 'checked' : ''); ?>>
                      <label class="form-check-label" for="is_feature">Featured</label>
                    </div>
                
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox" name="is_reco" id="is_reco" value="1" <?php echo e(1==$product->is_reco ? 'checked' : ''); ?>>
                      <label class="form-check-label" for="is_reco">Trending</label>
                    </div>
                
                  </div>
                </div>
              
                <div class="col-12 col-md-6 col-lg-2">
                    <div class="form-group">
                        <label> Estimate Delivery Day </label><br>
                        <input type="number" name="estimate_delivery_day" class="form-control" value="<?php echo e($product->estimate_delivery_day); ?>"/>
                    </div>
                </div>
    
              <!-- Warranty Available -->
              <div class="col-12 col-md-6 col-lg-3">
                <div class="form-check mt-3">
                  <input class="form-check-input" type="checkbox" value="1" name="warranty_available" id="warranty_available" <?php echo e(1==$product->warranty_available ? 'checked':''); ?>>
                  <label class="form-check-label" for="warranty_available">Warranty Available</label>
                </div>
              </div>
    
              <!-- Warranty Days -->
              <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                  <label>Warranty Days</label>
                  <input class="form-control" type="number" name="warranty_days" value="<?php echo e($product->warranty_days); ?>">
                </div>
              </div>
    
              <!-- Warranty Note -->
              <div class="col-12 col-md-6 col-lg-6">
                <div class="form-group">
                  <label>Warranty Note</label>
                  <textarea class="form-control" name="warranty_note" rows="3"><?php echo e($product->warranty_note); ?></textarea>
                </div>
              </div>
    
              <!-- Return Available -->
              <div class="col-12 col-md-6 col-lg-3">
                <div class="form-check mt-3">
                  <input class="form-check-input" type="checkbox" value="1" name="return_available" id="return_available" <?php echo e(1==$product->return_available ? 'checked':''); ?>>
                  <label class="form-check-label" for="return_available">Return Available</label>
                </div>
              </div>
    
              <!-- Return Days -->
              <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                  <label>Return Days</label>
                  <input class="form-control" type="number" name="return_days" value="<?php echo e($product->return_days); ?>">
                </div>
              </div>
    
              <!-- Return Note -->
              <div class="col-12 col-md-6 col-lg-6">
                <div class="form-group">
                  <label>Return Note</label>
                  <textarea class="form-control" name="return_note" rows="3"><?php echo e($product->return_note); ?></textarea>
                </div>
              </div>
    
              <!-- Description -->
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label>Description</label>
                  <textarea class="form-control" name="description" id="editor" rows="6"><?php echo $product->description; ?></textarea>
                </div>
              </div>
    
              <!-- Specification -->
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label>Specification</label>
                  <textarea class="form-control" name="specification" id="editor2" rows="6"><?php echo $product->specification; ?></textarea>
                </div>
              </div>
    
              <!-- Youtube Video Link -->
              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label>Youtube Video Link</label>
                  <input class="form-control" type="text" name="video_link" value="<?php echo e($product->video_link); ?>" placeholder="Enter Youtube Video Link">
                </div>
              </div>
    
              <!-- Type -->
              <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                  <label>Type</label>
                  <select class="form-control product_type" name="type">
                    <option value="single" <?php echo e('single'==$product->type ? 'selected':''); ?>>Single</option>
                    <option value="variable" <?php echo e('variable'==$product->type ? 'selected':''); ?>>Variable</option>
                  </select>
                </div>
              </div>
    
              <!-- Status -->
              <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                  <label>Status</label>
                  <select class="form-control" name="status">
                    <option value="1" <?php echo e('1'==$product->status ? 'selected':''); ?>>Active</option>
                    <option value="0" <?php echo e('0'==$product->status ? 'selected':''); ?>>De-Active</option>
                  </select>
                </div>
              </div>
    
              <!-- Variable Section (remains full width on all devices) -->
              <div class="col-12 mt-4 variable_section">
                <?php $__currentLoopData = $variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                  $values = explode(",", $variant->valus);
                ?>
                <div class="row align-items-center mb-3">
                  <div class="col-12 col-md-3">
                    <h5 class="fw-semibold mb-0 variants"><?php echo e($variant->name); ?></h5>
                  </div>
                  <div class="col-12 col-md-9">
                    <?php $__currentLoopData = $values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input <?php echo e($variant->name); ?>" <?php echo e(!empty($newarr) && in_array($value, $newarr) ? 'checked' : ''); ?> type="checkbox" value="<?php echo e($value); ?>" id="<?php echo e($value); ?>">
                      <label class="form-check-label" for="<?php echo e($value); ?>"><?php echo e($value); ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </div>
                </div>
                <hr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    
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
                      <?php $__currentLoopData = $product->variations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $variation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <tr class="<?php echo e($variation->name); ?>">
                        <td>
                          <?php echo e($variation->name); ?>

                          <input type="hidden" name="variations[<?php echo e($index); ?>][name]" value="<?php echo e($variation->name); ?>">
                        </td>
                        <td>
                          <input type="text" class="form-control" name="variations[<?php echo e($index); ?>][sub_sku]" value="<?php echo e($variation->sub_sku); ?>">
                        </td>
                        <td>
                          <input type="number" class="form-control" name="variations[<?php echo e($index); ?>][purchase_price]" step="0.01" value="<?php echo e($variation->purchase_price); ?>" required>
                        </td>
                        <td>
                          <input type="number" class="form-control" name="variations[<?php echo e($index); ?>][sell_price]" step="1" value="<?php echo e($variation->sell_price); ?>" required>
                        </td>
                      </tr>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                  </table>
                </div>
              </div>
    
              <textarea name="variants" class="variant_values" style="display: none"><?php echo e($product->variants); ?></textarea>
            </div>
          </div>
    
          <div class="modal-footer d-flex gap-2">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
</div><?php /**PATH E:\laragon\www\personal\as-multi-vendor-ecom\admin\resources\views/products/create.blade.php ENDPATH**/ ?>