@php

    $total_sell=DB::table('transactions')->where(['is_new'=>0,'type'=>'sell','is_pos'=>0,'shipping_status'=>'pending'])->count();
@endphp

<ul>
    @canany(['sells.view', 'pos.view','vendor_orders.view','order_from.view'])
        <li class="submenu-open">
            <h6 class="submenu-hdr">Sales </h6>
            <ul>
                <li class="submenu">
                    <a class="{{in_array(request()->segment(1), ['sells','pos','vendor-orders','order-from','sell-returns']) ?'subdrop':''}} " href="javascript:void(0);"><i class="ti ti-layout-grid fs-16 me-2"></i><span>Sales</span> 
                        <span class="badge badge bg-primary text-dark"> {{ $total_sell}} </span>
                        
                         <span class="menu-arrow"></span></a>
                    <ul style="{{in_array(request()->segment(1), ['sells','pos','vendor-orders','order-from']) ?'display:block':''}}">
                        @can('sells.view')
                        <li class="{{ activeMeny('sells')}}"><a href="{{ route('sells.index')}}">Online Orders  <span class="badge badge bg-primary text-dark"> {{ $total_sell}} </span></a></li>
                        @endcan
                        
                        @can('pos.view')
                        <li class="{{ activeMeny('pos')}}"><a href="{{ route('pos.index')}}">POS Orders</a></li>
                        @endcan
                        
                        
                        
                        @can('vendor_orders.view')
                        <!--<li class="hide {{ activeMeny('vendor-orders')}}"><a href="{{ route('vendor_orders.index')}}">Vendor Orders</a></li>-->
                        @endcan
                        
                        @can('order_from.view')
                        <li class="{{ activeMeny('order-from')}}"><a href="{{ route('order_from.index')}}">Order From Manage</a></li>
                        @endcan
                        
                        @can('sells.view')
                        <li class="{{ activeMeny('sell-returns')}}"><a href="{{ route('sell_returns.index')}}"> Sell Return </span></a></li>
                        @endcan
                        
                    </ul>
                </li>
                <!--<li><a href="invoice.html"><i class="ti ti-file-invoice fs-16 me-2"></i><span>Invoices</span></a></li>-->
                <!--<li><a href="sales-returns.html"><i class="ti ti-receipt-refund fs-16 me-2"></i><span>Sales Return</span></a></li>-->
                
                @can('pos.view')
                <li class="{{ activeMeny('quotations')}}"><a href="{{ route('getQuotation')}}"><i class="ti ti-files fs-16 me-2"></i><span>Quotation</span></a></li>
                @endcan
                
            </ul>
        </li>
    @endcanany
    
    @canany(['purchases.view', 'purchase_return.view'])
    <li class="submenu-open">
        <h6 class="submenu-hdr">Purchases</h6>
        <ul>
            @can('purchases.view')
            <li class="{{ activeMeny('purchases')}}"><a href="{{ route('purchases.index')}}"><i class="ti ti-shopping-bag fs-16 me-2"></i><span>Purchases</span></a></li>
            @endcan
                    
            @can('purchase_return.view')
            <li><a href="purchase-returns.html"><i class="ti ti-file-upload fs-16 me-2"></i><span>Purchase Return</span></a></li>
            @endcan
        </ul>
    </li>
    @endcanany
    
    @canany(['products.view', 'variants.view','barcodes.view','categories.view'])
        <li class="submenu-open">
            <h6 class="submenu-hdr">Inventory</h6>
            <ul>
                @can('products.view')
                <li class="{{ activeMeny('product')}}"><a href="{{ route('products.index')}}"><i data-feather="box"></i><span>Products</span></a></li>
                @endcan
                @can('categories.view')
                <li class="{{ activeMeny('category')}}"><a href="{{ route('categories.index')}}"><i class="ti ti-list-details fs-16 me-2"></i><span>Category</span></a></li>
                @endcan
                @can('brands.view')
                <li class="{{ activeMeny('brand')}}"><a href="{{ route('brands.index')}}"><i class="ti ti-triangles fs-16 me-2"></i><span>Brands</span></a></li>
                @endcan
                @can('units.view')
                <li class="{{ activeMeny('unit')}}"><a href="{{ route('units.index')}}"><i class="ti ti-brand-unity fs-16 me-2"></i><span>Units</span></a></li>
                @endcan
                
                @can('variants.view')
                <li class="{{ activeMeny('variant-attributes')}}"><a href="{{ route('variant_attributes.index')}} "><i class="ti ti-checklist fs-16 me-2"></i><span>Variant Attributes</span></a></li>
                @endcan
                
                
                
                @can('barcodes.view')
                <li class="{{ activeMeny('barcodes')}}"><a href="{{ route('barcodes.index')}}"><i class="ti ti-barcode fs-16 me-2"></i><span>Print Barcode</span></a></li>
                @endcan
                
            </ul>
        </li>
    @endcanany
    
    @canany(['stock_transfers.view', 'stock_adjustments.view'])
    <li class="submenu-open hide">
        <h6 class="submenu-hdr">Stock</h6>
        <ul>
            @can('stock_adjustments.view')
            <li class="{{ activeMeny('stock-adjustments')}}"><a href="{{ route('stock_adjustments.index')}}"><i class="ti ti-stairs-up fs-16 me-2"></i><span>Stock Adjustment</span></a></li>
            @endcan
                    
            @can('stock_transfers.view')
            <li class="{{ activeMeny('stock-transfers')}}"><a href="{{ route('stock_transfers.index')}}"><i class="ti ti-stack-pop fs-16 me-2"></i><span>Stock Transfer</span></a></li>
            @endcan
        </ul>
    </li>
    @endcanany
    
    @canany(['customers.view', 'suppliers.view', 'site_visits.view'])
    <li class="submenu-open">
        <h6 class="submenu-hdr">Peoples</h6>
        <ul>
            @can('customers.view')
            <li class="{{ activeMeny('customers')}}"><a href="{{ route('customers.index')}}"><i class="ti ti-users-group fs-16 me-2"></i><span>Customers</span></a></li>
            @endcan
            
            @can('vendors.view')
            <li class="{{ activeMeny('vendors')}}"><a href="{{ route('vendors.index')}}"><i class="ti ti-shield-up fs-16 me-2"></i><span>Vendors</span></a></li>
            @endcan
            
            @can('suppliers.view')
            <li class="{{ activeMeny('suppliers')}}"><a href="{{ route('suppliers.index')}}"><i class="ti ti-user-dollar fs-16 me-2"></i><span>Suppliers</span></a></li>
            @endcan
            
            @can('site_visits.view')
            <li class="{{ activeMeny('site-visits')}}"><a href="{{ route('site_visits.index')}}"><i class="ti ti-user-dollar fs-16 me-2"></i><span>Site Visits</span></a></li>
            @endcan
            
        </ul>
    </li>
    @endcanany
    
    @canany(['users.view', 'roles.view','permissions.view'])
        <li class="submenu-open">
            <h6 class="submenu-hdr">User & Role Management</h6>
            <ul>
                @can('users.view')
                <li class="{{ activeMeny('user')}}"><a href="{{ route('users.index')}}"><i class="ti ti-shield-up fs-16 me-2"></i><span>Users</span></a></li>
                @endcan
                @can('roles.view')
                <li class="{{ activeMeny('roles')}}"><a href="{{ route('roles.index')}}"><i class="ti ti-jump-rope fs-16 me-2"></i><span>Roles</span></a></li>
                @endcan
                
                @can('permissions.view')
                <li class="{{ activeMeny('permissions')}} d-none"><a href="{{ route('permissions.index')}}"><i class="ti ti-jump-rope fs-16 me-2"></i><span>Permissions</span></a></li>
                @endcan
                
            </ul>
        </li>
    @endcanany

    @canany(['sliders.view', 'pages.view','delivery_charges.view'])
    <li class="submenu-open">
        <h6 class="submenu-hdr">Ecommerce</h6>
        <ul>
            @can('sliders.view')
            <li class="{{ activeMeny('slider')}}"><a href="{{ route('sliders.index')}}"><i class="ti ti-stack-3 fs-16 me-2"></i><span>Sliders</span></a></li>
            @endcan
            
            @can('pages.view')
            <li class="{{ activeMeny('pages')}}"><a href="{{ route('pages.index')}}"><i class="ti ti-stack-3 fs-16 me-2"></i><span>Page Manage</span></a></li>
            <li class="{{ activeMeny('faq-pages')}}"><a href="{{ route('faq_pages.index')}}"><i class="ti ti-stack-3 fs-16 me-2"></i><span>FAQ Page Manage</span></a></li>
            @endcan
            
            @can('delivery_charges.view')
            <li class="{{ activeMeny('delivery-charges')}}"><a href="{{ route('delivery_charges.index')}}"><i class="ti ti-stack-3 fs-16 me-2"></i><span>Delivery Charge</span></a></li>
            @endcan
            
            @can('top_menus.view')
            <li class="{{ activeMeny('product-features')}}"><a href="{{ route('product_features.index')}} "><i class="ti ti-checklist fs-16 me-2"></i><span> Top Menu </span></a></li>
            @endcan

        </ul>
    </li>
    @endcanany
    
    @canany(['coupons.view', 'discounts.view'])
    <li class="submenu-open">
        <h6 class="submenu-hdr">Promo & Discount</h6>
        <ul>
            @can('coupons.view')
            <li class="{{ activeMeny('coupons')}}"><a href="{{ route('coupons.index')}}"><i class="ti ti-ticket fs-16 me-2"></i><span>Coupons</span></a></li>
            @endcan
                    
            @can('discounts.view')
            <li class="{{ activeMeny('discounts')}}"><a href="{{ route('discounts.index')}}"><i class="ti ti-ticket fs-16 me-2"></i><span>Discounts</span></a></li>
            <li class="{{ activeMeny('discount-products')}}"><a href="{{ route('discount_products.index')}}"><i class="ti ti-ticket fs-16 me-2"></i><span>Product Discounts</span></a></li>
            @endcan
            
        </ul>
    </li>
    @endcanany
    
    @canany(['expenses.view', 'incomes.view'])
        <li class="submenu-open">
            <h6 class="submenu-hdr">Finance & Accounts</h6>
            <ul>

                {{-- Expenses Dropdown --}}
                @can('expenses.view')
                <li class="submenu">
                    <a class="{{ in_array(request()->segment(1), ['expenses','expense-category']) ? 'subdrop' : '' }}"
                    href="javascript:void(0);">
                        <i class="ti ti-file-stack fs-16 me-2"></i>
                        <span>Expenses</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul style="{{ in_array(request()->segment(1), ['expenses','expense-category']) ? 'display:block' : '' }}">
                        <li class="{{ activeMeny('expenses') }}">
                            <a href="{{ route('expenses.index') }}">Expenses</a>
                        </li>
                        <li class="{{ activeMeny('expense-category') }}">
                            <a href="{{ route('expenseCategory') }}">Expense Category</a>
                        </li>
                    </ul>
                </li>
                @endcan


                {{-- Income Dropdown --}}
                @can('incomes.view')
                <li class="submenu">
                    <a class="{{ in_array(request()->segment(1), ['incomes','income-category']) ? 'subdrop' : '' }}"
                    href="javascript:void(0);">
                        <i class="ti ti-file-pencil fs-16 me-2"></i>
                        <span>Income</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul style="{{ in_array(request()->segment(1), ['incomes','income-category']) ? 'display:block' : '' }}">
                        <li class="{{ activeMeny('incomes') }}">
                            <a href="{{ route('incomes.index') }}">Income</a>
                        </li>
                        <li class="{{ activeMeny('income-category') }}">
                            <a href="{{ route('incomeCategory') }}">Income Category</a>
                        </li>
                    </ul>
                </li>
                @endcan

            </ul>
        </li>
    @endcanany
    
    
    
    @canany(['reports.access'])
