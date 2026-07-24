<div class="table-responsive">
	<table class="table ">
		<thead class="thead-light">
		    <th>SL</th>
			<th>Title</th>
			<th>Code</th>
			<th>Description</th>
			<th>Type</th>
			<th>Discount</th>
			<th>Valid</th>
			<th>End</th>
			<th>Status</th>
			<th class="no-sort">Action</th>
		</thead>
		<tbody>
			<?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<tr>
			    <td>
			        <?php echo e($i+1); ?>

			    </td>
				<td><?php echo e($item->title); ?></td>
				<td><?php echo e($item->code); ?></td>
				<td><?php echo e($item->note); ?></td>
				<td><?php echo e($item->discount_type); ?></td>
				<td><?php echo e($item->amount); ?></td>
				<td><?php echo e(dateFormate($item->start)); ?></td>
				<td><?php echo e(dateFormate($item->end)); ?></td>
				
				<td>
				    <?php if($item->status): ?>
				    <span class="badge table-badge bg-success fw-medium fs-10">Active</span>
				    <?php else: ?>
				    <span class="badge table-badge bg-warning fw-medium fs-10">De-Active</span>
				    <?php endif; ?>
				</td>
				
				<td class="action-table-data">
					<div class="edit-delete-action">
					    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('coupons.edit')): ?>
						<a class="me-2 p-2 btn_modal" href="<?php echo e(route('coupons.edit',[$item->id])); ?>">
							<i class="fa fa-edit"></i>
						</a>
						<?php endif; ?>
						
						<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('coupons.delete')): ?>
						<a  href="<?php echo e(route('coupons.destroy',[$item->id])); ?>" class="delete">
							<i class="fa fa-trash"></i>
						</a>
						<?php endif; ?>
					</div>
					
				</td>
			</tr>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</tbody>
	</table>
</div>
<p> <?php echo e($items->render()); ?> </p><?php /**PATH E:\laragon\www\personal\as-multi-vendor-ecom\admin\resources\views/coupons/data.blade.php ENDPATH**/ ?>