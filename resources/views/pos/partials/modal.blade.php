<style>

    .payment-option-group .payment-btn {
        width: 140px;
        padding: 6px 10px;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: pointer;
        transition: background-color .15s ease-in-out,
                    border-color .15s ease-in-out,
                    color .15s ease-in-out;
    }
    
    .payment-option-group .payment-btn .form-check-input {
        margin: 0 !important;
    }
    
    /* ========== CHECKED STATES ========== */
    
    /* SUCCESS */
    .payment-option-group 
    .btn-outline-success:has(input.payment_option:checked) {
        background-color: var(--bs-success) !important;
        border-color: var(--bs-success) !important;
        color: #fff !important;
    }
    
    /* WARNING */
    .payment-option-group 
    .btn-outline-warning:has(input.payment_option:checked) {
        background-color: var(--bs-warning) !important;
        border-color: var(--bs-warning) !important;
        color: #fff !important; /* bootstrap hover behaviour */
    }
    
    /* DANGER */
    .payment-option-group 
    .btn-outline-danger:has(input.payment_option:checked) {
        background-color: var(--bs-danger) !important;
        border-color: var(--bs-danger) !important;
        color: #fff !important;
    }
    
    /* radio color inside checked button */
    .payment-option-group 
    .payment-btn:has(input.payment_option:checked) input {
        accent-color: currentColor;
    }

</style>

<!-- Shipping Cost modal -->
<div class="modal fade modal-default" id="shipping-cost">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Shipping Cost</h5>
			   <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		   </div>
			<div class="modal-body pb-1">
				<div class="mb-3">
					<label class="form-label">Shipping Cost <span class="text-danger">*</span></label>
					<input type="number" step="any" class="form-control service_charge input_number" value="{{ $transaction->shipping_charge}}"  name="shipping_charge"> 
				</div>
			</div>
			<div class="modal-footer d-flex justify-content-end flex-wrap gap-2">
				<button type="button" class="btn btn-md btn-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-md btn-primary saveCharge" data-bs-dismiss="modal">Submit</button>
			</div>
		</div>
	</div>
</div>
<!-- /Shipping Cost -->

<!-- Discount modal -->
<div class="modal fade modal-default" id="discount">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Discount </h5>
			   
			   <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		   </div>
			<div class="modal-body pb-1">
				<div class="mb-3">
					<label class="form-label">Order Discount Type <span class="text-danger">*</span></label>
					<select class="select discount_type" name="discount_type">
						<option value="Fixed" {{ $transaction->discount_type=='Fixed' ?'selected':''}}>Fixed</option>
						<option value="Percentage" {{ $transaction->discount_type=='Percentage' ?'selected':''}}>Percentage</option>
					</select>
				</div>
				<div class="mb-3">
					<label class="form-label">Value <span class="text-danger">*</span></label>
					<input type="number" step="any" class="form-control discount_amount input_number" value="{{ $transaction->discount_amount}}" name="discount_amount">
				</div>
			</div>
			<div class="modal-footer d-flex justify-content-end flex-wrap gap-2">
				<button type="button" class="btn btn-md btn-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-md btn-primary savediscount" data-bs-dismiss="modal">Submit</button>
			</div>
		</div>
	</div>
</div>
<!-- /Discount -->

