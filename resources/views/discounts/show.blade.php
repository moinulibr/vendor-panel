
@php
    $selectedProducts = $discount->discount_prodcuts->map(function($p){
        return ['sku' => $p->id, 'name' => $p->name];
    });
@endphp

<div class="modal-dialog modal-lg">
  <div class="modal-content">

        <!-- Header -->
        <div class="modal-header">
            <h5 class="modal-title fw-semibold">Discount Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- Body -->
        <div class="modal-body">
            <div class="row g-3">

                <!-- Title & Amount -->
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <small class="text-muted d-block">Title</small>
                        <span>{{ $discount->title ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <small class="text-muted d-block">Amount</small>
                        <span>{{ $discount->amount ?? 'N/A' }}</span>
                    </div>
                </div>

                <!-- Discount Type & Status -->
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <small class="text-muted d-block">Discount Type</small>
                        <span>{{ ucfirst($discount->discount_type) ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <small class="text-muted d-block">Status</small>
                        <span>{{ $discount->status == 1 ? 'Active' : 'De-Active' }}</span>
                    </div>
                </div>

                <!-- Start & End Date -->
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <small class="text-muted d-block">Start Date</small>
                        <span>{{ $discount->start ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <small class="text-muted d-block">End Date</small>
                        <span>{{ $discount->end ?? 'N/A' }}</span>
                    </div>
                </div>
                
                @if(empty($discount->is_product))
                <!-- Category & Brand -->
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <small class="text-muted d-block">Category</small>
                        <span>{{ $discount->category->name ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <small class="text-muted d-block">Brand</small>
                        <span>{{ $discount->brand->name ?? 'N/A' }}</span>
                    </div>
                </div>
                @endif

                <!-- User/Vendor -->
                <div class="col-12">
                    <div class="border rounded p-3">
                        <small class="text-muted d-block">Vendor</small>
                        <span>{{ $discount->user->name ?? 'N/A' }}</span>
                    </div>
                </div>
                
                @if($discount->is_product)
                <div class="col-12">
                    <h4> Products </h4>
                    <div class="border rounded p-3">
                        @foreach($selectedProducts as $p)
        			    <a class="btn btn-sm btn-primary">{{ $p['name']}}</a>
        			    @endforeach
                    </div>
                </div>
                @endif
                
				    

            </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                Close
            </button>
        </div>

  </div>
</div>
