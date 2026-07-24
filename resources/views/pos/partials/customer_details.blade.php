<div class="accordion-item border-0 shadow-sm mb-2">
    <h2 class="accordion-header" id="headingOne">
      <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
        Customer Details
      </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#customerDetailsAccordion">
      <div class="accordion-body">
            <div class="mb-2"><i class="ti ti-user me-2"></i><strong> {{ $item->name }} </strong></div>
            <div class="mb-2"><i class="ti ti-phone me-2"></i> {{ $item->mobile }} </div>
            <div class="mb-2"><i class="ti ti-mail me-2"></i> {{ $item->email }} </div>
            <div class="mb-2"><i class="ti ti-shopping-bag me-2"></i>{{ $item->total ??0 }} Orders</div>
            <div><i class="ti ti-currency-dollar me-2"></i>Previous due: <strong class="exist_due_balance">Tk {{ $item->total_sell - $item->total_sell_paid}} </strong></div>
        </div>
    </div>
  </div>

  <!-- Section 2: Billing Address -->
    <div class="accordion-item border-0 shadow-sm mb-2">
        <h2 class="accordion-header" id="headingTwo">
          <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
            Billing Address
          </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#customerDetailsAccordion">
            <div class="accordion-body">
                <div class="mb-2">
                  <i class="ti ti-user me-2"></i> District : {{$item->pdistrict->name ??''}}
                </div>
                
                <div class="mb-2">
                  <i class="ti ti-user me-2"></i> Thana : {{$item->pthana->name ??''}}
                </div>
                
                
                <div class="mb-1">
                  <i class="ti ti-map-pin me-2"></i> {{ $item->address}} 
                </div>
                
            </div>
        </div>
    </div>
    
    <div class="accordion-item border-0 shadow-sm mb-2">
        <h2 class="accordion-header" id="headingTwo">
          <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseShippimng">
            Shipping Address
          </button>
        </h2>
        <div id="collapseShippimng" class="accordion-collapse collapse row" data-bs-parent="#customerDetailsAccordion">
            @foreach($item->contact_address as $address)
            <div class="accordion-body option-box align-items-start gap-5 col-6">
                
                <div class="mb-2"><i class="ti ti-user me-2"></i><strong> {{ $address->name }} </strong></div>
                @if($address->district)
                    <div class="mb-2">
                        {{ $address->district->name }} - {{ $address->upazila->name??''}}<br>
                    </div>
                @endif
                
                <div class="mb-1">
                  <i class="ti ti-map-pin me-2"></i> {{ $address->address}}
                </div>
                
                <div class="mb-2"><i class="ti ti-phone me-2"></i> {{ $address->phone }} </div>
                
                
            </div>
            @endforeach
        </div>
    </div>
    

  

  <!-- Section 5: Delivery Date -->


  <!-- Section 7: Invoice -->
<!--  <div class="accordion-item border-0 shadow-sm mb-2">-->
<!--    <h2 class="accordion-header" id="headingSeven">-->
<!--      <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven">-->
<!--        Invoice-->
<!--      </button>-->
<!--    </h2>-->
<!--    <div id="collapseSeven" class="accordion-collapse collapse" data-bs-parent="#customerDetailsAccordion">-->
<!--      <div class="accordion-body">-->
        
        
<!--      </div>-->
<!--    </div>-->
<!--</div>-->