<!-- Payment modal -->
<div class="modal fade modal-default" id="payment-card">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <!-- Title -->
                    <h5 class="d-none d-md-flex modal-title mb-0">Finalize Sale</h5>
            
                    <!-- Customer Info -->
                    <div class="d-flex align-items-center gap-3 flex-wrap small text-muted">
                        <!-- Name -->
                        <span class="d-flex align-items-center gap-1">
                            <i class="fa fa-user"></i>
                            <strong class="ncustomer_name">
                                {{ $transaction->customer->name ?? 'N/A' }}
                            </strong>
                        </span>
            
                        <!-- Mobile -->
                        <span class="d-flex align-items-center gap-1">
                            <i class="fa fa-phone"></i>
                            <span class="ncustomer_phone">
                                {{ $transaction->customer->phone ?? 'N/A' }}
                            </span>
                        </span>
            
                        <!-- Email (hide on mobile) -->
                        <span class="d-none d-md-flex align-items-center gap-1 text-truncate">
                            <i class="fa fa-envelope"></i>
                            <span class="ncustomer_email">
                                {{ $transaction->customer->email ?? 'N/A' }}
                            </span>
                        </span>

                    </div>
                </div>
            
                <div class="d-flex align-items-center gap-3">
                    <!-- Due Amount -->
                    <!--<div class="px-3 py-1 rounded bg-light border">-->
                    <!--    <small class="text-muted me-1">Due:</small>-->
                    <!--    <strong class="text-danger due_balance">0 Tk</strong>-->
                    <!--</div>-->
            
                    <!-- Close Button -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
            </div>
		   
			<div class="modal-body pb-1">
                <label class="form-label fw-semibold">Order Information</label>
            
                <div class="order-info mb-3">
                    <div class="row text-center g-3">
            
                        <!-- Sub Total -->
                        <div class="col-6 col-md-2">
                            <div class="border rounded p-2">
                                <small class="text-muted d-block">Sub Total</small>
                                <span class="fw-bold">Tk <span class="sub_total">0.00</span></span>
                            </div>
                        </div>
            
                        <!-- Discount -->
                        <div class="col-6 col-md-2">
                            <div class="border rounded p-2">
                                <small class="text-muted d-block">Discount</small>
                                <span class="text-danger fw-bold">- Tk <span class="discount">0.00</span></span>
                            </div>
                        </div>
            
                        <!-- Shipping Cost -->
                        <div class="col-6 col-md-2">
                            <div class="border rounded p-2">
                                <small class="text-muted d-block">Shipping Cost</small>
                                <span class="fw-bold">Tk <span class="charge">0.00</span></span>
                            </div>
                        </div>
            
                        <!-- Total Items -->
                        <div class="col-6 col-md-2">
                            <div class="border rounded p-2">
                                <small class="text-muted d-block">Total Items</small>
                                <span class="fw-bold"><span class="total_item">0</span></span>
                            </div>
                        </div>
                        
                        <!-- Previous due -->
                        <div class="col-6 col-md-2">
                            <div class="border rounded p-2">
                                <small class="text-muted d-block">Previous due</small>
                                <span class="text-danger fw-bold"><span  class="due_balance">0</span></span>
                            </div>
                        </div>
            
                        <!-- Total -->
                        <div class="col-6 col-md-2">
                            <div class="border rounded p-2 bg-light">
                                <small class="text-muted d-block">Total</small>
                                <span class="fw-bold">Tk <span class="final_amount">0.00</span></span>
                            </div>
                        </div>
            
                    </div>
                </div>

                <label class="form-label fw-semibold">Payment Option</label>
                <div class="d-flex gap-2 mb-3 payment-option-group">
                    <label for="full_payment"
                        class="form-check btn btn-outline-success payment-btn cursor-pointer">
                        <input class="form-check-input payment_option mt-0"
                               type="radio"
                               name="payment_option"
                               id="full_payment"
                               value="full"
                               checked>
                        Full Payment
                    </label>
                
                    <label for="partial_payment"
                        class="form-check btn btn-outline-warning payment-btn cursor-pointer">
                        <input class="form-check-input payment_option mt-0"
                               type="radio"
                               name="payment_option"
                               id="partial_payment"
                               value="partial">
                        Partial Payment
                    </label>
                
                    <label for="full_due"
                        class="form-check btn btn-outline-danger payment-btn cursor-pointer">
                        <input class="form-check-input payment_option mt-0"
                               type="radio"
                               name="payment_option"
                               id="full_due"
                               value="due">
                        Full Due
                    </label>
                </div>
                
				<div class="row payment_main_row">
				    <div class="col-md-6 payment_amount_section d-none mb-3">
                        <label class="form-label">Payment Amount</label>
                        <div class="border rounded p-3 bg-light">
                            <div class="row g-3">
                                <div class="col-12 given_amount_wrapper">
                                    <label class="form-label fw-semibold">Given Amount</label>
                                    <input type="number" class="form-control given_amount" value="0" style="background-color: #d0e7ff;">
                                    <small class="text-danger d-none given-error">
                                        This Amount must be equal to or greater than Total Amount
                                    </small>
                                </div>
                                
                                <div class="col-12 received_amount_wrapper">
                                    <label class="form-label fw-semibold">Receiving Amount</label>
                                    <input type="number" class="form-control received_amount" name="received_amount" value="0">
                                    <small class="text-danger d-none received-error"></small>
                                </div>
                                
                                <div class="col-12 return_amount_wrapper">
                                    <label class="form-label fw-semibold">Return Amount</label>
                                    <input type="number" class="form-control return_amount" value="" readonly style="background-color: #d4ffd4; color: #FF0000 ;">
                                </div>
                            
                                <div class="col-12 due_amount_wrapper">
                                    <label class="form-label fw-semibold">Due Amount</label>
                                    <input type="number" class="form-control due_amount" readonly value="0" style="background-color: #ffd4d4;">
                                </div>
                            
                                <div class="col-12 next_payment_date_wrapper">
                                    <label class="form-label fw-semibold">Next Payment Date</label>
                                    <input type="date" class="form-control next_payment_date" name="next_payment_date" min="<?= date('Y-m-d'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    
    				<div class="col-md-6 payment_type_section mb-3">
    				    <label class="form-label">Payment Type</label>
    				    <div class="border rounded p-3 bg-light">
    				        @foreach($payments as $index => $pay)
                            <div class="">
                                <input type="hidden" name="payment[{{ $index }}][id]" value="{{ $pay['id'] }}">
                            
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <input type="radio" class="btn-check" name="payment[{{ $index }}][method]"
                                           id="cash_{{ $index }}" value="cash"
                                           {{ $pay['method']=='cash'?'checked':'' }}>
                                    <label class="btn btn-outline-secondary btn-sm" for="cash_{{ $index }}">Cash</label>
                            
                                    <input type="radio" class="btn-check" name="payment[{{ $index }}][method]"
                                           id="card_{{ $index }}" value="card"
                                           {{ $pay['method']=='card'?'checked':'' }}>
                                    <label class="btn btn-outline-secondary btn-sm" for="card_{{ $index }}">Card</label>
                            
                                    <input type="radio" class="btn-check" name="payment[{{ $index }}][method]"
                                           id="bank_{{ $index }}" value="bank"
                                           {{ $pay['method']=='bank'?'checked':'' }}>
                                    <label class="btn btn-outline-secondary btn-sm" for="bank_{{ $index }}">Bank</label>
                            
                                    <input type="radio" class="btn-check" name="payment[{{ $index }}][method]"
                                           id="mobile_{{ $index }}" value="mobile_banking"
                                           {{ $pay['method']=='mobile_banking'?'checked':'' }}>
                                    <label class="btn btn-outline-secondary btn-sm" for="mobile_{{ $index }}">Mobile Banking</label>
                            
                                    <input type="radio" class="btn-check" name="payment[{{ $index }}][method]"
                                           id="other_{{ $index }}" value="other"
                                           {{ $pay['method']=='other'?'checked':'' }}>
                                    <label class="btn btn-outline-secondary btn-sm" for="other_{{ $index }}">Other</label>
                                </div>
                            
                                <!-- Card -->
                                <div class="method-box d-none" id="card_box_{{ $index }}">
                                    
                                    <label class="form-label">Card Title *</label>
                                    <input type="text" class="form-control mb-2"
                                           name="payment[{{ $index }}][card_title]" value="{{ $pay['card_title']}}"
                                           placeholder="Card Title">
                                           
                                           
                                    <label class="form-label">Card Number *</label>
                                    <input type="text" class="form-control mb-2"
                                           name="payment[{{ $index }}][card_number]" value="{{ $pay['card_number']}}"
                                           placeholder="Card Number">
                                           
                                </div>
                            
                                <!-- Bank -->
                                <div class="method-box d-none" id="bank_box_{{ $index }}">
                                    <label class="form-label">Bank Name *</label>
                                    <input type="text" class="form-control mb-2"
                                           name="payment[{{ $index }}][bank_name]" value="{{ $pay['bank_name']}}"
                                           placeholder="Bank Name">
                            
                                    <label class="form-label">Account / Cheque No *</label>
                                    <input type="text" class="form-control mb-2"
                                           name="payment[{{ $index }}][account_no]" value="{{ $pay['account_no']}}"
                                           placeholder="Account / Cheque No">
                                </div>
                            
                                <!-- Mobile Banking -->
                                <div class="method-box d-none" id="mobile_box_{{ $index }}">
                                    <label class="form-label">Mobile Banking Provider *</label>
                                    <select class="form-control mb-2"
                                            name="payment[{{ $index }}][provider]">
                                        <option value="bkash">bKash</option>
                                        <option value="nagad">Nagad</option>
                                        <option value="rocket">Rocket</option>
                                        <option value="upay">Upay</option>
                                    </select>
                                    
                                    <label class="form-label">Mobile No *</label>
                                    <input type="text" class="form-control mb-2"
                                           name="payment[{{ $index }}][mobile_no]" value="{{ $pay['mobile_no']}}"
                                           placeholder="Mobile No">
                                           
                                </div>
                            
                                <!-- Other -->
                                <div class="method-box d-none" id="transaction_id_{{ $index }}" >
                                    <label class="form-label">Transaction ID *</label>
                                    <input type="text" class="form-control mb-2"
                                           name="payment[{{ $index }}][transaction_no]" value="{{ $pay['transaction_no']}}"
                                           placeholder="Transaction ID">
                                </div>
                            
                                <!-- Common -->
                                <div class="mt-2">
                                    <label class="form-label">Enter Amount *</label>
                                    <input type="text" class="form-control mb-2 pay_amount"
                                           name="payment[{{ $index }}][pay_amount]"
                                           value="{{ $pay['amount'] }}"
                                           placeholder="Tk 0" readonly>
                            
                                    <label class="form-label">Note</label>
                                    <textarea class="form-control" rows="2"
                                              name="payment[{{ $index }}][note]"
                                              placeholder="Note"> {{ $pay['note'] }} </textarea>
                                </div>
                            </div>
                        @endforeach
    				    </div>
    					
    				</div> 	
				    
                    <div class="col-6 mb-3">
    				    <div class="form-group">
    				        <label class="form-label fw-semibold">Delivery Date</label>
                            <input type="date" class="form-control" name="delivery_date" value="{{ $transaction->delivery_date}}">
    				    </div>
    				</div>
    				
    				<div class="col-6 mb-3">
    				    <div class="form-group">
    				        <label class="form-label fw-semibold"> Order From</label>
    				        <select class="form-control select2" name="order_from_id">
    				            @foreach($order_froms as $order_from)
    				            <option value="{{ $order_from->id}}" {{ $transaction->order_from_id==$order_from->id ||  $order_from->is_default==1  ?'selected':''}} > {{ $order_from->title }} </option>
    				            @endforeach
    				        </select>
    				    </div>
    				</div>
				</div>

			<div class="row">
                <div class="col-12">
            
                    <!-- Customer Shipping Address Card -->
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Customer Shipping Address</h5>
                            <div class="d-flex gap-2">
                                <!-- Add New Button -->
                                <button type="button" class="btn btn-sm btn-primary add_new_shipping">
                                    + Add New
                                </button>
                                
                                <!-- Hide Form Button (শুরুতে লুকানো থাকবে) -->
                                <button type="button" class="btn btn-sm btn-outline-danger hide_shipping_form d-none">
                                    Hide Form
                                </button>
                            </div>
                        </div>
            
                        <div class="card-body">
                            <div class="row g-3 customer_address">
                                @if($transaction->contact)
                                    @foreach($transaction->contact->contact_address as $i => $address)
                                        <div class="col-12 col-md-6 col-lg-4">
                                            <label
                                                class="option-box border rounded p-3 d-flex gap-3 w-100 cursor-pointer h-100">
                                                
                                                <input
                                                    class="form-check-input mt-1"
                                                    type="radio"
                                                    name="shipping_id"
                                                    id="addr{{ $address->id }}"
                                                    value="{{ $address->id }}"
                                                    {{ $transaction->shipping_id == $address->id || $i == 0 ? 'checked' : '' }}>
            
                                                <div class="flex-grow-1">
                                                    <strong>{{ $address->name }}</strong><br>
                                                    {{ $address->address }}<br>
                                                    @if($address->district)
                                                        {{ $address->district->name }}
                                                        {{ $address->upazila ? ' - '.$address->upazila->name : '' }}<br>
                                                    @endif
                                                    📞 {{ $address->phone }}
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
            
                    <!-- Add New Shipping Address Card -->
                    <div class="card mb-3 shadow-sm shipping_new_form" style="display:none;">
                        <div class="card-header">
                            <h5 class="mb-0">Add New Shipping Address</h5>
                        </div>
            
                        <div class="card-body">
                            <div class="row g-3">
            
                                <div class="col-md-6">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" placeholder="Full Name" required>
                                </div>
            
                                <div class="col-md-6">
                                    <label class="form-label">Mobile <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control mobile_validation"
                                           id="customer_phone" placeholder="+8801XXXXXXXXX">
                                </div>
            
                                <div class="col-md-6">
                                    <label class="form-label">District</label>
                                    <select class="form-select select2 district_id" id="district_id">
                                        <option value="">Select District</option>
                                        @foreach($districts as $district)
                                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
            
                                <div class="col-md-6">
                                    <label class="form-label">Thana</label>
                                    <select class="form-select select2 upazila_id" id="upazila_id">
                                        <option value="">Select Thana</option>
                                    </select>
                                </div>
            
                                <div class="col-md-6">
                                    <label class="form-label">Landmark (Optional)</label>
                                    <input type="text" class="form-control" id="landmark"
                                           placeholder="Tangail Sadar">
                                </div>
            
                                <div class="col-md-6">
                                    <label class="form-label">Full Address</label>
                                    <input type="text" class="form-control" id="address"
                                           placeholder="Tangail Sadar, Dhaka, Bangladesh">
                                </div>
            
                                <div class="col-12 d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-success add_shipping_form">
                                        Save
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary clear_shipping_form">
                                        Clear
                                    </button>
                                </div>
            
                            </div>
                        </div>
                    </div>
            
                </div>
            </div>

			</div>
			<div class="modal-footer d-flex justify-content-end flex-wrap gap-2">
				<button type="button" class="btn btn-md btn-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-md btn-primary final_sale">Submit</button>
			</div>
		</div>
	</div>