<li class="submenu-open">
    <h6 class="submenu-hdr">Reports</h6>
    <ul>

        {{-- Parent Reports Dropdown --}}
        <li class="submenu">
            <a href="javascript:void(0);"
               class="{{ request()->segment(1) == 'reports' ? 'subdrop' : '' }}">
                <i class="ti ti-report-analytics fs-16 me-2"></i>
                <span>Reports</span>
                <span class="menu-arrow"></span>
            </a>

            <ul style="{{ request()->segment(1) == 'reports' ? 'display:block' : '' }}">

                {{-- Sales Report --}}
                @canany(['reports.sales','reports.best_seller'])
                <li class="submenu">
                    <a href="javascript:void(0);"
                       class="{{ in_array(request()->segment(2), ['get-sales','best-seller']) ? 'subdrop' : '' }}">
                        <span>Product Sales Report</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul style="{{ in_array(request()->segment(2), ['get-sales','best-seller']) ? 'display:block' : '' }}">
                        @can('reports.sales')
                        <li class="{{ activeMeny2('get-sales') }}">
                            <a href="{{ route('reports.getSales') }}">Sales Report</a>
                        </li>
                        @endcan

                        @can('reports.best_seller')
                        <li class="{{ activeMeny2('best-seller') }}">
                            <a href="{{ route('reports.getBestSeller') }}">Best Seller</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                {{-- Purchase Report --}}
                @can('reports.purchase')
                <li class="{{ activeMeny2('purchase-report') }}">
                    <a href="{{ route('reports.purchaseReport') }}">
                        <span>Purchase Report</span>
                    </a>
                </li>
                @endcan

                {{-- Inventory Report --}}
                @can('reports.inventory')
                <li class="submenu">
                    <a href="javascript:void(0);"
                       class="{{ request()->segment(2) == 'product-stock' ? 'subdrop' : '' }}">
                        <span>Inventory Report</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul style="{{ request()->segment(2) == 'product-stock' ? 'display:block' : '' }}">
                        <li class="{{ activeMeny2('product-stock') }}">
                            <a href="{{ route('reports.productSTock') }}">Inventory Report</a>
                        </li>
                    </ul>
                </li>
                @endcan

                {{-- Supplier Report --}}
                @canany(['reports.supplier','reports.supplier_due'])
                <li class="submenu">
                    <a href="javascript:void(0);"
                       class="{{ in_array(request()->segment(2), ['supplier','supplier-due']) ? 'subdrop' : '' }}">
                        <span>Supplier Report</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul style="{{ in_array(request()->segment(2), ['supplier','supplier-due']) ? 'display:block' : '' }}">
                        @can('reports.supplier')
                        <li class="{{ activeMeny2('supplier') }}">
                            <a href="{{ route('reports.getSupplier') }}">Supplier Report</a>
                        </li>
                        @endcan

                        @can('reports.supplier_due')
                        <li class="{{ activeMeny2('supplier-due') }}">
                            <a href="{{ route('reports.getSupplierDue') }}">Supplier Due Report</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                {{-- Customer Report --}}
                @canany(['reports.customer','reports.customer_due'])
                <li class="submenu">
                    <a href="javascript:void(0);"
                       class="{{ in_array(request()->segment(2), ['customer','customer-due']) ? 'subdrop' : '' }}">
                        <span>Customer Report</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul style="{{ in_array(request()->segment(2), ['customer','customer-due']) ? 'display:block' : '' }}">
                        @can('reports.customer')
                        <li class="{{ activeMeny2('customer') }}">
                            <a href="{{ route('reports.getCustomer') }}">Customer Report</a>
                        </li>
                        @endcan

                        @can('reports.customer_due')
                        <li class="{{ activeMeny2('customer-due') }}">
                            <a href="{{ route('reports.getCustomerDue') }}">Customer Due Report</a>
                        </li>
                        
                        <li class="{{ activeMeny2('customer-due-payments') }}">
                            <a href="{{ route('reports.customerDuePayment') }}">Customer Due Payment</a>
                        </li>
                        
                        @endcan
                        
                        @can('reports.customer_payment_date')
                        <li class="{{ activeMeny2('customer-payment-date') }}">
                            <a href="{{ route('reports.customerPaymentDate') }}">Customer Payment Date</a>
                        </li>
                        
                        @endcan
                        
                    </ul>
                </li>
                @endcanany

                {{-- Product Report --}}
                @canany(['reports.product','reports.product_quantity_alert'])
                <li class="submenu">
                    <a href="javascript:void(0);"
                       class="{{ in_array(request()->segment(2), ['product-report','product-quantity-alert']) ? 'subdrop' : '' }}">
                        <span>Product Report</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul style="{{ in_array(request()->segment(2), ['product-report','product-quantity-alert']) ? 'display:block' : '' }}">
                        @can('reports.product')
                        <li class="{{ activeMeny2('product-report') }}">
                            <a href="{{ route('reports.productReport') }}">Product Report</a>
                        </li>
                        @endcan

                        @can('reports.product_quantity_alert')
                        <li class="{{ activeMeny2('product-quantity-alert') }}">
                            <a href="{{ route('reports.productQuantityAlert') }}">Product Quantity Alert</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                {{-- Expense / Income / Profit / Annual --}}
                @can('reports.expense')
                <li class="{{ activeMeny2('get-expense') }}">
                    <a href="{{ route('reports.getExpense') }}">Expense Report</a>
                </li>
                @endcan

                @can('reports.income')
                <li class="{{ activeMeny2('get-incomes') }}">
                    <a href="{{ route('reports.getIncomes') }}">Income Report</a>
                </li>
                @endcan

                @can('reports.profit_loss')
                <li class="{{ activeMeny2('profit-loss') }}">
                    <a href="{{ route('reports.profitLoss') }}">Profit & Loss</a>
                </li>
                @endcan

                @can('reports.annual')
                <li class="{{ activeMeny2('annual-report') }}">
                    <a href="{{ route('reports.annualReport') }}">Annual Report</a>
                </li>
                @endcan

            </ul>
        </li>

    </ul>
