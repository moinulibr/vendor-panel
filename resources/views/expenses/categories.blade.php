@extends('layouts.app')
@section('content')
<div class="content">
	<div class="page-header">
		<div class="add-item d-flex">
			<div class="page-title">
				<h4 class="fw-bold">Expense Category</h4>
				<h6>Manage your Expense Category</h6>
			</div>
		</div>
        
        @can('expenses.create')
		<div class="page-btn">
			<a href="{{ route('transaction_categories.create',['type'=>'expense'])}}" class="btn btn-primary btn_modal"><i class="ti ti-circle-plus me-1"></i>Add Category</a>
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
    
    $('#status').change(function(){
        getData();
    });
    
    $('#sort').change(function(){
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
    
        $('#member_data').html('');
        $.ajax({
            url: '{{ route("transaction_categories.index")}}?page='+page,
            type: 'GET',
            data:{q,status,sort,type:'expense'},
            dataType: 'html',
            success: function(data) {
                $('#data').html(data);
            }
        });
    }
  });
</script>
@endpush