</div>

<!-- Quotation modal -->
<div class="modal fade modal-default" id="quotation-modal">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header">
                <h5 class="modal-title">Finalize Quotation</h5>

                <div class="d-flex align-items-center gap-3">
                    <div class="px-3 py-1 rounded bg-light border">
                        <small class="text-muted">Due:</small>
                        <strong class="text-danger due_balance">0.00 Tk</strong>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
            </div>

            <!-- Body -->
            <div class="modal-body pb-4">

                <!-- Customer Info (Simple Row) -->
                <label class="form-label fw-semibold">Customer Information</label>
                <div class="border rounded p-2 mb-3 bg-light">
                    <div class="row g-2 align-items-center small">
                        <div class="col-md-4">
                            <span class="text-muted">Name:</span>
                            <strong class="ms-1 ncustomer_name">
                                {{ $transaction->customer->name ?? 'N/A' }}
                            </strong>
                        </div>

                        <div class="col-md-4">
                            <span class="text-muted">Mobile:</span>
                            <strong class="ms-1 ncustomer_phone">
                                {{ $transaction->customer->phone ?? 'N/A' }}
                            </strong>
                        </div>

                        <div class="col-md-4 text-truncate">
                            <span class="text-muted">Email:</span>
                            <strong class="ms-1 ncustomer_email">
                                {{ $transaction->customer->email ?? 'N/A' }}
                            </strong>
                        </div>
                    </div>
                </div>

                <!-- Validity Date -->
                <div class="form-group">
                    <label class="form-label fw-semibold">Quotation Validity Date</label>
                    <input type="date"
                           class="form-control"
                           name="price_expiry_date"
                           value="{{ $transaction->price_expiry_date }}">
                </div>

            </div>

            <!-- Footer -->
            <div class="modal-footer d-flex gap-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button type="button" class="btn btn-primary final_sale quotation">
                    Submit
                </button>
            </div>

        </div>
    </div>
