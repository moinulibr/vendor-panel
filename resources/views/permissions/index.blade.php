@extends('layouts.app')
@section('content')
<div class="content">
	<div class="page-header">
		<div class="add-item d-flex">
			<div class="page-title">
				<h4 class="fw-bold">Permission</h4>
				<h6>Manage your Permission</h6>
			</div>
		</div>
		@can('permissions.create')
		<div class="page-btn">
			<a href="{{ route('permissions.create')}}" class="btn btn-primary btn_modal"><i class="ti ti-circle-plus me-1"></i>Add Permission</a>
		</div>
		@endcan
	</div>
	<!-- /product list -->
	<div class="card">
		<div class="card-header">
    		<div class="d-flex align-items-center flex-wrap gap-3 col-12">
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
    
    $(document).on('click', ".pagination a", function(e) {
        e.preventDefault();

        $('li').removeClass('active');
        $(this).parent('li').addClass('active');

        var page = $(this).attr('href').split('page=')[1];
        getData(page);
    });
  
    function getData(page=null){
        let q=$('#search').val();
    
        $('#member_data').html('');
        $.ajax({
            url: '{{ route("permissions.index")}}?page='+page,
            type: 'GET',
            data:{q},
            dataType: 'html',
            success: function(data) {
                $('#data').html(data);
            }
        });
    }
  });
</script>
@endpush