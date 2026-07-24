<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
        
        <?php
            $p_price=getProductDiscount($product);
            $discount_price=$p_price['discount_price'];
            $discount=$p_price['discount'];
        
        ?>

        <!-- Header -->
        <div class="modal-header bg-light">
            <h5 class="modal-title fw-bold">
                Product Details
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- Body -->
        <div class="modal-body">
            <div class="container-fluid">

                <!-- BASIC INFO -->
                <div class="row g-3 mb-3">

                    <!-- Product Info -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header fw-bold">
                                Basic Information
                            </div>
                            <div class="card-body small">
                                <p><b>Name:</b> <?php echo e($product->name); ?></p>
                                <p><b>Name (Bangla):</b> <?php echo e($product->name_bangla); ?></p>
                                <p><b>SKU:</b> <?php echo e($product->sku); ?></p>
                                <p><b>Type:</b> <?php echo e(ucfirst($product->type)); ?></p>
                                <p><b>Stock Manage:</b> <?php echo e($product->stock_manage ? 'Yes' : 'No'); ?></p>
                                <?php if($product->stock_manage): ?>
                                <p><b>Current Stock :</b> <?php echo e($product->stocks->sum('qty_available')); ?></p>
                                <?php endif; ?>
                                <p><b>Stock Alert:</b> <?php echo e($product->stock_alert); ?></p>
                                <p><b>Ecommerce:</b> <?php echo e($product->is_ecom == '1' ? 'Active':'De-active'); ?></p>
                                <p><b>Featured:</b> <?php echo e($product->is_feature == '1' ? 'Active':'De-active'); ?></p>
                                <p><b>Trending:</b> <?php echo e($product->is_reco == '1' ? 'Active':'De-active'); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing & Category -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header fw-bold">
                                Pricing & Category
                            </div>
                            <div class="card-body small">
                                <p><b>Purchase Price:</b> <?php echo e(priceFormate($product->purchase_price)); ?></p>
                                <p><b>MRP:</b> <?php echo e(priceFormate($product->sell_price)); ?></p>
                                <p><b>Category:</b> <?php echo e($product->category->name ?? 'N/A'); ?></p>
                                <p><b>Sub Category:</b> <?php echo e($product->subcategory->name ?? 'N/A'); ?></p>
                                <p><b>Brand:</b> <?php echo e($product->brand->name ?? 'N/A'); ?></p>
                                <p><b>Unit:</b> <?php echo e($product->unit->name ?? 'N/A'); ?></p>
                                
                                <?php if($discount_price>0): ?>
                                    <p> Discount Advailable : <b> <?php echo e($discount->title); ?> </b> <br>
                                    <span class="text-red"><?php echo e(priceFormate($product->sell_price - $discount_price)); ?></span>
                                    <del class="text-muted"><?php echo e(priceFormate($product->sell_price)); ?></del>
                                    <span class="text-muted">(<?php echo e(number_format($discount->amount,0)); ?> <?php echo e($discount->discount_type =='percentage'?'%':'tk'); ?> off)</span>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Image & Video -->
                    <div class="col-lg-4 col-md-12">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header fw-bold">
                                Media
                            </div>
                            <div class="card-body text-center">
                                <img
                                    src="<?php echo e(getImage('products',$product->image)); ?>"
                                    class="img-fluid rounded mb-2"
                                    style="max-height:220px; object-fit:contain;"
                                >

                                <?php if($product->video_link): ?>
                                    <div class="ratio ratio-16x9 mt-2">
                                        <iframe
                                            src="https://www.youtube.com/embed/<?php echo e($product->video_link); ?>"
                                            allowfullscreen>
                                        </iframe>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- WARRANTY & RETURN -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header fw-bold">Warranty</div>
                            <div class="card-body small">
                                <p><b>Available:</b> <?php echo e($product->warranty_available ? 'Yes' : 'No'); ?></p>
                                <?php if($product->warranty_days): ?>
                                    <p><b>Days:</b> <?php echo e($product->warranty_days); ?></p>
                                <?php endif; ?>
                                <p><?php echo e($product->warranty_note); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header fw-bold">Return Policy</div>
                            <div class="card-body small">
                                <p><b>Available:</b> <?php echo e($product->return_available ? 'Yes' : 'No'); ?></p>
                                <?php if($product->return_days): ?>
                                    <p><b>Days:</b> <?php echo e($product->return_days); ?></p>
                                <?php endif; ?>
                                <p><?php echo e($product->return_note); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DESCRIPTION -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header fw-bold">Description</div>
                    <div class="card-body small">
                        <?php echo $product->description; ?>

                        <hr>
                        <?php echo $product->specification; ?>

                    </div>
                </div>

                <!-- GALLERY -->
               <?php if($product->images): ?>
                    <div class="card shadow-sm mb-3">
                        <div class="card-header fw-bold d-flex justify-content-between align-items-center">
                            <span>Product Gallery</span>
                            <span class="badge bg-secondary">
                                <?php echo e(count($product->images)); ?> Images
                            </span>
                        </div>
                    
                        <div class="card-body">
                            <div class="row g-3">
                                <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                    <div class="card h-100 position-relative shadow-sm border-0">
                    
                                        <!-- Delete Button -->
                                        <a href="<?php echo e(route('multiImageDelete', $img->id)); ?>"
                                           class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 delete"
                                           title="Delete">
                                            ✖
                                        </a>
                    
                                        <!-- Image -->
                                        <img
                                            src="<?php echo e(getImage('products',$img->image)); ?>"
                                            class="card-img-top rounded-top"
                                            style="height:140px; object-fit:cover;">
                    
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>


                <!-- VARIATIONS -->
                <?php if($product->type == 'variable'): ?>
                <div class="card shadow-sm">
                    <div class="card-header fw-bold">Product Variations</div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>SKU</th>
                                    <th>Purchase</th>
                                    <th>Sell</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $product->variations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($variation->name); ?></td>
                                    <td><?php echo e($variation->sub_sku); ?></td>
                                    <td><?php echo e($variation->purchase_price); ?></td>
                                    <td><?php echo e($variation->sell_price); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>

    </div>
</div>
<?php /**PATH E:\laragon\www\personal\as-multi-vendor-ecom\admin\resources\views/products/show.blade.php ENDPATH**/ ?>