</div>


<script>
    document.querySelectorAll('.pay_amount').forEach(input => {
        input.classList.add('bg-light');
    });

    document.addEventListener('change', function(e){
    
        if(!e.target.name || !e.target.name.includes('[method]')) return;
    
        let index = e.target.name.match(/\d+/)[0];
    
        // Hide all boxes
        document.querySelectorAll('#card_box_'+index+',#bank_box_'+index+',#mobile_box_'+index+',#other_box_'+index+',#transaction_id_'+index)
            .forEach(el => el.classList.add('d-none'));
    
        // Show selected
        if(e.target.value === 'card'){
            document.getElementById('card_box_'+index).classList.remove('d-none');
            document.getElementById('transaction_id_'+index).classList.remove('d-none');
        }
        if(e.target.value === 'bank'){
            document.getElementById('bank_box_'+index).classList.remove('d-none');
            document.getElementById('transaction_id_'+index).classList.remove('d-none');
        }
        if(e.target.value === 'mobile_banking'){
            document.getElementById('mobile_box_'+index).classList.remove('d-none');
            document.getElementById('transaction_id_'+index).classList.remove('d-none');
        }
    });
    
    // Page load support
    document.querySelectorAll('input[type=radio]:checked').forEach(r=>{
        r.dispatchEvent(new Event('change'));
    });
    
    document.addEventListener('DOMContentLoaded', function () {
        const addBtn      = document.querySelector('.add_new_shipping');
        const hideBtn     = document.querySelector('.hide_shipping_form');
        const formBox     = document.querySelector('.shipping_new_form');
    

        addBtn.addEventListener('click', function () {
            formBox.style.display = 'block';
            hideBtn.classList.remove('d-none');
            addBtn.classList.add('d-none');
    
            // smooth scroll
            formBox.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    

        hideBtn.addEventListener('click', function () {
            formBox.style.display = 'none';
            hideBtn.classList.add('d-none'); 
            addBtn.classList.remove('d-none');
        });
    
        // Clear form (আগের কোড রাখতে পারেন)
        const clearBtn = document.querySelector('.clear_shipping_form');
        clearBtn.addEventListener('click', function () {
            formBox.querySelectorAll('input, select, textarea').forEach(el => {
                if (el.type === 'checkbox' || el.type === 'radio') {
                    el.checked = false;
                } else {
                    el.value = '';
                }
            });
            $(formBox).find('.select2').val(null).trigger('change');
        });
    });

    document.addEventListener('DOMContentLoaded', function () {

        const paymentType   = document.querySelector('.payment_type_section');
        const paymentAmount = document.querySelector('.payment_amount_section');
        const givenWrapper  = document.querySelector('.given_amount_wrapper');
        const returnWrapper = document.querySelector('.return_amount_wrapper');
        const receivedWrapper = document.querySelector('.received_amount_wrapper');
        const dueWrapper    = document.querySelector('.due_amount_wrapper');
        const dateWrapper   = document.querySelector('.next_payment_date_wrapper');
    
        function resetLayout() {
            paymentType.classList.remove('d-none', 'col-md-12', 'col-md-6');
            paymentAmount.classList.remove('d-none', 'col-md-12', 'col-md-6');
    
            givenWrapper.classList.remove('d-none');
            returnWrapper.classList.remove('d-none');
            receivedWrapper.classList.remove('d-none');
            dueWrapper.classList.remove('d-none');
            dateWrapper.classList.remove('d-none');
        }
    
        function togglePaymentSections() {
            const optionEl = document.querySelector('input[name="payment_option"]:checked');
            if (!optionEl) return;
            const option = optionEl.value;
    
            resetLayout();
    
            if (option === 'full') {
                // Full Payment
                paymentType.classList.add('col-md-6');
                paymentAmount.classList.add('col-md-6');
                
                // paymentAmount stays visible
                receivedWrapper.classList.add('d-none');
                dueWrapper.classList.add('d-none');
                dateWrapper.classList.add('d-none');
                
                receivedInput.setAttribute('readonly', 'readonly');
            }
            else if (option === 'partial') {
                // Partial Payment
                paymentType.classList.add('col-md-6');
                paymentAmount.classList.add('col-md-6');
                
                receivedInput.removeAttribute('readonly');
            }
            else if (option === 'due') {
                // Full Due
                paymentType.classList.add('d-none');
        
                paymentAmount.classList.add('col-md-12');
                
                receivedWrapper.classList.add('d-none');
                givenWrapper.classList.add('d-none');
                returnWrapper.classList.add('d-none');
                
                receivedInput.setAttribute('readonly', 'readonly');
            }
        }
    
        document.querySelectorAll('.payment_option').forEach(el => {
            el.addEventListener('change', togglePaymentSections);
        });
    
        togglePaymentSections(); // initial load
    });

</script>

<script>
    const nextPaymentInput = document.querySelector('.next_payment_date');
    const today = new Date().toISOString().split('T')[0];
    nextPaymentInput.setAttribute('min', today);
</script>


<!-- Combined Payment Script for Full Payment, Partial Payment, Full Due -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
    
        const payInputs      = document.querySelectorAll('.pay_amount');
        const dueInput       = document.querySelector('.due_amount');
        const givenInput     = document.querySelector('.given_amount');
        const returnInput    = document.querySelector('.return_amount');
        const receivedInput  = document.querySelector('.received_amount');
        const givenError     = document.querySelector('.given-error');
        const receivedError  = document.querySelector('.received-error');
        const finalSaleBtn   = document.querySelector('.final_sale');
    
        // Calculate total from all Enter Amount fields
        function getEnterAmountTotal() {
            let total = 0;
            payInputs.forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            return total;
        }
        
        function resetPaymentInputs() {
            givenInput.value = '';
            receivedInput.value = '';
            returnInput.value = '';
            dueInput.value = '';
        
            givenError.classList.add('d-none');
            receivedError.classList.add('d-none');
        
            givenInput.classList.remove('is-invalid');
            receivedInput.classList.remove('is-invalid');
        }

    
        // Sync Received Amount = Given Amount for Partial Payment
        function syncReceivedWithGiven() {
            const selected = document.querySelector('input[name="payment_option"]:checked');
            if (!selected) return;
    
            if (selected.value === 'partial') {
                const givenValue = parseFloat(givenInput.value) || 0;
                receivedInput.value = givenValue.toFixed(2);
                validateAndCalculate();
            }
        }
    
        // Handle payment option changes
        function handlePaymentOptionChange() {
            const selected = document.querySelector('input[name="payment_option"]:checked');
            if (!selected) return;
            const option = selected.value;
        
            // 🔥 RESET values when switching payment option
            resetPaymentInputs();
        
            // Reset readonly states
            givenInput.removeAttribute('readonly');
            receivedInput.removeAttribute('readonly');
        
            if (option === 'full') {
                receivedInput.setAttribute('readonly', 'readonly');
            }
            else if (option === 'partial') {
                receivedInput.removeAttribute('readonly');
            }
            else if (option === 'due') {
                receivedInput.setAttribute('readonly', 'readonly');
                givenInput.setAttribute('readonly', 'readonly');
        
                // Full due auto set
                dueInput.value = getEnterAmountTotal().toFixed(2);
                returnInput.value = '0.00';
            }
        
            validateAndCalculate();
        }

    
        // Validation + calculation
        function validateAndCalculate() {
            const selected = document.querySelector('input[name="payment_option"]:checked');
            if (!selected) return;
            const option = selected.value;
            const total = getEnterAmountTotal();
            const given = parseFloat(givenInput.value) || 0;
            const received = parseFloat(receivedInput.value) || 0;
    
            // Reset errors
            givenError.classList.add('d-none');
            receivedError.classList.add('d-none');
            givenInput.classList.remove('is-invalid');
            receivedInput.classList.remove('is-invalid');
    
            if (option === 'full') {
                if (given < total) {
                    givenError.textContent = 'This Amount must be equal to or greater than Total Amount';
                    givenError.classList.remove('d-none');
                    givenInput.classList.add('is-invalid');
                    returnInput.value = '0.00';
                    receivedInput.value = '0.00';
                    dueInput.value = '0.00';
                } else {
                    returnInput.value = (given - total).toFixed(2);
                    receivedInput.value = total.toFixed(2);
                    dueInput.value = '0.00';
                }
            } else if (option === 'partial') {
                let hasError = false;
                // --- Received validations ---
                if (received <= 0) {
                    receivedError.textContent = 'Received Amount must be greater than 0';
                    receivedError.classList.remove('d-none');
                    receivedInput.classList.add('is-invalid');
                    hasError = true;
                }
            
                if (received > given) {
                    receivedError.textContent = 'Received Amount cannot be greater than Given Amount';
                    receivedError.classList.remove('d-none');
                    receivedInput.classList.add('is-invalid');
                    hasError = true;
                }
            
                if (received > total) {
                    receivedError.textContent = 'Received Amount cannot be more than the Total Amount';
                    receivedError.classList.remove('d-none');
                    receivedInput.classList.add('is-invalid');
                    hasError = true;
                }
            
                // --- Given validations ---
                if (given > total) {
                    givenError.textContent = 'Given Amount cannot be more than the Total Amount';
                    givenError.classList.remove('d-none');
                    givenInput.classList.add('is-invalid');
                    hasError = true;
                }
            
                // ❌ If any error → stop calculation
                if (hasError) {
                    returnInput.value = '0.00';
                    dueInput.value = '0.00';
                    return;
                }
            
                // ✅ All valid → calculate
                returnInput.value = (given - received).toFixed(2);
                dueInput.value = (total - received).toFixed(2);
            } else if (option === 'due') {
                dueInput.value = total.toFixed(2);
                returnInput.value = '0.00';
            }
        }
    
        // Final submit validation
        finalSaleBtn.addEventListener('click', function(e) {
            const selected = document.querySelector('input[name="payment_option"]:checked');
            if (!selected) return;
            const option = selected.value;
            const total = getEnterAmountTotal();
            const given = parseFloat(givenInput.value) || 0;
            const received = parseFloat(receivedInput.value) || 0;
    
            let invalid = false;
    
            if (option === 'full' && given < total) invalid = true;
            if (option === 'partial' && (received <= 0 || received > given || received > total)) invalid = true;
    
            if (invalid) {
                e.preventDefault();
                validateAndCalculate(); // show errors below inputs
                return false;
            }
        });
    
        // Event listeners
        document.querySelectorAll('.payment_option').forEach(radio => {
            radio.addEventListener('change', handlePaymentOptionChange);
        });
    
        givenInput.addEventListener('input', () => { syncReceivedWithGiven(); validateAndCalculate(); });
        givenInput.addEventListener('change', () => { syncReceivedWithGiven(); validateAndCalculate(); });
        givenInput.addEventListener('keyup', () => { syncReceivedWithGiven(); validateAndCalculate(); });
    
        receivedInput.addEventListener('input', validateAndCalculate);
        receivedInput.addEventListener('change', validateAndCalculate);
        receivedInput.addEventListener('keyup', validateAndCalculate);
    
        // Initial setup
        handlePaymentOptionChange();
    
    });
</script>