</li>
@endcanany

    
    @canany(['settings.access', 'locations.view'])
        <li class="submenu-open">
            <h6 class="submenu-hdr">Settings</h6>
            <ul>
        
                {{-- General Settings --}}
                <li class="submenu">
                    <a class="{{ in_array(request()->segment(1), ['setting','locations']) ? 'subdrop' : '' }}"
                       href="javascript:void(0);">
                        <i class="ti ti-settings fs-16 me-2"></i>
                        <span>General Settings</span>
                        <span class="menu-arrow"></span>
                    </a>
        
                    <ul style="{{ in_array(request()->segment(1), ['setting','locations']) ? 'display:block' : '' }}">
                        @can('settings.access')
                        <li class="{{ activeMeny('setting') }}">
                            <a href="{{ route('settings.index') }}">System Settings</a>
                        </li>
                        @endcan
        
                        @can('locations.view')
                        <li class="{{ activeMeny('locations') }} d-none">
                            <a href="{{ route('locations.index') }}">Business Location</a>
                        </li>
                        @endcan
                    </ul>
                </li>
        
                {{-- Logout --}}
                <li>
                    <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="ti ti-logout fs-16 me-2"></i>
                        <span>Logout</span>
                    </a>
        
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
        
            </ul>
        </li>
    @endcanany
</ul>