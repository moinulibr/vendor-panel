<?php

    $total_sell=DB::table('transactions')->where(['is_new'=>0,'type'=>'sell','is_pos'=>0,'shipping_status'=>'pending'])->count();
?>

<ul>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['sells.view', 'pos.view','vendor_orders.view','order_from.view'])): ?>
        <li class="submenu-open">
            <h6 class="submenu-hdr">Sales </h6>
            <ul>
                <li class="submenu">
                    <a class="<?php echo e(in_array(request()->segment(1), ['sells','pos','vendor-orders','order-from','sell-returns']) ?'subdrop':''); ?> " href="javascript:void(0);"><i class="ti ti-layout-grid fs-16 me-2"></i><span>Sales</span> 
                        <span class="badge badge bg-primary text-dark"> <?php echo e($total_sell); ?> </span>
                        
                         <span class="menu-arrow"></span></a>
                    <ul style="<?php echo e(in_array(request()->segment(1), ['sells','pos','vendor-orders','order-from']) ?'display:block':''); ?>">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sells.view')): ?>
                        <li class="<?php echo e(activeMeny('sells')); ?>"><a href="<?php echo e(route('sells.index')); ?>">Online Orders  <span class="badge badge bg-primary text-dark"> <?php echo e($total_sell); ?> </span></a></li>
                        <?php endif; ?>
                        
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('pos.view')): ?>
                        <li class="<?php echo e(activeMeny('pos')); ?>"><a href="<?php echo e(route('pos.index')); ?>">POS Orders</a></li>
                        <?php endif; ?>
                        
                        
                        
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('vendor_orders.view')): ?>
                        <!--<li class="hide <?php echo e(activeMeny('vendor-orders')); ?>"><a href="<?php echo e(route('vendor_orders.index')); ?>">Vendor Orders</a></li>-->
                        <?php endif; ?>
                        
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('order_from.view')): ?>
                        <li class="<?php echo e(activeMeny('order-from')); ?>"><a href="<?php echo e(route('order_from.index')); ?>">Order From Manage</a></li>
                        <?php endif; ?>
                        
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sells.view')): ?>
                        <li class="<?php echo e(activeMeny('sell-returns')); ?>"><a href="<?php echo e(route('sell_returns.index')); ?>"> Sell Return </span></a></li>
                        <?php endif; ?>
                        
                    </ul>
                </li>
                <!--<li><a href="invoice.html"><i class="ti ti-file-invoice fs-16 me-2"></i><span>Invoices</span></a></li>-->
                <!--<li><a href="sales-returns.html"><i class="ti ti-receipt-refund fs-16 me-2"></i><span>Sales Return</span></a></li>-->
                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('pos.view')): ?>
                <li class="<?php echo e(activeMeny('quotations')); ?>"><a href="<?php echo e(route('getQuotation')); ?>"><i class="ti ti-files fs-16 me-2"></i><span>Quotation</span></a></li>
                <?php endif; ?>
                
            </ul>
        </li>
    <?php endif; ?>
    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['purchases.view', 'purchase_return.view'])): ?>
    <li class="submenu-open">
        <h6 class="submenu-hdr">Purchases</h6>
        <ul>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchases.view')): ?>
            <li class="<?php echo e(activeMeny('purchases')); ?>"><a href="<?php echo e(route('purchases.index')); ?>"><i class="ti ti-shopping-bag fs-16 me-2"></i><span>Purchases</span></a></li>
            <?php endif; ?>
                    
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchase_return.view')): ?>
            <li><a href="purchase-returns.html"><i class="ti ti-file-upload fs-16 me-2"></i><span>Purchase Return</span></a></li>
            <?php endif; ?>
        </ul>
    </li>
    <?php endif; ?>
    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['products.view', 'variants.view','barcodes.view','categories.view'])): ?>
        <li class="submenu-open">
            <h6 class="submenu-hdr">Inventory</h6>
            <ul>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('products.view')): ?>
                <li class="<?php echo e(activeMeny('product')); ?>"><a href="<?php echo e(route('products.index')); ?>"><i data-feather="box"></i><span>Products</span></a></li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('categories.view')): ?>
                <li class="<?php echo e(activeMeny('category')); ?>"><a href="<?php echo e(route('categories.index')); ?>"><i class="ti ti-list-details fs-16 me-2"></i><span>Category</span></a></li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('brands.view')): ?>
                <li class="<?php echo e(activeMeny('brand')); ?>"><a href="<?php echo e(route('brands.index')); ?>"><i class="ti ti-triangles fs-16 me-2"></i><span>Brands</span></a></li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('units.view')): ?>
                <li class="<?php echo e(activeMeny('unit')); ?>"><a href="<?php echo e(route('units.index')); ?>"><i class="ti ti-brand-unity fs-16 me-2"></i><span>Units</span></a></li>
                <?php endif; ?>
                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('variants.view')): ?>
                <li class="<?php echo e(activeMeny('variant-attributes')); ?>"><a href="<?php echo e(route('variant_attributes.index')); ?> "><i class="ti ti-checklist fs-16 me-2"></i><span>Variant Attributes</span></a></li>
                <?php endif; ?>
                
                
                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('barcodes.view')): ?>
                <li class="<?php echo e(activeMeny('barcodes')); ?>"><a href="<?php echo e(route('barcodes.index')); ?>"><i class="ti ti-barcode fs-16 me-2"></i><span>Print Barcode</span></a></li>
                <?php endif; ?>
                
            </ul>
        </li>
    <?php endif; ?>
    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['stock_transfers.view', 'stock_adjustments.view'])): ?>
    <li class="submenu-open hide">
        <h6 class="submenu-hdr">Stock</h6>
        <ul>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stock_adjustments.view')): ?>
            <li class="<?php echo e(activeMeny('stock-adjustments')); ?>"><a href="<?php echo e(route('stock_adjustments.index')); ?>"><i class="ti ti-stairs-up fs-16 me-2"></i><span>Stock Adjustment</span></a></li>
            <?php endif; ?>
                    
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stock_transfers.view')): ?>
            <li class="<?php echo e(activeMeny('stock-transfers')); ?>"><a href="<?php echo e(route('stock_transfers.index')); ?>"><i class="ti ti-stack-pop fs-16 me-2"></i><span>Stock Transfer</span></a></li>
            <?php endif; ?>
        </ul>
    </li>
    <?php endif; ?>
    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['customers.view', 'suppliers.view', 'site_visits.view'])): ?>
    <li class="submenu-open">
        <h6 class="submenu-hdr">Peoples</h6>
        <ul>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customers.view')): ?>
            <li class="<?php echo e(activeMeny('customers')); ?>"><a href="<?php echo e(route('customers.index')); ?>"><i class="ti ti-users-group fs-16 me-2"></i><span>Customers</span></a></li>
            <?php endif; ?>
            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('vendors.view')): ?>
            <li class="<?php echo e(activeMeny('vendors')); ?>"><a href="<?php echo e(route('vendors.index')); ?>"><i class="ti ti-shield-up fs-16 me-2"></i><span>Vendors</span></a></li>
            <?php endif; ?>
            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('suppliers.view')): ?>
            <li class="<?php echo e(activeMeny('suppliers')); ?>"><a href="<?php echo e(route('suppliers.index')); ?>"><i class="ti ti-user-dollar fs-16 me-2"></i><span>Suppliers</span></a></li>
            <?php endif; ?>
            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('site_visits.view')): ?>
            <li class="<?php echo e(activeMeny('site-visits')); ?>"><a href="<?php echo e(route('site_visits.index')); ?>"><i class="ti ti-user-dollar fs-16 me-2"></i><span>Site Visits</span></a></li>
            <?php endif; ?>
            
        </ul>
    </li>
    <?php endif; ?>
    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['users.view', 'roles.view','permissions.view'])): ?>
        <li class="submenu-open">
            <h6 class="submenu-hdr">User & Role Management</h6>
            <ul>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('users.view')): ?>
                <li class="<?php echo e(activeMeny('user')); ?>"><a href="<?php echo e(route('users.index')); ?>"><i class="ti ti-shield-up fs-16 me-2"></i><span>Users</span></a></li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.view')): ?>
                <li class="<?php echo e(activeMeny('roles')); ?>"><a href="<?php echo e(route('roles.index')); ?>"><i class="ti ti-jump-rope fs-16 me-2"></i><span>Roles</span></a></li>
                <?php endif; ?>
                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permissions.view')): ?>
                <li class="<?php echo e(activeMeny('permissions')); ?> d-none"><a href="<?php echo e(route('permissions.index')); ?>"><i class="ti ti-jump-rope fs-16 me-2"></i><span>Permissions</span></a></li>
                <?php endif; ?>
                
            </ul>
        </li>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['sliders.view', 'pages.view','delivery_charges.view'])): ?>
    <li class="submenu-open">
        <h6 class="submenu-hdr">Ecommerce</h6>
        <ul>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sliders.view')): ?>
            <li class="<?php echo e(activeMeny('slider')); ?>"><a href="<?php echo e(route('sliders.index')); ?>"><i class="ti ti-stack-3 fs-16 me-2"></i><span>Sliders</span></a></li>
            <?php endif; ?>
            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('pages.view')): ?>
            <li class="<?php echo e(activeMeny('pages')); ?>"><a href="<?php echo e(route('pages.index')); ?>"><i class="ti ti-stack-3 fs-16 me-2"></i><span>Page Manage</span></a></li>
            <li class="<?php echo e(activeMeny('faq-pages')); ?>"><a href="<?php echo e(route('faq_pages.index')); ?>"><i class="ti ti-stack-3 fs-16 me-2"></i><span>FAQ Page Manage</span></a></li>
            <?php endif; ?>
            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delivery_charges.view')): ?>
            <li class="<?php echo e(activeMeny('delivery-charges')); ?>"><a href="<?php echo e(route('delivery_charges.index')); ?>"><i class="ti ti-stack-3 fs-16 me-2"></i><span>Delivery Charge</span></a></li>
            <?php endif; ?>
            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('top_menus.view')): ?>
            <li class="<?php echo e(activeMeny('product-features')); ?>"><a href="<?php echo e(route('product_features.index')); ?> "><i class="ti ti-checklist fs-16 me-2"></i><span> Top Menu </span></a></li>
            <?php endif; ?>

        </ul>
    </li>
    <?php endif; ?>
    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['coupons.view', 'discounts.view'])): ?>
    <li class="submenu-open">
        <h6 class="submenu-hdr">Promo & Discount</h6>
        <ul>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('coupons.view')): ?>
            <li class="<?php echo e(activeMeny('coupons')); ?>"><a href="<?php echo e(route('coupons.index')); ?>"><i class="ti ti-ticket fs-16 me-2"></i><span>Coupons</span></a></li>
            <?php endif; ?>
                    
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('discounts.view')): ?>
            <li class="<?php echo e(activeMeny('discounts')); ?>"><a href="<?php echo e(route('discounts.index')); ?>"><i class="ti ti-ticket fs-16 me-2"></i><span>Discounts</span></a></li>
            <li class="<?php echo e(activeMeny('discount-products')); ?>"><a href="<?php echo e(route('discount_products.index')); ?>"><i class="ti ti-ticket fs-16 me-2"></i><span>Product Discounts</span></a></li>
            <?php endif; ?>
            
        </ul>
    </li>
    <?php endif; ?>
    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['expenses.view', 'incomes.view'])): ?>
        <li class="submenu-open">
            <h6 class="submenu-hdr">Finance & Accounts</h6>
            <ul>

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('expenses.view')): ?>
                <li class="submenu">
                    <a class="<?php echo e(in_array(request()->segment(1), ['expenses','expense-category']) ? 'subdrop' : ''); ?>"
                    href="javascript:void(0);">
                        <i class="ti ti-file-stack fs-16 me-2"></i>
                        <span>Expenses</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul style="<?php echo e(in_array(request()->segment(1), ['expenses','expense-category']) ? 'display:block' : ''); ?>">
                        <li class="<?php echo e(activeMeny('expenses')); ?>">
                            <a href="<?php echo e(route('expenses.index')); ?>">Expenses</a>
                        </li>
                        <li class="<?php echo e(activeMeny('expense-category')); ?>">
                            <a href="<?php echo e(route('expenseCategory')); ?>">Expense Category</a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>


                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('incomes.view')): ?>
                <li class="submenu">
                    <a class="<?php echo e(in_array(request()->segment(1), ['incomes','income-category']) ? 'subdrop' : ''); ?>"
                    href="javascript:void(0);">
                        <i class="ti ti-file-pencil fs-16 me-2"></i>
                        <span>Income</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul style="<?php echo e(in_array(request()->segment(1), ['incomes','income-category']) ? 'display:block' : ''); ?>">
                        <li class="<?php echo e(activeMeny('incomes')); ?>">
                            <a href="<?php echo e(route('incomes.index')); ?>">Income</a>
                        </li>
                        <li class="<?php echo e(activeMeny('income-category')); ?>">
                            <a href="<?php echo e(route('incomeCategory')); ?>">Income Category</a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>

            </ul>
        </li>
    <?php endif; ?>
    
    
    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['reports.access'])): ?>
