@extends('layouts.app')
@section('content')
<style>
    @media (max-width: 767px) {
      .offcanvas-body form {
        gap: 1.5rem;
      }
      .card-body .row > * {
        flex: 0 0 100%;
        max-width: 100%;
      }
    }
    
</style>

<style>
 print-only: hide everything except #print_area 
@media print {


   show only print_area and its children 
  #print_area, #print_area * {
    visibility: visible;
  }
  
   place print_area at top-left and full width 
  #print_area {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
  }


}

.page-break { page-break-before: always; break-before: page; }

@media print {
  .no-print {
    display: none !important;
  }
}

.option-box {
    cursor: pointer;
    border: 1px solid #ddd;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 12px;
}

.option-box.active {
    border-color: #0d6efd;
    background-color: #f8f9ff;
}


</style>



<div class="content no-print">
    <div class="card">
        <div class="container-fluid py-4">
            {{-- POS FORM --}}
            <form action="{{ route('pos.update',[$transaction->id])}}" method="post" id="pos_form">
                @method('PATCH')
                @csrf
                <input id="contact_id" name="contact_id" type="hidden" value="{{$transaction->contact_id}}">
                <div class="row">
                    <!--Product section-->
                    <div class="col-lg-8">
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="ti ti-search text-muted"></i>
                            </span>
                            <input type="text"
                                   class="form-control border-start-0"
                                   placeholder="Search Product"
                                   id="purchases_product">
                            <button type="button"
                                    class="btn btn-primary"
                                    data-bs-toggle="offcanvas"
                                    data-bs-target="#productOffcanvas">
                                Products List
                            </button>
                        </div>
                        <div id="pos_cart_items">
                            @foreach($olditems as $olditem)
        					    @include('pos.partials.product_row',['item'=>$olditem, 'exist'=>1])
        					@endforeach
                        </div>
                        
                    </div>
                    
                    <!--Customer section-->
                    <div class="col-lg-4">
                        <div class="card shadow-sm border-0 mb-3">
                            <div class="card-body p-3">
                                <!-- Header -->
                                <div class="customer-section">
                                    
                                    <!-- Header -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="fw-semibold mb-0">
                                            <i class="ti ti-user me-2 text-primary"></i>Customer Info
                                        </h5>
                            
                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary rounded-pill"
                                                data-bs-toggle="offcanvas"
                                                data-bs-target="#customerOffcanvas">
                                            <i class="ti ti-plus"></i> Customer List
                                        </button>
                                    </div>
                            
                                    <!-- Customer Name -->
                                    <div class="customer-box p-3 rounded d-flex align-items-center justify-content-between"
                                         style="background: #f8f9fa; cursor: pointer;"
                                         data-bs-toggle="offcanvas"
                                         data-bs-target="#customerDetailsOffcanvas">
                                    
                                        <div>
                                            <small class="text-muted d-block">Selected Customer</small>
                                            <!-- gap-2 added -->
                                            <span class="customer_name fw-bold text-dark d-block mt-2"></span>
                                        </div>
                                    
                                        <!-- Right icons: cross above arrow -->
                                        <div class="d-flex flex-column align-items-center gap-2">
                                            <!-- Cross icon -->
                                            <!--<i class="ti ti-x text-danger"-->
                                            <!--   onclick="event.stopPropagation(); clearCustomer();"-->
                                            <!--   style="cursor: pointer;-->
                                            <!--           font-size: 1rem;-->
                                            <!--           background: #ffe6e6;-->
                                            <!--           border-radius: 50%;-->
                                            <!--           padding: 4px;-->
                                            <!--           display: flex;-->
                                            <!--           align-items: center;-->
                                            <!--           justify-content: center;-->
                                            <!--           transition: all 0.2s ease;">-->
                                            <!--</i>-->
                                    
                                            <!-- Arrow icon -->
                                            <i class="ti ti-chevron-right text-muted"></i>
                                        </div>
                                    </div>

                                </div>
                                
                                <div class="d-flex justify-content-between my-3">
                                    <span>Sub Total</span>
                                    <strong>Tk <span class="sub_total">0</span></strong>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-3">
                                    <a href="#" class="link-default"  data-bs-toggle="modal" data-bs-target="#shipping-cost">
                                        <span>Shipping Charge</span>
            						    <i class="ti ti-edit"></i>
            						</a>
                                    <strong>Tk <span class="charge">0</span></strong>
                                </div>
    
                                <div class="d-flex justify-content-between mb-3">
                                    <a href="#" class="link-default" data-bs-toggle="modal" data-bs-target="#discount">
                                        Discount <i class="ti ti-edit"></i>
            						</a>
                                    <strong>Tk <span class="discount">0</span></strong>
                                </div>
    
                                <div class="total-section">
                                    <div class="d-flex justify-content-between mb-4">
                                        <span class="fw-bold">Total Price</span>
                                        <strong>Tk <span class="final_amount">0</span></strong>
                                        <input type="hidden" class="final_amount" name="final_amount">
                                        <input type="hidden" class="cal_discount" name="cal_discount">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Invoice Type (only product name):</label><br>
                                      
                                        <div class="form-check form-check-inline">
                                            <input style="cursor: pointer;" class="form-check-input" type="radio" name="invoice_type" id="invoiceEnglish" value="1"  {{ $transaction->invoice_type==1 ?'checked':''}}>
                                            <label style="cursor: pointer;" class="form-check-label" for="invoiceEnglish">English</label>
                                        </div>
                                      
                                        <div class="form-check form-check-inline">
                                            <input style="cursor: pointer;" class="form-check-input" type="radio" name="invoice_type" id="invoiceBangla" value="2" {{ $transaction->invoice_type==2 ?'checked':''}}>
                                            <label style="cursor: pointer;" class="form-check-label" for="invoiceBangla">Bangla</label>
                                        </div>
                                      
                                    </div>
                                    
                                    <div>
                                      <label class="form-label">Invoice Send via:</label><br>
                                      <div class="form-check form-check-inline">
                                        <input style="cursor: pointer;" class="form-check-input" type="checkbox" name="sms_notification" id="sendMobile" value="1" {{ $transaction->sms_notification ?'checked':''}}>
                                        <label style="cursor: pointer;" class="form-check-label" for="sendMobile">SMS</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                        <input style="cursor: pointer;" class="form-check-input" type="checkbox" name="mail_notification" id="sendEmail" value="1" {{ $transaction->mail_notification ?'checked':''}}>
                                        <label style="cursor: pointer;" class="form-check-label" for="sendEmail">Email</label>
                                      </div>
                                    </div>
            
            
    
                                    <div class="d-grid gap-2 no-print mt-2">
                                        <!--<button type="button" class="btn btn-outline-primary" onclick="window.print()">Print</button>-->
                                        <button type="button" class="btn btn-outline-primary" id="print_sell">
                                            Print
                                        </button>
                                        <button type="button" class="btn btn-primary" id="quotation">Quotation</button> 
                                        <button type="button" class="btn btn-success" id="place_order">Place an Order</button> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @include('pos.partials.modal')
                    </div>
                </div>
            </form>
        </div>

        <!--Product Offcanvas-->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="productOffcanvas">
          <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title">Products</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
          </div>
          <div class="offcanvas-body">
            <div class="row g-2 mb-3">
                <div class="col-3">
                    <label class="form-label small mb-1">Location</label>
                    <select class="form-select" id="location_id">
                        @foreach($locations as $location)
                        <option value="{{ $location->id}}">{{ $location->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <label class="form-label small mb-1">Category</label>
                    <select class="form-select" id="category_id">
                        <option value="">All</option>
                        @foreach($cats as $cat)
                        <option value="{{ $cat->id}}">{{ $cat->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <label class="form-label small mb-1">Brand</label>
                    <select class="form-select" id="brand_id">
                        <option value="">All</option>
                        @foreach($brands as $brand)
                        <option value="{{ $brand->id}}">{{ $brand->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <label class="form-label small mb-1">Search</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control border-start-0" placeholder="Search products..." id="psearch">
                    </div> 
                </div>
            </div>

            <div id="product-list"></div>
          </div>
        </div>

        <!--Customer List Offcanvas-->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="customerOffcanvas">
            <div class="offcanvas-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="offcanvas-title mb-0">Customer List</h5>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#addCustomerOffcanvas">
                        <i class="bi bi-plus-lg me-1"></i>Add New
                    </button>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                </div>
            </div>

            <div class="offcanvas-body">
                <div class="row">
                    
                    
                    <div class="col-6">
                        <div class="input-group mb-3">
                            <select class="form-control concat_add_from">
                                <option value=""> All </option>
                                <option value="1"> Ecommerce Register </option>
                                <option value="2"> Socialite Add  </option>
                                <option value="3"> Admin Panel </option>
                                <option value="4"> SR Panel </option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-6">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control border-start-0" placeholder="Search Customer..." id="customer_search">
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="customer-list">
                            <!-- Customer list content -->
                        </div>
                    </div>
                    
                </div>
                

                
            </div>
        </div>

        <!--Add New Customer Offcanvas-->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="addCustomerOffcanvas">
            <div class="offcanvas-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="offcanvas-title mb-0">Add New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
                <form class="d-flex flex-column gap-4" method="post" action="{{ route('customers.store')}}" id="add_customer">
                    @csrf
                    <!-- Customer Details -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light fw-semibold">Customer Details</div>
                        <div class="card-body">
                          <div class="row g-3">
                            <div class="col-md-6">
                              <label class="form-label">First Name <span class="text-danger">*</span></label>
                              <input type="text" class="form-control" name="name" placeholder="First Name" required>
                            </div>
                            <div class="col-md-6">
                              <label class="form-label">Last Name </label>
                              <input type="text" class="form-control" name="last_name" placeholder="Last Name">
                            </div>
                            <div class="col-md-6">
                              <label class="form-label">Number <span class="text-danger">*</span></label>
                              <input type="text" class="form-control mobile_validation" name="mobile" placeholder="+880253653222" required>
                            </div>
                            <div class="col-md-6">
                              <label class="form-label">Email</label>
                              <input type="email" class="form-control" name="email" placeholder="example@mail.com">
                            </div>
                          </div>
                        </div>
                    </div>
                
                    <!-- Permanent Address -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light fw-semibold">Billing Address</div>
                        <div class="card-body">
                          <div class="row g-3">
                            <div class="col-md-6">
                              <label class="form-label"> District <span class="text-danger">*</span></label>
                              <select class="form-select select2 district_id" name="p_district" id="p_district" required>
                                <option value="">Select  District </option>
                                @foreach($districts as $district)
                                <option value="{{ $district->id}}">{{ $district->name}}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="col-md-6">
                              <label class="form-label">Thana</label>
                              <select class="form-select select2 upazila_id" name="p_upazila" id="p_upazila">
                                <option value="">Select Thana</option>
                                
                              </select>
                            </div>
                            <div class="col-12">
                              <label class="form-label">Landmark (Optional)</label>
                              <input type="text" name="p_landmark" class="form-control" placeholder="Tangail Sadar">
                            </div>
                            <div class="col-12">
                              <label class="form-label">Full Address <span class="text-danger">*</span></label>
                              <input type="text" name="address" class="form-control" required placeholder="Tangail Sadar, Dhaka, Bangladesh">
                            </div>
                          </div>
                        </div>
                        
                        <div class="col-12 p-3 mb-3">
                            
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="1" name="same_shipping" id="same_shipping">
                              <label class="form-check-label" for="same_shipping">
                                    Same Shipping Address
                              </label>
                            </div>
                        </div>
                            
                    </div>
                
                    <!-- Shipping Address -->
                    <div class="card border-0 shadow-sm shipping_form">
                        <div class="card-header bg-light fw-semibold">Shipping Address</div>
                        <div class="card-body">
                          <div class="row g-3">
                              
                            <div class="col-md-12">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="full_name" placeholder="Full Name">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">District</label>
                                <select class="form-select select2" name="s_district" id="s_district">
                                    <option value="">Select District</option>
                                    @foreach($districts as $district)
                                    <option value="{{ $district->id}}">{{ $district->name}}</option>
                                    @endforeach
                              </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"> Thana </label>
                                <select class="form-select select2" name="s_upazila" id="s_upazila">
                                    <option value="">Select  Thana </option>
                              </select>
                            </div>
                            <div class="col-12">
                              <label class="form-label">Landmark (Optional)</label>
                              <input type="text" class="form-control" placeholder="Tangail Sadar" name="s_landmark">
                            </div>
                            
                            <div class="col-md-6">
                              <label class="form-label">Mobile <span class="text-danger">*</span></label>
                              <input type="text" class="form-control" name="phone" placeholder="+880253653222">
                            </div>
                            
                            <div class="col-12">
                              <label class="form-label">Full Address</label>
                              <input type="text" class="form-control" placeholder="Tangail Sadar, Dhaka, Bangladesh" name="s_address">
                            </div>
                          </div>
                        </div>
                    </div>
                
                    <!-- Extra Shipping Addresses -->
                    <!--<div class="card border-0 shadow-sm">-->
                    <!--    <div class="card-header bg-light fw-semibold">Shipping Address</div>-->
                    <!--    <div class="card-body d-flex flex-column gap-3">-->
                    <!--      <div class="form-control bg-light">Address 1<br><small>Mirpur, Bangladesh</small></div>-->
                    <!--      <div class="form-control bg-light">Address 1<br><small>Mirpur, Bangladesh</small></div>-->
                    <!--      <div class="form-control bg-light">Address 1<br><small>Mirpur, Bangladesh</small></div>-->
                    <!--      <div class="form-control bg-light">Address 1<br><small>Mirpur, Bangladesh</small></div>-->
                    <!--    </div>-->
                    <!--</div>-->
                
                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-3 mt-3">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="offcanvas">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">Save</button>
                    </div>
        
                </form>
            </div>
        </div>

        <!-- Customer Details Offcanvas -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="customerDetailsOffcanvas">
            <div class="offcanvas-header border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                  <button class="btn btn-light rounded-circle p-2 btn-close" data-bs-dismiss="offcanvas">
                    <!--<i class="ti ti-arrow-left"></i>-->
                  </button>
                  <h5 class="mb-0 fw-bold">Customer Details</h5>
                </div>
            </div>
            
            <div class="offcanvas-body bg-light">
                <div class="accordion" id="customerDetailsAccordion">
                    
                </div>
                <div class="accordion" id="fff">
            
                  <!-- Section 1: Customer Info -->
                  
                  <!--<div class="accordion-item border-0 shadow-sm mb-2">-->
                  <!--  <h2 class="accordion-header" id="headingFour">-->
                  <!--    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">-->
                  <!--      Payment Summary-->
                  <!--    </button>-->
                  <!--  </h2>-->
                  <!--  <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#customerDetailsAccordion">-->
                  <!--    <div class="accordion-body">-->
                  <!--      <div class="d-flex justify-content-between mb-2"><span>Sub Total:</span>-->
                  <!--          <strong>Tk <span class="sub_total">0</span></strong>-->
                  <!--      </div>-->
                  <!--      <div class="d-flex justify-content-between mb-2"><span>Shipping Charge:</span> -->
                  <!--          <strong>Tk <span class="charge">0</span></strong>-->
                  <!--      </div>-->
                  <!--      <div class="d-flex justify-content-between mb-3"><span> Discount :</span>-->
                  <!--          <strong>Tk <span class="discount">0</span></strong>-->
                  <!--      </div>-->
                        
                        
                  <!--      <div class="d-flex justify-content-between mb-3"><span> Final Amount :</span>-->
                  <!--          <strong>Tk <span class="final_amount">0</span></strong>-->
                  <!--      </div>-->
                        
                        
                  <!--    </div>-->
                  <!--  </div>-->
                  <!--</div>-->
            
                </div>
             </div>
            
              <!-- Sticky Footer Buttons -->
            <!--<div class="offcanvas-footer p-3 border-top bg-white d-flex flex-column flex-md-row gap-2">-->
            <!--    <button class="btn btn-outline-secondary w-100 final_sale" data-method="cash">Print Invoice</button>-->
            <!--    <button class="btn btn-dark w-100" id="place_order">Proceed to Pay</button>-->
            <!--</div>-->
        </div>
    </div>
</div>


@endsection

@push('js')
@include('pos.partials.js')
    <script>
         function printInvoice() {
            const invoice = document.getElementById('invoice_print_area');
            if (!invoice) return;
        
            invoice.classList.remove('d-none');
            window.print();
            invoice.classList.add('d-none');
        }
    </script>
@endpush