@extends('layouts.app')
@section('content')
<div class="content">
	<div class="page-header">
		<div class="add-item d-flex">
			<div class="page-title">
				<h4 class="fw-bold">{{ ucfirst($type)}} Report</h4>
			</div>
		</div>
        
	</div>
	<!-- /product list -->
	
	<div class="card">
		<div class="card-body pb-1">
			<div class="row align-items-end">
				<div class="col-12">
					<div class="row">
					    
					    <div class="col-12 col-md-6 col-lg-4">
					        <div class="mb-3">
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
                        </div>
            
						<div class="col-12 col-md-6 col-lg-4">
							<div class="mb-3">
								<label class="form-label">Choose Date</label>
								<div class="input-icon-start position-relative">
									<input type="text" class="form-control bookingrange" placeholder="dd/mm/yyyy - dd/mm/yyyy">
									<span class="input-icon-left">
										<i class="ti ti-calendar"></i>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="card">
    		
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
  
    function getData(page=1){
        let q=$('#search').val();
        let status=$('#status').val();
        let sort = $('#sort').val();
        let type='{{$type}}';
    
        $('#customer_data').html('');
        $.ajax({
            url: '{{ route("reports.getCustomerDue")}}',
            type: 'GET',
            data:{q,status,sort,page, type},
            dataType: 'html',
            success: function(res) {
                
                console.log(res);
                
                $('#customer_data').html(res);
            }
        });
    }
    
    

    
});
</script>
@endpush