<li class="submenu-open">
    <h6 class="submenu-hdr">Reports</h6>
    <ul>

        
        <li class="submenu">
            <a href="javascript:void(0);"
               class="<?php echo e(request()->segment(1) == 'reports' ? 'subdrop' : ''); ?>">
                <i class="ti ti-report-analytics fs-16 me-2"></i>
                <span>Reports</span>
                <span class="menu-arrow"></span>
            </a>

            <ul style="<?php echo e(request()->segment(1) == 'reports' ? 'display:block' : ''); ?>">

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['reports.sales','reports.best_seller'])): ?>
                <li class="submenu">
                    <a href="javascript:void(0);"
                       class="<?php echo e(in_array(request()->segment(2), ['get-sales','best-seller']) ? 'subdrop' : ''); ?>">
                        <span>Product Sales Report</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul style="<?php echo e(in_array(request()->segment(2), ['get-sales','best-seller']) ? 'display:block' : ''); ?>">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reports.sales')): ?>
                        <li class="<?php echo e(activeMeny2('get-sales')); ?>">
                            <a href="<?php echo e(route('reports.getSales')); ?>">Sales Report</a>
                        </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reports.best_seller')): ?>
                        <li class="<?php echo e(activeMeny2('best-seller')); ?>">
                            <a href="<?php echo e(route('reports.getBestSeller')); ?>">Best Seller</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reports.purchase')): ?>
                <li class="<?php echo e(activeMeny2('purchase-report')); ?>">
                    <a href="<?php echo e(route('reports.purchaseReport')); ?>">
                        <span>Purchase Report</span>
                    </a>
                </li>
                <?php endif; ?>

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reports.inventory')): ?>
                <li class="submenu">
                    <a href="javascript:void(0);"
                       class="<?php echo e(request()->segment(2) == 'product-stock' ? 'subdrop' : ''); ?>">
                        <span>Inventory Report</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul style="<?php echo e(request()->segment(2) == 'product-stock' ? 'display:block' : ''); ?>">
                        <li class="<?php echo e(activeMeny2('product-stock')); ?>">
                            <a href="<?php echo e(route('reports.productSTock')); ?>">Inventory Report</a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['reports.supplier','reports.supplier_due'])): ?>
                <li class="submenu">
                    <a href="javascript:void(0);"
                       class="<?php echo e(in_array(request()->segment(2), ['supplier','supplier-due']) ? 'subdrop' : ''); ?>">
                        <span>Supplier Report</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul style="<?php echo e(in_array(request()->segment(2), ['supplier','supplier-due']) ? 'display:block' : ''); ?>">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reports.supplier')): ?>
                        <li class="<?php echo e(activeMeny2('supplier')); ?>">
                            <a href="<?php echo e(route('reports.getSupplier')); ?>">Supplier Report</a>
                        </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reports.supplier_due')): ?>
                        <li class="<?php echo e(activeMeny2('supplier-due')); ?>">
                            <a href="<?php echo e(route('reports.getSupplierDue')); ?>">Supplier Due Report</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['reports.customer','reports.customer_due'])): ?>
                <li class="submenu">
                    <a href="javascript:void(0);"
                       class="<?php echo e(in_array(request()->segment(2), ['customer','customer-due']) ? 'subdrop' : ''); ?>">
                        <span>Customer Report</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul style="<?php echo e(in_array(request()->segment(2), ['customer','customer-due']) ? 'display:block' : ''); ?>">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reports.customer')): ?>
                        <li class="<?php echo e(activeMeny2('customer')); ?>">
                            <a href="<?php echo e(route('reports.getCustomer')); ?>">Customer Report</a>
                        </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reports.customer_due')): ?>
                        <li class="<?php echo e(activeMeny2('customer-due')); ?>">
                            <a href="<?php echo e(route('reports.getCustomerDue')); ?>">Customer Due Report</a>
                        </li>
                        
                        <li class="<?php echo e(activeMeny2('customer-due-payments')); ?>">
                            <a href="<?php echo e(route('reports.customerDuePayment')); ?>">Customer Due Payment</a>
                        </li>
                        
                        <?php endif; ?>
                        
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reports.customer_payment_date')): ?>
                        <li class="<?php echo e(activeMeny2('customer-payment-date')); ?>">
                            <a href="<?php echo e(route('reports.customerPaymentDate')); ?>">Customer Payment Date</a>
                        </li>
                        
                        <?php endif; ?>
                        
                    </ul>
                </li>
                <?php endif; ?>

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['reports.product','reports.product_quantity_alert'])): ?>
                <li class="submenu">
                    <a href="javascript:void(0);"
                       class="<?php echo e(in_array(request()->segment(2), ['product-report','product-quantity-alert']) ? 'subdrop' : ''); ?>">
                        <span>Product Report</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul style="<?php echo e(in_array(request()->segment(2), ['product-report','product-quantity-alert']) ? 'display:block' : ''); ?>">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reports.product')): ?>
                        <li class="<?php echo e(activeMeny2('product-report')); ?>">
                            <a href="<?php echo e(route('reports.productReport')); ?>">Product Report</a>
                        </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reports.product_quantity_alert')): ?>
                        <li class="<?php echo e(activeMeny2('product-quantity-alert')); ?>">
                            <a href="<?php echo e(route('reports.productQuantityAlert')); ?>">Product Quantity Alert</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reports.expense')): ?>
                <li class="<?php echo e(activeMeny2('get-expense')); ?>">
                    <a href="<?php echo e(route('reports.getExpense')); ?>">Expense Report</a>
                </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reports.income')): ?>
                <li class="<?php echo e(activeMeny2('get-incomes')); ?>">
                    <a href="<?php echo e(route('reports.getIncomes')); ?>">Income Report</a>
                </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reports.profit_loss')): ?>
                <li class="<?php echo e(activeMeny2('profit-loss')); ?>">
                    <a href="<?php echo e(route('reports.profitLoss')); ?>">Profit & Loss</a>
                </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reports.annual')): ?>
                <li class="<?php echo e(activeMeny2('annual-report')); ?>">
                    <a href="<?php echo e(route('reports.annualReport')); ?>">Annual Report</a>
                </li>
                <?php endif; ?>

            </ul>
        </li>

    </ul>
