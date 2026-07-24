<div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel"> Vendor Order Payment #{{$item->invoice_no}} </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <form action="{{route('vendor_order_payments.update',[$item->id])}}" method="POST" id="ajax_form">
              @csrf
              @method('PATCH')
              <div class="row">
                
                @php
                    $index=1;
                @endphp
                
                <div class="mb-3">
                            <label class="form-label">Payment Type</label>
                        
                        
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <input type="radio" class="btn-check" name="method"
                                       id="cash_{{ $index }}" value="cash" checked>
                                <label class="btn btn-outline-secondary btn-sm" for="cash_{{ $index }}">Cash</label>
                        
                                <input type="radio" class="btn-check" name="method"
                                       id="card_{{ $index }}" value="card">
                                <label class="btn btn-outline-secondary btn-sm" for="card_{{ $index }}">Card</label>
                        
                                <input type="radio" class="btn-check" name="method"
                                       id="bank_{{ $index }}" value="bank">
                                <label class="btn btn-outline-secondary btn-sm" for="bank_{{ $index }}">Bank</label>
                        
                                <input type="radio" class="btn-check" name="method"
                                       id="mobile_{{ $index }}" value="mobile_banking">
                                <label class="btn btn-outline-secondary btn-sm" for="mobile_{{ $index }}">Mobile Banking</label>
                        
                                <input type="radio" class="btn-check" name="method"
                                       id="other_{{ $index }}" value="other">
                                <label class="btn btn-outline-secondary btn-sm" for="other_{{ $index }}">Other</label>
                            </div>
                        
                            <!-- Card -->
                            <div class="method-box d-none" id="card_box_{{ $index }}">
                                
                                <label class="form-label">Card Title *</label>
                                <input type="text" class="form-control mb-2"
                                       name="card_title"
                                       placeholder="Card Title">
                                       
                                       
                                <label class="form-label">Card Number *</label>
                                <input type="text" class="form-control mb-2"
                                       name="card_number"
                                       placeholder="Card Number">
                                       
                            </div>
                        
                            <!-- Bank -->
                            <div class="method-box d-none" id="bank_box_{{ $index }}">
                                <label class="form-label">Bank Name *</label>
                                <input type="text" class="form-control mb-2"
                                       name="bank_name"
                                       placeholder="Bank Name">
                        
                                <label class="form-label">Account / Cheque No *</label>
                                <input type="text" class="form-control mb-2"
                                       name="account_no"
                                       placeholder="Account / Cheque No">
                            </div>
                        
                            <!-- Mobile Banking -->
                            <div class="method-box d-none" id="mobile_box_{{ $index }}">
                                <label class="form-label">Mobile Banking Provider *</label>
                                <select class="form-control mb-2"
                                        name="provider">
                                    <option value="bkash">bKash</option>
                                    <option value="nagad">Nagad</option>
                                    <option value="rocket">Rocket</option>
                                    <option value="upay">Upay</option>
                                </select>
                                
                                <label class="form-label">Mobile No *</label>
                                <input type="text" class="form-control mb-2"
                                       name="mobile_no"
                                       placeholder="Mobile No">
                                       
                            </div>
                        
                            <!-- Other -->
                            <div class="method-box d-none" id="transaction_id_{{ $index }}">
                                <label class="form-label">Transaction ID *</label>
                                <input type="text" class="form-control mb-2"
                                       name="transaction_no"
                                       placeholder="Transaction ID">
                            </div>
                            
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label> Date </label>
                                    <input type="date" name="paid_on" value="{{ date('Y-m-d')}}" class="form-control">
                                </div>
                            </div>
                
                        
                            <!-- Common -->
                            <div class="mt-2">
                                <label class="form-label">Enter Amount *</label>
                                <input type="text" class="form-control mb-2 pay_amount"
                                       name="amount" value="{{ $due}}"
                                       placeholder="Tk 0">
                        
                                <label class="form-label">Note</label>
                                <textarea class="form-control" rows="2"
                                          name="note"
                                          placeholder="Note">  </textarea>
                            </div>
                        </div>
                        


              </div>
              <br>
              <input type="submit" value="Save" class="btn btn-success">
              <hr>
          </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
  
<script>
    document.addEventListener('change', function(e){
    
        if(!e.target.name || !e.target.name.includes('method')) return;
    
        let index = 1;
    
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
</script>