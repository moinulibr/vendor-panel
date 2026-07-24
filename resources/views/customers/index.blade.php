@extends('layouts.app')
@section('content')
<div class="content">
	<div class="page-header">
		<div class="add-item d-flex">
			<div class="page-title">
				<h4 class="fw-bold">Customer</h4>
				<h6>Manage your Customer</h6>
			</div>
		</div>
        @can('customers.create')
		<div class="page-btn">
			<a href="{{ route('customers.create')}}" class="btn btn-primary btn_modal"><i class="ti ti-circle-plus me-1"></i>Add Customer</a>
		</div>
		@endcan
	</div>
	<!-- /product list -->
	<div class="card">
		<div class="card-header">
    		<div class="d-flex align-items-center flex-wrap gap-3">
        
                <!-- Search (70%) -->
                <div class="flex-grow-1 search-wrapper">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="ti ti-search"></i>
                        </span>
                        <input
                            type="text"
                            id="search"
                            class="form-control"
                            placeholder="Search..."
                        >
                    </div>
                </div>
        
                <!-- Right Section (30%) -->
                <div class="filter-wrapper d-flex justify-content-end gap-2">
                    <div class="dropdown">
                        <select class="form-control" id="add_from">
                            <option value=""> All Adding List </option>
                            <option value="1"> Ecommerce Register </option>
                            <option value="2"> Socialite Add  </option>
                            <option value="3"> Admin Panel </option>
                            <option value="4"> SR Panel </option>
                            
                        </select>
                    </div>
                    
                    
                    <div class="dropdown">
                        <select class="form-control" id="status">
                            <option value=""> All Status </option>
                            <option value="1"> Active </option>
                            <option value="0"> Inactive </option>
                        </select>
                    </div>
        
                    <div class="dropdown">
                        <select class="form-control" id="sort">
                            <option value="latest">Sort : Latest</option>
                            <option value="asc">Sort : Ascending</option>
                            <option value="desc">Sort : Descending</option>
                        </select>
                    </div>
                </div>
            </div>
		</div>	
		<div class="card-body p-0" id="customer_data">										
			
		</div>
	</div>
	<!-- /product list -->
</div>
@endsection

@push('js')


<script type="text/javascript">
  $(document).ready(function () {
      
    getData();
    $('#search').keyup(function(){
        getData();
    });
    
    $('#status, #sort, #add_from').change(function(){
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
        let q=$('#search').val();
        let status=$('#status').val();
        let sort = $('#sort').val();
        let add_from = $('#add_from').val();
    
        $('#customer_data').html('');
        $.ajax({
            url: '{{ route("customers.index")}}',
            type: 'GET',
            data:{q,status,sort,page, add_from},
            dataType: 'html',
            success: function(res) {
                
                console.log(res);
                
                $('#customer_data').html(res);
            }
        });
    }
    
    
    $('#common_modal').on('shown.bs.modal', function () {
        
        $('#same_shipping').on('change', function () {
            $('.shipping_form').toggle(!this.checked);
        });
            
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
        
        $(document).on('change', '#s_district',function () {
            var district_id = $(this).val();
        
            $('#s_upazila').empty().append('<option value="">Loading...</option>');
        
            $.ajax({
                url: '{{ route("getUpazila")}}',
                type: 'GET',
                data:{district_id},
                success: function (data) {
                    $('#s_upazila').empty().append('<option value="">Select Thana</option>');
                    $.each(data, function (key, value) {
                        $('#s_upazila').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
                }
            });
        });
    });
    
});
</script>
@endpush