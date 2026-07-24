@extends('layouts.app')
@section('content')
<div class="content">
	<div class="page-header">
		<div class="add-item d-flex">
			<div class="page-title">
				<h4 class="fw-bold">Stock Transfer</h4>
				<h6>Manage your Stock Transfer</h6>
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
		@can('stock_transfers.create')
		<div class="page-btn">
			<a href="{{ route('stock_transfers.create')}}" class="btn btn-primary btn_modal"><i class="ti ti-circle-plus me-1"></i>Add Stock Transfer</a>
		</div>
		@endcan
	</div>
	<!-- /product list -->
	
	<div class="card">
		<div class="card-body py-3">
			<div class="row align-items-end">
				<div class="col-lg-12">
					<div class="row g-3">
                        <!-- Search -->
                        <div class="col-12 col-md-6 col-lg-3">
                            <div>
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
                        
                        <!-- Date Range -->
                        <div class="col-12 col-md-6 col-lg-3">
                            <div>
                              <label class="form-label">Choose Date</label>
                              <div class="input-icon-start position-relative">
                                <input type="text" class="form-control bookingrange" placeholder="dd/mm/yyyy - dd/mm/yyyy">
                                <span class="input-icon-left">
                                  <i class="ti ti-calendar"></i>
                                </span>
                              </div>
                            </div>
                        </div>
                        
                        <!-- Location From -->
                        <div class="col-12 col-md-6 col-lg-2">
                            <div>
                              <label class="form-label">Location From</label>
                              <select class="select w-100" id="location_id">
                                <option value="">All</option>
                                @foreach($locations as $location)
                                  <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                              </select>
                            </div>
                        </div>
                        
                        <!-- Location To -->
                        <div class="col-12 col-md-6 col-lg-2">
                            <div>
                              <label class="form-label">Location To</label>
                              <select class="select w-100" id="location_id_to">
                                <option value="">All</option>
                                @foreach($locations as $location)
                                  <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                              </select>
                            </div>
                        </div>
                        
                        <!-- Payment Status -->
                        <div class="col-12 col-md-6 col-lg-2">
                            <div>
                              <label class="form-label">Payment Status</label>
                              <select class="select w-100" id="payment_status">
                                <option value="">All</option>
                                <option value="paid">Paid</option>
                                <option value="due">Due</option>
                                <option value="partial">Partial</option>
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

    $('#payment_status, #location_id_to, #location_id').change(function(){
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
    
        let payment_status=$('#payment_status').val();
        let location_id_to=$('#location_id_to').val();
        let location_id=$('#location_id').val();
    
        $('#data').html('');
        $.ajax({
            url: '{{ route("stock_transfers.index")}}?page='+page,
            type: 'GET',
            data:{q,date,payment_status,location_id_to,location_id},
            dataType: 'html',
            success: function(data) {
                $('#data').html(data);
            }
        });
    }
  });
</script>


<script type="text/javascript">

    var product_url = "{{ route('getTransferProduct') }}";
    
    console.log('ok');

    $('#common_modal').on('shown.bs.modal', function () {
    	const products=[];
    	$('.datetimepicker').datepicker({
	      dateFormat: 'yy-mm-dd',  // format you want the selected date to appear in
	      timeFormat: 'HH:mm:ss',
	      changeMonth: true,
	      changeYear: true,
	      showButtonPanel: true
	    });


        $("#purchases_product" ).autocomplete({
            selectFirst: true, //here
            minLength: 2,
            source: function( request, response ) {
              $.ajax({
                url: product_url,
                type: 'GET',
                dataType: "json",
                data: {search: request.term, location_id:$(document).find("#location_id_from").val()},
                success: function( data ) {
                    
                    if (data.length ==0) {
                        toastr.error('Product Not Found');
                    }
                    else if (data.length ==1) {
    
                        if(products.indexOf(data[0].id) ==-1){
                            productEntry(data[0].id);
                            products.push(data[0].id);
                        }
                        
                        $('#product_search').val('');
    
    
                        
                    }else if (data.length >1) {
                        response(data);
                    }
                }
              });
            },
            select: function (event, ui) {
               
               if(products.indexOf(ui.item.id) ==-1){
                    productEntry(ui.item.id);
                    products.push(ui.item.id);
                }
    
               $('#product_search').val('');
               return false;
            }
        });
    

	});
	
	
	

    function productEntry(variation_id){
        let location_id=$("#location_id_from").val();
        $.ajax({
            url: '{{ route("transferProductEntry")}}',
            type: 'GET',
            dataType: "json",
            data: {variation_id,location_id},
            success: function( res ) {
                    
                if (res.html) {
                    $('#purchase_product tbody').append(res.html);
                    calculateSum();
                }else{
                    swal(res.msg);
                }
                
            }
        });
    }
    


    $(document).on('click',".remove",function(e) {
        var whichtr = $(this).closest("tr");
        whichtr.remove();  
        calculateSum();    
    });

    $(document).on('blur',".quantity",function(e) {
        let inputqty=$(this);
        let qty=Number(inputqty.val());
        let max_qty=Number(inputqty.attr('max'));
        
        if (max_qty <qty) {
            swal('Stock Is Over');
            inputqty.val(max_qty);
        }
        

            
        calculateSum();    
    });
    
    
    $(document).on('blur',".unit_price",function(e) {
        
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


