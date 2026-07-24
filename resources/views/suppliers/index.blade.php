@extends('layouts.app')
@section('content')
<div class="content">
	<div class="page-header">
		<div class="add-item d-flex">
			<div class="page-title">
				<h4 class="fw-bold">Supplier</h4>
				<h6>Manage your Supplier</h6>
			</div>
		</div>
        @can('suppliers.create')
		<div class="page-btn">
			<a href="{{ route('contacts.create')}}?type=supplier" class="btn btn-primary btn_modal"><i class="ti ti-circle-plus me-1"></i>Add Supplier</a>
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
                      <select class="form-control w-100 vendor_id select2" id="vendor_id">
                        <option value="">All Vendor</option>
                        @foreach($users as $user)
                          <option value="{{ $user->id }}" >
                            {{ $user->name }}
                          </option>
                        @endforeach
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
		<div class="card-body p-0" id="data">										
			
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
    
    $('#status, #vendor_id, #sort').change(function(){
        getData();
    });
    
    $(document).on('click', ".pagination a", function(e) {
        e.preventDefault();

        $('li').removeClass('active');
        $(this).parent('li').addClass('active');

        var page = $(this).attr('href').split('page=')[1];
        getData(page);
    });
  
    function getData(page=null){
        let q=$('#search').val();
        let status=$('#status').val();
        let sort = $('#sort').val();
        let vendor_id = $('#vendor_id').val();
    
        $('#member_data').html('');
        $.ajax({
            url: '{{ route("suppliers.index")}}?page='+page+'&type=supplier',
            type: 'GET',
            data:{q,status,sort,vendor_id},
            dataType: 'html',
            success: function(data) {
                $('#data').html(data);
            }
        });
    }
    
    
    $('#common_modal').on('shown.bs.modal', function () {

        // =========================
        // DISTRICT CHANGE → LOAD UPAZILA
        // =========================
        $(document).off('change', '.district_id').on('change', '.district_id', function () {
            let district_id = $(this).val();
            loadUpazila(district_id, '.upazila_id', null);
        });
    
        // =========================
        // EDIT MODE: AUTO LOAD UPAZILA
        // =========================
        let editDistrict = $('.district_id').val();
        let editUpazila  = $('.upazila_id').data('selected'); // from blade
    
        if (editDistrict) {
            loadUpazila(editDistrict, '.upazila_id', editUpazila);
        }
    
        // =========================
        // COMMON FUNCTION
        // =========================
        function loadUpazila(district_id, upazilaSelector, selectedUpazila = null) {
    
            if (!district_id) return;
    
            $(upazilaSelector).html('<option value="">Loading...</option>');
    
            $.ajax({
                url: '{{ route("getUpazila") }}',
                type: 'GET',
                data: { district_id },
                success: function (data) {
    
                    $(upazilaSelector).html('<option value="">Select Thana</option>');
    
                    $.each(data, function (key, value) {
                        let selected = (selectedUpazila == value.id) ? 'selected' : '';
                        $(upazilaSelector).append(
                            `<option value="${value.id}" ${selected}>${value.name}</option>`
                        );
                    });
                }
            });
        }
    
    });

  });
</script>
@endpush