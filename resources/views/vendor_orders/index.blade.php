@extends('layouts.app')
@section('content')
<div class="content">
	<div class="page-header">
		<div class="add-item d-flex">
			<div class="page-title">
				<h4>Orders</h4>
				<h6>Manage Your orders</h6>
			</div>
		</div>
	</div>
	
	<!-- /product list -->
	
	<div class="card">
		<div class="card-body pb-1">
			<form action="https://dreamspos.dreamstechnologies.com/html/template/expense-report.html">
				<div class="row align-items-end">
					<div class="col-lg-12">
						<div class="row">
						    
						    <div class="col-12 col-md-3">
						        <div class="mb-3">
									<label class="form-label">Search</label>
                                    <div class="search-set w-100">
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="ti ti-search"></i>
                                            </span>
                                            <input type="text" id="search" class="form-control" placeholder="Search...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                
							<div class="col-12 col-md-3">
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
							<div class="col-12 col-md-3">
								<div class="mb-3">
									<label class="form-label"> Shipping Status</label>
									<select class="select" id="shipping_status">
										<option value="">All</option>
										
										@foreach(getStatusList() as $i=> $status)
										<option value="{{ $i}}">{{ $status }}</option>
										@endforeach
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
    							<div class="mb-3">
    								<label class="form-label"> Payment Status</label>
    								<select class="select" id="payment_status">
    									<option value="">All</option>
    								
    									<option value="paid"> Paid</option>
    									<option value="due"> Due</option>
    									<option value="partial"> Partial</option>
    								</select>
    							</div>
    						</div>
						
							
						</div>
					</div>
					
				</div>
			</form>
		</div>
	</div>
	
	<div class="card">

		<div class="card-body p-0 sell_data">
			
		</div>
	</div>
	<!-- /product list -->
</div>

@endsection

@push('js')


<script type="text/javascript">
  $(document).ready(function () {
    
    $(document).on('bookingRangeChanged', function (e, data) {
        
        
        getData(1);
    
    });
    
    $('#search').keyup(function(){
        getData();
    });

    $('#search_btn').click(function(){
        getData();
    });

    $('#payment_status').change(function(){
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
        let date=$('.bookingrange').val();
        let q=$('#search').val();
        let shipping_status=$('#shipping_status').val();
        let payment_status=$('#payment_status').val();
    
        $('.sell_data').html('');
        $.ajax({
            url: '{{ route("vendor_orders.index")}}?page='+page,
            type: 'GET',
            data:{q,date,shipping_status,payment_status},
            dataType: 'html',
            success: function(data) {
                $('.sell_data').html(data);
            }
        });
    }
  });
</script>
@endpush