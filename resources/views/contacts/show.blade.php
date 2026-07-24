@extends('layouts.app')
@section('content')


<style>

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

@php
    
    $add_forms= [
        ''=>'',
        '1'=>'Ecommerce Register',
        '2'=>'Socialite Add',
        '3'=>' Admin Panel',
        '4'=>' SR Panel',
    ];
                            
@endphp
<div class="content">
	<div class="card">
        <div class="card-header">
            <div class="row g-3">
                <div class="">
        	        <h5>Customer Info </h5>
        	    </div>
                <!-- Contact Info -->
                <div class="col-12 col-md-6">
                    <div class="p-2 border rounded h-100">
                        <b>Name : </b> <span class="fw-bold mb-1 text-primary">{{ ucfirst($contact->name) }}</span>  <br>
                        <b>Mobile :</b> {{ $contact->mobile }} <br>
                        <b>Email :</b> {{ $contact->email }} <br>
                        <b>Address :</b> {{ $contact->address }} <br>
                        <b>District  :</b>  @if($contact->pdistrict)
                                {{ $contact->pdistrict->name }} - {{ $contact->pupazila->name??''}}<br>
                            @endif
                        <b><br> Customer Add From : {{$add_forms[$contact->add_from]}}</b>
                    </div>
                </div>
    
                <!-- Sell Info -->
                <div class="col-12 col-md-6">
                    <div class="p-2 border rounded h-100">
                        <b>Total Sell :</b> {{ priceFormate($contact->total_sell) }} <br>
                        <b>Total Sell Paid :</b> {{ priceFormate($contact->total_sell_paid ?? 0) }} <br>
                        <b>Total Sell Due :</b> {{ priceFormate($contact->total_sell - ($contact->total_sell_paid ?? 0)) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

	
	@if($contact->type=='customer')
	<div class="card">
    	<div class="row p-3">
    	    <div class="mb-3">
    	        <h5>Shipping Address</h5>
    	    </div>
    	    @foreach($contact->contact_address as $address)

        	    
        	    <div class="col-12 col-md-3 col-lg-3">
                    <div class="p-2 option-box d-flex align-items-start justify-content-between gap-3 position-relative">
                
                        <label class="form-check-label w-100" for="addr{{ $address->id }}">
                            <strong>{{ ucfirst($address->name) }}</strong><br>
                            {{ $address->address }}<br>
                
                            @if($address->district)
                                {{ $address->district->name }} - {{ $address->upazila->name ?? '' }}<br>
                            @endif
                
                            {{ $address->phone }}
                        </label>
                
                        <div class="d-flex flex-column gap-1">
                            <a href="{{ route('contact_address.edit',[$address->id])}}" 
                               class="btn btn-outline-primary btn-sm btn_modal">
                                Edit
                            </a>
                            
                            <a href="{{ route('contact_address.destroy',[$address->id])}}" 
                               class="btn btn-outline-danger btn-sm delete">
                                Delete
                            </a>
                        </div>
                
                    </div>
                </div>

        	@endforeach
    	</div>
	</div>
	@endif
	
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

</div>
@endsection

@push('js')

@php
    $url=route("pos.index");
   
    
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
  
   $('#common_modal').on('shown.bs.modal', function () {
        
            
        $(document).on('change', '.district_id',function () {
            var district_id = $(this).val();
        
            $('.upazila_id').empty().append('<option value="">Loading...</option>');
        
            $.ajax({
                url: '{{ route("getUpazila")}}',
                type: 'GET',
                data:{district_id},
                success: function (data) {
                    $('.upazila_id').empty().append('<option value="">Select Thana</option>');
                    $.each(data, function (key, value) {
                        $('.upazila_id').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
                }
            });
        });
        
    });
    
</script>
@endpush