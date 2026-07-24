@extends('layouts.app')
@section('content')
<div class="content">
	<div class="page-header">
		<div class="add-item d-flex">
			<div class="page-title">
				<h4 class="fw-bold">Category</h4>
				<h6>Manage your Categories</h6>
			</div>
		</div>
         @can('categories.create')
		<div class="page-btn">
			<a href="{{ route('categories.create')}}" class="btn btn-primary btn_modal"><i class="ti ti-circle-plus me-1"></i>Add Category</a>
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
                            <option value="">Sort</option>
                            <option value="top">Top</option>
                            <option value="menu">Menu</option>
                        <option value="home">Bottom</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>


    	<div class="row m-1">
            <div class="col-12">
                <div class="d-flex flex-wrap gap-2 p-2">
        
                    <a class="btn btn-sm btn-info show_update"
                       href="{{ route('categoryStatus') }}?is_top=1">
                        Top Active
                    </a>
        
                    <a class="btn btn-sm btn-danger show_update"
                       href="{{ route('categoryStatus') }}?is_top=0">
                        Top De-Active
                    </a>
        
                    <a class="btn btn-sm btn-info show_update"
                       href="{{ route('categoryStatus') }}?is_menu=1">
                        Active (Menu Bar)
                    </a>
        
                    <a class="btn btn-sm btn-danger show_update"
                       href="{{ route('categoryStatus') }}?is_menu=0">
                        De-active (Menu Bar)
                    </a>
        
                    <a class="btn btn-sm btn-info show_update"
                       href="{{ route('categoryStatus') }}?is_home=1">
                        Active (Bottom)
                    </a>
        
                    <a class="btn btn-sm btn-danger show_update"
                       href="{{ route('categoryStatus') }}?is_home=0">
                        De-active (Bottom)
                    </a>
        
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
    
    $(document).on('click', 'a.show_update', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
    
        var product = $('input.checkbox:checked').map(function(){
          return $(this).val();
        });
        var product_ids=product.get();
        
        if(product_ids.length ==0){
            toastr.error('Please Select A Category First !');
            return ;
        }
        
        $.ajax({
           type:'GET',
           url:url,
           data:{product_ids},
           success:function(res){
               if(res.status==true){
                toastr.success(res.msg);
                getData();
                
            }else if(res.status==false){
                toastr.error(res.msg);
            }
           }
        });
    
    });

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
    
        $('#data').html('');
        $.ajax({
            url: '{{ route("categories.index")}}?page='+page,
            type: 'GET',
            data:{q,status,sort},
            dataType: 'html',
            success: function(data) {
                $('#data').html(data);
            }
        });
    }
  });
</script>
@endpush