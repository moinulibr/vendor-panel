<div class="table-responsive">
	<table class="table ">
		<thead class="thead-light">
			<tr>
			    <th class="no-sort">SL</th>
			    <th class="no-sort">Action</th>
				<th class="no-sort">
					<label class="checkboxs">
						<input type="checkbox" id="select-all">
						<span class="checkmarks"></span>
					</label>
				</th>
				<th>Product</th>
				<th>SKU</th>
				<th>Type</th>
				<th>Category</th>
				<th>Brand</th>
				<th>Purchse Price</th>
				<th>MRP</th>
				<th>Unit</th>
				<th>Qty</th>
				<th>Ecommerce</th>
				<th>Featured</th>
				<th>Trending</th>
				<th> Vendor </th>
				<th class="no-sort">Status</th>
				<th> Created Date </th>
				
			</tr>
		</thead>
		<tbody>
			<?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<tr>
			    <td>
			        <?php echo e($i+1); ?>

			    </td>
			    <td class="action-table-data">
                    <div class="dropdown action-dropdown-wrap">
                        <button class="btn btn-sm btn-icon"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                            <i class="fa fa-ellipsis-h"></i>
                        </button>
                
                        <ul class="dropdown-menu dropdown-menu-end action-dropdown">
                            <li>
                                <a class="dropdown-item btn_modal" href="<?php echo e(route('products.show',[$item->id])); ?>">
                                    <i class="fa fa-eye"></i>
                                    <span>View</span>
                                </a>
                            </li>
                            
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('products.edit')): ?>
                            <li>
                                <a class="dropdown-item btn_modal" href="<?php echo e(route('products.edit',[$item->id])); ?>">
                                    <i class="fa fa-edit"></i>
                                    <span>Edit</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('products.delete')): ?>
                            <li>
                                <a class="dropdown-item text-danger delete" href="<?php echo e(route('products.destroy',[$item->id])); ?>">
                                    <i class="fa fa-trash"></i>
                                    <span>Delete</span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </td>
                
				<td>
					<label class="checkboxs">
						<input type="checkbox" value="<?php echo e($item->id); ?>" class="checkbox">
						<span class="checkmarks"></span>
					</label>
				</td>
				<td>
					<div class="d-flex align-items-center">
						<a href="<?php echo e(route('products.show',[$item->id])); ?>" class="btn_modal avatar avatar-md bg-light-900 p-1 me-2">
							<img class="object-fit-contain" src="<?php echo e(getImage('products',$item->image)); ?>" alt="img">
						</a>
						<a href="javascript:void(0);"><?php echo e($item->name); ?></a>
					</div>
				</td>
				<td><?php echo e($item->sku); ?></td>
				<td><?php echo e($item->type); ?></td>
				<td><?php echo e($item->category->name ?? ''); ?></td>
				<td><?php echo e($item->brand->name ?? ''); ?></td>
				<td><?php echo e(priceFormate($item->purchase_price)); ?></td>
				<td><?php echo e(priceFormate($item->sell_price)); ?></td>
				<td><?php echo e($item->unit->name ?? ''); ?></td>
				<td><?php echo e($item->stock ?? 0); ?></td>
				<td><?php echo e($item->is_ecom == '1' ? 'Active':'De-active'); ?></td>
				<td><?php echo e($item->is_feature == '1' ? 'Active':'De-active'); ?></td>
				<td><?php echo e($item->is_reco == '1' ? 'Active':'De-active'); ?></td>
				<td><?php echo e($item->user->name ??''); ?></td>
				<td>
				    <?php if($item->status): ?>
				    <span class="badge table-badge bg-success fw-medium fs-10">Active</span>
				    <?php else: ?>
				    <span class="badge table-badge bg-warning fw-medium fs-10">De-Active</span>
				    <?php endif; ?>
				    
				</td>
				
              	<td><?php echo e($item->created_at->format('d-m-Y h:i:s A')); ?></td>
				
			</tr>

			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</tbody>
	</table>
</div>
<p> <?php echo e($items->render()); ?> </p>
<?php /**PATH E:\laragon\www\personal\as-multi-vendor-ecom\admin\resources\views/products/data.blade.php ENDPATH**/ ?>