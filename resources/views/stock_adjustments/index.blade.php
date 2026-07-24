@extends('layouts.app')
@section('content')
<div class="content">
	<div class="page-header">
		<div class="add-item d-flex">
			<div class="page-title">
				<h4 class="fw-bold">Stock Adjustment</h4>
				<h6>Manage your Stock Adjustment</h6>
			</div>
		</div>
		<!--<ul class="table-top-head">-->
		<!--	<li>-->
		<!--		<a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img src="assets/img/icons/pdf.svg" alt="img"></a>-->
		<!--	</li>-->
		<!--	<li>-->
		<!--		<a data-bs-toggle="tooltip" data-bs-placement="top" title="Excel"><img src="assets/img/icons/excel.svg" alt="img"></a>-->
		<!--	</li>-->
		<!--	<li>-->
		<!--		<a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>-->
		<!--	</li>-->
		<!--	<li>-->
		<!--		<a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>-->
		<!--	</li>-->
		<!--</ul>-->
		@can('stock_adjustments.create')
		<div class="page-btn">
			<a href="{{ route('stock_adjustments.create')}}" class="btn btn-primary btn_modal"><i class="ti ti-circle-plus me-1"></i>Add Stock Adjustment</a>
		</div>
		@endcan
	</div>
	<!-- /product list -->
	
	<div class="card">
		<div class="card-body pb-1">
			<div class="row align-items-end">
				<div class="col-lg-12">
					<div class="row">
					    
					    <div class="col-12 col-md-6 col-lg-6">
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
            
						<div class="col-12 col-md-3 col-lg-3">
							<div class="date">
								<label class="form-label">Choose Date</label>
								<div class="input-icon-start position-relative">
									<input type="text" class="form-control bookingrange" placeholder="dd/mm/yyyy - dd/mm/yyyy">
									<span class="input-icon-left">
										<i class="ti ti-calendar"></i>
									</span>
								</div>
							</div>
						</div>
						
						<div class="col-12 col-md-3 col-lg-3">
							<div class="location">
								<label class="form-label"> Location</label>
								<select class="select" id="location_id">
									<option value="">All</option>
								    @foreach($locations as $location)
									<option value="{{ $location->id}}"> {{ $location->name}}</option>
									@endforeach
								</select>
							</div>
						</div>
						
					</div>
				</div>
				
			</div>
		</div>
	</div>
	
	<div class="card">
		<div class="card-body p-0" id="data">										
			
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

    $('#location_id').change(function(){
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
        let date=$('.bookingrange').val();

        let location_id=$('#location_id').val();
    
        $('#member_data').html('');
        $.ajax({
            url: '{{ route("stock_adjustments.index")}}?page='+page,
            type: 'GET',
            data:{q,date,location_id},
            dataType: 'html',
            success: function(data) {
                $('#data').html(data);
            }
        });
    }
  });
</script>


<script type="text/javascript">


    //

    
    
    var product_url = "{{ route('getAdjustmentProduct') }}";

    $('#common_modal').on('shown.bs.modal', function () {
    	const products=[];
    	$('.datetimepicker').datepicker({
	      dateFormat: 'yy-mm-dd',  // format you want the selected date to appear in
	      timeFormat: 'HH:mm:ss',
	      changeMonth: true,
	      changeYear: true,
	      showButtonPanel: true
	    });



	    $(document).find("#purchases_product").autocomplete({
	        selectFirst: true, //here
	        minLength: 2,
	        source: function( request, response ) {
	          $.ajax({
	            url: product_url,
	            type: 'GET',
	            dataType: "json",
	            data: {search: request.term, location_id:$(document).find("#location_id_from").val(), adjustment_type:$(document).find("#adjustment_type").val()},
	            success: function( data ) {
	                
	                if (data.length ==0) {
	                    toastr.error('Product Not Found');
	                }
	                else if (data.length ==1) {

	                    if(products.indexOf(data[0].id) ==-1){
	                        productEntry(data[0]);
	                        products.push(data[0].id);
	                    }
	                    
	                    $('#purchases_product').val('');


	                    
	                }else if (data.length >1) {
	                    response(data);
	                }
	            }
	          });
	        },
	        select: function (event, ui) {
	           
	           if(products.indexOf(ui.item.id) ==-1){
	                productEntry(ui.item);
	                products.push(ui.item.id);
	            }

	           $('#purchases_product').val('');
	           return false;
	        }
	    });

	});
    function productEntry(item){
        let location_id=$(document).find("#location_id_from").val();
        
        let adjustment_type=$(document).find("#adjustment_type").val();
        
        $.ajax({
            url: '{{ route("adjustmentProductEntry")}}',
            type: 'GET',
            dataType: "json",
            data: {id:item.id, adjustment_type, location_id},
            success: function( res ) {
                    
                if (res.html) {
                    $('#purchase_product tbody').append(res.html);
                    calculateSum();
                }
                
            }
        });
    }


    $(document).on('click',".remove",function(e) {
        var whichtr = $(this).closest("tr");
        whichtr.remove();  
        calculateSum();    
    });


    $(document).on('blur',".quantity, .unit_price",function(e) {

        calculateSum();    
    });


    function calculateSum() {


        let sub_total=0;

        let tblrows = $("#purchase_product tbody tr");
        tblrows.each(function (index) {
            let tblrow = $(this);

            let product_qty=Number(tblrow.find('td input.quantity').val());
            let product_amount=Number(tblrow.find('td input.unit_price').val());

            let product_row_total=(product_qty *product_amount);
            tblrow.find('td.row_total').text(product_row_total.toFixed(2));
            sub_total+=product_row_total;
         
            
        });

        $('input.final_amount').val(sub_total.toFixed(2));
    }
  
</script>

@endpush


