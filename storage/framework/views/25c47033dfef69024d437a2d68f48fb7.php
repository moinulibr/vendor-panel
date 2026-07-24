<?php $__env->startSection('content'); ?>

<div class="content">

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-2">
        <div class="mb-3">
            <h1 class="mb-1">Welcome, <?php echo e(getRole()); ?></h1>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-primary sale-widget flex-fill">
                <div class="input-icon-start position-relative">
					<input type="text" class="form-control bookingrange" placeholder="dd/mm/yyyy - dd/mm/yyyy">
					<span class="input-icon-left">
						<i class="ti ti-calendar"></i>
					</span>
				</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="clearfix"></div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-primary sale-widget flex-fill">
                <div class="card-body d-flex align-items-center">
                    <span class="sale-icon bg-white text-primary">
                        <i class="ti ti-file-text fs-24"></i>
                    </span>
                    <div class="ms-2">
                        <p class="text-white mb-1">Total Sales</p>
                        <div class="d-inline-flex align-items-center flex-wrap gap-2">
                            <h4 class="text-white total_sell"> 0 </h4>
                            <span class="badge badge-soft-primary"><i class="ti ti-arrow-up me-1"></i>  </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-secondary sale-widget flex-fill">
                <div class="card-body d-flex align-items-center">
                    <span class="sale-icon bg-white text-secondary">
                        <i class="ti ti-repeat fs-24"></i>
                    </span>
                    <div class="ms-2">
                        <p class="text-white mb-1">Total Sales Return</p>
                        <div class="d-inline-flex align-items-center flex-wrap gap-2">
                            <h4 class="text-white total_sell_return"> 0 </h4>
                            <span class="badge badge-soft-danger"><i class="ti ti-arrow-down me-1"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-teal sale-widget flex-fill">
                <div class="card-body d-flex align-items-center">
                    <span class="sale-icon bg-white text-teal">
                        <i class="ti ti-gift fs-24"></i>
                    </span>
                    <div class="ms-2">
                        <p class="text-white mb-1">Total Purchase</p>
                        <div class="d-inline-flex align-items-center flex-wrap gap-2">
                            <h4 class="text-white total_purchase">0</h4>
                            <span class="badge badge-soft-success"><i class="ti ti-arrow-up me-1"></i>0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-info sale-widget flex-fill">
                <div class="card-body d-flex align-items-center">
                    <span class="sale-icon bg-white text-info">
                        <i class="ti ti-brand-pocket fs-24"></i>
                    </span>
                    <div class="ms-2">
                        <p class="text-white mb-1">Total Purchase Return</p>
                        <div class="d-inline-flex align-items-center flex-wrap gap-2">
                            <h4 class="text-white total_purchase_return">0</h4>
                            <span class="badge badge-soft-success"><i class="ti ti-arrow-up me-1"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row">

        <!-- Profit -->
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card revenue-widget flex-fill">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                        <div>
                            <h4 class="mb-1 total_profit"> 0 </h4>
                            <p>Profit</p>
                        </div>
                        <span class="revenue-icon bg-cyan-transparent text-cyan">
                            <i class="fa-solid fa-layer-group fs-16"></i>
                        </span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-0"><span class="fs-13 fw-bold text-success"></span> vs Last Month</p>
                        <a href="#" class="text-decoration-underline fs-13 fw-medium">View All</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Profit -->

        <!-- Invoice -->
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card revenue-widget flex-fill">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                        <div>
                            <h4 class="mb-1 total_sell_due">0</h4>
                            <p>Invoice Due</p>
                        </div>
                        <span class="revenue-icon bg-teal-transparent text-teal">
                            <i class="ti ti-chart-pie fs-16"></i>
                        </span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-0"><span class="fs-13 fw-bold text-success">+35%</span> vs Last Month</p>
                        <a href="#" class="text-decoration-underline fs-13 fw-medium">View All</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Invoice -->

        <!-- Expenses -->
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card revenue-widget flex-fill">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                        <div>
                            <h4 class="mb-1 total_expense">0</h4>
                            <p>Total Expenses</p>
                        </div>
                        <span class="revenue-icon bg-orange-transparent text-orange">
                            <i class="ti ti-lifebuoy fs-16"></i>
                        </span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-0"><span class="fs-13 fw-bold text-success">+41%</span> vs Last Month</p>
                        <a href="<?php echo e(url('/expenses')); ?>" class="text-decoration-underline fs-13 fw-medium">View All</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Expenses -->

        <!-- Returns -->
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card revenue-widget flex-fill">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                        <div>
                            <h4 class="mb-1">0</h4>
                            <p>Total Payment Returns</p>
                        </div>
                        <span class="revenue-icon bg-indigo-transparent text-indigo">
                            <i class="ti ti-hash fs-16"></i>
                        </span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-0"><span class="fs-13 fw-bold text-danger">-20%</span> vs Last Month</p>
                        <a href="#" class="text-decoration-underline fs-13 fw-medium">View All</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Returns -->

    </div>
</div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('js'); ?>


<script type="text/javascript">
  $(document).ready(function () {
    
    $(document).on('bookingRangeChanged', function (e, data) {
        
        
        getData();
    
    });
  
    function getData(){
        let date=$('.bookingrange').val();
        $.ajax({
            url: '<?php echo e(route("dashboardData")); ?>',
            type: 'GET',
            data:{date},
            dataType: 'json',
            success: function(data) {
                if(data.total_sell){
                    $('.total_sell').text(data.total_sell);
                }
                
                if(data.total_sell_return){
                    $('.total_sell_return').text(data.total_sell_return);
                }
                
                
                if(data.total_purchase){
                    $('.total_purchase').text(data.total_purchase);
                }
                
                if(data.total_sell_due){
                    $('.total_sell_due').text(data.total_sell_due);
                }
                
                if(data.total_purchase_due){
                    $('.total_purchase_due').text(data.total_purchase_due);
                }
                
                if(data.total_expense){
                    $('.total_expense').text(data.total_expense);
                }
                
                if(data.total_income){
                    $('.total_income').text(data.total_income);
                }
                
                
                
            }
        });
    }
  });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\personal\as-multi-vendor-ecom\admin\resources\views/home.blade.php ENDPATH**/ ?>