</li>
<?php endif; ?>

    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['settings.access', 'locations.view'])): ?>
        <li class="submenu-open">
            <h6 class="submenu-hdr">Settings</h6>
            <ul>
        
                
                <li class="submenu">
                    <a class="<?php echo e(in_array(request()->segment(1), ['setting','locations']) ? 'subdrop' : ''); ?>"
                       href="javascript:void(0);">
                        <i class="ti ti-settings fs-16 me-2"></i>
                        <span>General Settings</span>
                        <span class="menu-arrow"></span>
                    </a>
        
                    <ul style="<?php echo e(in_array(request()->segment(1), ['setting','locations']) ? 'display:block' : ''); ?>">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('settings.access')): ?>
                        <li class="<?php echo e(activeMeny('setting')); ?>">
                            <a href="<?php echo e(route('settings.index')); ?>">System Settings</a>
                        </li>
                        <?php endif; ?>
        
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('locations.view')): ?>
                        <li class="<?php echo e(activeMeny('locations')); ?> d-none">
                            <a href="<?php echo e(route('locations.index')); ?>">Business Location</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
        
                
                <li>
                    <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="ti ti-logout fs-16 me-2"></i>
                        <span>Logout</span>
                    </a>
        
                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                        <?php echo csrf_field(); ?>
                    </form>
                </li>
        
            </ul>
        </li>
    <?php endif; ?>
</ul><?php /**PATH E:\laragon\www\personal\as-multi-vendor-ecom\admin\resources\views/partials/sidebar.blade.php ENDPATH**/ ?>