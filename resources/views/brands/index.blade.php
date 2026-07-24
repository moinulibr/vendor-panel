@extends('layouts.app')
@section('content')
<div class="content">
	<div class="page-header">
		<div class="add-item d-flex">
			<div class="page-title">
				<h4 class="fw-bold">Brand</h4>
				<h6>Manage your brands</h6>
			</div>
		</div>
        @can('brands.create')
		<div class="page-btn">
			<a href="{{ route('brands.create')}}" class="btn btn-primary btn_modal"><i class="ti ti-circle-plus me-1"></i>Add Brand</a>
		</div>
		@endcan
	</div>
	
	<!-- /product list -->
	<div class="card">
		<div class="card-header">
            <div class="d-flex align-items-center flex-wrap gap-3">
        
                <!-- Search (70%) -->
                <div class="flex-grow-1 search-wrapper">
                    <div class="search-set w-100">
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
                </div>
            </div>
        </div>


		<div class="row m-1">
            <div class="col-12">
                <div class="d-flex flex-wrap gap-2 p-2">
        
                    <a class="btn btn-sm btn-info show_update"
                       href="{{ route('brandStatus') }}?is_top=1">
                        Top Active
                    </a>
        
                    <a class="btn btn-sm btn-danger show_update"
                       href="{{ route('brandStatus') }}?is_top=0">
                        Top De-Active
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
            toastr.error('Please Select A Brand First !');
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

        $('#member_data').html('');
        $.ajax({
            url: '{{ route("brands.index")}}?page='+page,
            type: 'GET',
            data:{q,status},
            dataType: 'html',
            success: function(data) {
                $('#data').html(data);
            }
        });
    }
  });
</script>
@endpush