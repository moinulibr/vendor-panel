<?php
  
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers as CN;

   
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/clear', function(){
    Artisan::call('optimize'); 
    Artisan::call('view:clear'); 
    Artisan::call('cache:clear'); 
    Artisan::call('config:clear'); 
    Artisan::call('config:cache'); 
    Artisan::call('route:clear');
    
    Artisan::call('migrate');  
    // Artisan::call('make:model SmsSetting -mcr');  
    // Artisan::call('make:controller SellReturnController -r');  
    // Artisan::call('make:model ContactNextPayment');  
    dd('ok');
});

  
Auth::routes();
  
Route::get('/home', [CN\HomeController::class, 'index'])->name('home');
Route::get('/dashboard-data', [CN\HomeController::class, 'dashboardData'])->name('dashboardData');
  
Route::group(['middleware' => ['auth']], function() {
    
    Route::resource('roles', CN\RoleController::class);
    Route::resource('permissions', CN\PermissionController::class);
    Route::resource('user', CN\UserController::class,['names' => 'users']);
    Route::resource('brand', CN\BrandController::class,['names' => 'brands']);
    Route::resource('unit', CN\UnitController::class,['names' => 'units']);
    Route::resource('slider', CN\SliderController::class,['names' => 'sliders']);
    Route::resource('category', CN\CategoryController::class,['names' => 'categories']);
    Route::resource('product', CN\ProductController::class,['names' => 'products']);
    Route::resource('pos', CN\PosController::class);
    Route::resource('sells', CN\SellController::class);
    Route::resource('purchases', CN\PurchaseController::class);
    Route::resource('setting', CN\SettingController::class,['names' => 'settings']);
    Route::resource('customers', CN\CustomerController::class);
    Route::resource('suppliers', CN\SupplierController::class);
    Route::resource('location', CN\LocationController::class,['names' => 'locations']);
    Route::resource('sell-returns', CN\SellReturnController::class,['names' => 'sell_returns']);
    Route::resource('contacts', CN\ContactController::class);
    Route::resource('expenses', CN\ExpenseController::class);
    Route::resource('incomes', CN\IncomeController::class);
    Route::resource('coupons', CN\CouponController::class);
    Route::resource('discounts', CN\DiscountController::class);

    //product created history
    Route::get('product/created/history',[CN\ProductController::class, 'createdHistory'])->name('product.created.history');

    Route::get('/get-contact-due/{id}', [CN\ContactController::class, 'getContactDue'])->name('getContactDue');
    Route::post('/pay-due-store/{id}', [CN\TransactionPaymentController::class, 'payDueStore'])->name('payDueStore');
    
    
    Route::delete('/multi-image-delete/{id}', [CN\ProductController::class, 'multiImageDelete'])->name('multiImageDelete');
    
    
    Route::resource('discount-products', CN\DiscountProductController::class,['names' => 'discount_products']); 
    Route::get('discount-products-search',[CN\DiscountProductController::class, 'discountProducts'])->name('discount_products.search');
    Route::resource('variant-attributes', CN\VariantAttributeController::class,['names' => 'variant_attributes']); 
    Route::resource('transaction-categories', CN\TransactionCategoryController::class,['names' => 'transaction_categories']); 
    Route::resource('contact-address', CN\ContactAddressController::class,['names' => 'contact_address']); 
    Route::resource('delivery-charges', CN\DeliveryChargeController::class,['names' => 'delivery_charges']); 
    Route::resource('faq-pages', CN\FaqPageController::class,['names' => 'faq_pages']); 
    Route::resource('site-visits', CN\SiteVisitController::class,['names' => 'site_visits']); 
    Route::resource('order-from', CN\OrderFromController::class,['names' => 'order_from']); 
    Route::resource('barcodes', CN\BarcodeController::class); 
    Route::resource('pages', CN\PageController::class); 
    Route::resource('vendor-orders', CN\VendorOrderController::class,['names' => 'vendor_orders']); 
    Route::resource('vendors', CN\VendorController::class,['names' => 'vendors']); 
    
    Route::get('/vendor-order-print/{id}', [CN\VendorOrderController::class, 'orderPrint'])->name('orderPrint');
    
    
    Route::resource('stock-transfers', CN\StockTransferController::class,['names' => 'stock_transfers']); 
    Route::resource('stock-adjustments', CN\StockAdjustmentController::class,['names' => 'stock_adjustments']); 
    Route::resource('product-features', CN\ProductFeatureController::class,['names' => 'product_features']); 
    Route::resource('transaction-payments', CN\TransactionPaymentController::class,['names' => 'transaction_payments']); 
    Route::resource('vendor-order-payments', CN\VendorOrderPaymentController::class,['names' => 'vendor_order_payments']); 
    
    Route::get('/get-adjustment-product', [CN\StockAdjustmentController::class, 'getAdjustmentProduct'])->name('getAdjustmentProduct');
    Route::get('/adjustment-entry-product', [CN\StockAdjustmentController::class, 'adjustmentProductEntry'])->name('adjustmentProductEntry');
    
    
    Route::get('/get-transfer-product', [CN\StockTransferController::class, 'getTransferProduct'])->name('getTransferProduct');
    Route::get('/transfer-entry-product', [CN\StockTransferController::class, 'transferProductEntry'])->name('transferProductEntry');
    
    Route::get('/get-customers', [CN\CustomerController::class, 'getCustomer'])->name('getCustomer');
    Route::get('/get-customers-details', [CN\CustomerController::class, 'getCustomerdetails'])->name('getCustomerdetails');
    Route::get('/get-customers-address', [CN\CustomerController::class, 'getCustomerAddress'])->name('getCustomerAddress');
    Route::get('/get-upazila', [CN\CustomerController::class, 'getUpazila'])->name('getUpazila');
    
    Route::post('/store-customer-address', [CN\ContactController::class, 'storeCustomerAddress'])->name('storeCustomerAddress');
    Route::get('/next-payment-edit/{id}', [CN\ContactController::class, 'nextPaymentEdit'])->name('nextPaymentEdit');
    Route::post('/next-payment-update/{id}', [CN\ContactController::class, 'nextPaymentUpdate'])->name('nextPaymentUpdate');
    
    Route::patch('/store-pos-print', [CN\PosController::class, 'storePosPrint'])->name('storePosPrint');
    Route::get('/pos-product', [CN\PosController::class, 'getPosProduct'])->name('getPosProduct');
    Route::get('/quotations', [CN\PosController::class, 'getQuotation'])->name('getQuotation');
    Route::get('/expense-category', [CN\ExpenseController::class, 'expenseCategory'])->name('expenseCategory');
    Route::get('/income-category', [CN\IncomeController::class, 'incomeCategory'])->name('incomeCategory');
    Route::get('/sell-product', [CN\PosController::class, 'getSellProduct'])->name('getSellProduct');
    Route::get('/sell-product-entry', [CN\PosController::class, 'sellProductEntry'])->name('sellProductEntry');
    Route::get('/sell-print/{id}', [CN\SellController::class, 'sellPrint'])->name('sellPrint');
    
    Route::post('/sell-bulk-delete', [CN\PosController::class, 'sellBulkDelete'])->name('sells.bulkDelete');
    
    Route::get('/sell-status/{id}', [CN\PosController::class, 'sellStatus'])->name('sellStatus');
    Route::post('/sell-status/{id}', [CN\PosController::class, 'updateSellStatus'])->name('updateSellStatus');
    
    
    
    Route::get('/vendor-order-status/{id}', [CN\VendorOrderController::class, 'vendorOrderStatus'])->name('vendorOrderStatus');
    Route::post('/update-order-status/{id}', [CN\VendorOrderController::class, 'updateOrderStatus'])->name('updateOrderStatus');


    Route::get('/category-status', [CN\CategoryController::class, 'categoryStatus'])->name('categoryStatus');
    Route::get('/get-sub-category', [CN\CategoryController::class, 'getSubCategory'])->name('getSubCategory');
    Route::get('/brand-status', [CN\BrandController::class, 'brandStatus'])->name('brandStatus');

    Route::get('/purchase-product', [CN\PurchaseController::class, 'getPurchaseProduct'])->name('getPurchaseProduct');
    Route::get('/purchase-entry-product', [CN\PurchaseController::class, 'purchaseProductEntry'])->name('purchaseProductEntry');
    Route::get('/barcode-entry-product', [CN\BarcodeController::class, 'barcodeProductEntry'])->name('barcodeProductEntry');
    Route::get('/get-supplier', [CN\PurchaseController::class, 'getSupplier'])->name('purchase.getSupplier');
    Route::post('/ckeditor-upload', [CN\PageController::class, 'purchaseProductEntry'])->name('ckeditor.upload');

    Route::controller(CN\ReportController::class)->group(function(){
        Route::group(['prefix' => 'reports','as'=>'reports.'], function() {

            Route::get('/product-sell','productSell')->name('productSell');
            Route::get('/profit-loss','profitLoss')->name('profitLoss');
            Route::get('/annual-report','annualReport')->name('annualReport');
            Route::get('/purchase-report','purchaseReport')->name('purchaseReport');
            Route::get('/product-report','productReport')->name('productReport');

            Route::get('/category-sell','categorySell')->name('categorySell');
            Route::get('/product-stock','productSTock')->name('productSTock');
            Route::get('/all-payment','allPayment')->name('allPayment');
            Route::get('/get-sales','getSales')->name('getSales');
            Route::get('/get-incomes','getIncomes')->name('getIncomes');
            Route::get('/get-expense','getExpense')->name('getExpense');
            Route::get('/best-seller','getBestSeller')->name('getBestSeller');
            Route::get('/customer','getCustomer')->name('getCustomer');
            Route::get('/supplier','getSupplier')->name('getSupplier');
            Route::get('/customer-due','getCustomerDue')->name('getCustomerDue');
            Route::get('/supplier-due','getSupplierDue')->name('getSupplierDue');
            Route::get('/product-quantity-alert','productQuantityAlert')->name('productQuantityAlert');
            Route::get('/customer-due-payments','customerDuePayment')->name('customerDuePayment');
            Route::get('/customer-payment-date','customerPaymentDate')->name('customerPaymentDate');


        });
        
    });

    Route::controller(CN\ProductController::class)->group(function(){
  
        Route::get('/product-update','productUpdate')->name('productUpdate');
        
    });


    Route::controller(CN\UserController::class)->group(function(){
  
        Route::post('/vandor-update','vandorUpdate')->name('vandorUpdate');
        
    });

});