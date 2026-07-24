@extends('layouts.app')
@section('content')
<div class="content">
	<div class="card">
        <div class="card-header">
            <div class="row g-3">
                <div class="">
        	        <h5>Supplier Info </h5>
        	    </div>
                <!-- Contact Info -->
                <div class="row g-2">

                    <!-- Personal Info -->
                    <div class="col-12 col-md-4">
                        <div class="p-3 border rounded h-100">
                            <b>Name :</b> <span class="fw-bold text-primary">{{ $contact->name }}</span><br>
                            <b>Mobile :</b> {{ $contact->mobile }} <br>
                            <b>Email :</b> {{ $contact->email }} <br>
                            <b>Address :</b> {{ $contact->address }} <br>
                
                            <b>Status :</b>
                            <span class="badge table-badge bg-{{ $contact->status=='1' ?'success':'warning'}} fw-medium fs-10">
                                {{ $contact->status == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                
                    <!-- Vendor (User) Info -->
                    <div class="col-12 col-md-4">
                        <div class="p-3 border rounded h-100">
                            <b>Vendor :</b> {{ $contact->user_name ?? 'N/A' }} <br>
                            <b>Vendor Email :</b> {{ $contact->user_email ?? 'N/A' }} <br>
                            <b>Vendor Mobile :</b> {{ $contact->user_mobile ?? 'N/A' }} <br>
                        </div>
                    </div>
                
                    <!-- Purchase Info -->
                    <div class="col-12 col-md-4">
                        <div class="p-3 border rounded h-100">
                            <b>Total Purchase :</b> {{ number_format($contact->total_purchase, 2) }} <br>
                            <b>Total Paid :</b> {{ number_format($contact->total_purchase_paid ?? 0, 2) }} <br>
                            <b>Total Due :</b>
                            <span class="text-danger fw-bold">
                                {{ number_format($contact->total_purchase - ($contact->total_purchase_paid ?? 0), 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
	
	<div class="card">
		<div class="card-header">
            <div class="row g-3 align-items-end">
        
                <!-- Search -->
            <div class="col-12 col-md-6 col-lg-5">
                    <label class="form-label">Search</label>
                    <div class="search-set w-100">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="ti ti-search"></i>
                            </span>
                            <input type="text" id="search" class="form-control" placeholder="Search Reference...">
                        </div>
                    </div>
                </div>
        
                <!-- Date Range -->
                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label">Choose Date</label>
                    <div class="input-icon-start position-relative">
                        <input type="text" class="form-control bookingrange" placeholder="dd/mm/yyyy - dd/mm/yyyy">
                        <span class="input-icon-left">
                            <i class="ti ti-calendar"></i>
                        </span>
                    </div>
                </div>
        
                <!-- Shipping Status -->
                <div class="col-12 col-md-6 col-lg-2">
                    <label class="form-label">Shipping Status</label>
                    <select class="form-select" id="shipping_status">
                        <option value="">All</option>
                        @foreach(getStatusList() as $i=> $status)
                            <option value="{{ $i }}">{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
        
                <!-- Payment Status -->
                <div class="col-12 col-md-6 col-lg-2">
                    <label class="form-label">Payment Status</label>
                    <select class="form-select" id="payment_status">
                        <option value="">All</option>
                        <option value="paid">Paid</option>
                        <option value="due">Due</option>
                        <option value="partial">Partial</option>
                    </select>
                </div>
        
            </div>
        </div>
		<div class="card-body p-0" id="data">										
			
		</div>
	</div>
	<!-- /product list -->
</div>
@endsection

@push('js')

@php
    
    $url=route("purchases.index");
    
    
@endphp

<script type="text/javascript">
  $(document).ready(function () {
    
    $(document).on('bookingRangeChanged', function (e, data) {
        
        
        getData(1);
    
    });
    
    
    $('#search').change(function(){
        getData();
    });

    $('#search_btn').click(function(){
        getData();
    });

    $('#type_id, #shipping_status, #payment_status').change(function(){
        getData();
    });
    
    
    $(document).on('click', ".pagination a", function(e) {
        e.preventDefault();

        $('li').removeClass('active');
        $(this).parent('li').addClass('active');

        var page = $(this).attr('href').split('page=')[1];
        getData(page);
    });
  
    
    function getData(page=1){
       let date=$('.bookingrange').val();
        
        let q=$('#search').val();
        
        let shipping_status=$('#shipping_status').val();
        let payment_status=$('#payment_status').val();
        let is_pos=1;
        let contact_id='{{ $contact->id}}';
    
        $('#data').html('');
        $.ajax({
            url: '{{ $url}}',
            type: 'GET',
            data:{q,date,shipping_status, payment_status,contact_id,page},
            dataType: 'html',
            success: function(data) {
                $('#data').html(data);
            }
        });
    }
  });
</script>
@endpush