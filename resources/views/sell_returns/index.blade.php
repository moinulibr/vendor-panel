@extends('layouts.app')
@section('content')
<div class="content">
	<div class="page-header">
		<div class="add-item d-flex">
			<div class="page-title">
				<h4 class="fw-bold">Sell Return</h4>
				<h6>Manage your Sell Return</h6>
			</div>
		</div>
		
	</div>
	<!-- /product list -->
	

	
	<div class="card">
		<div class="card-header">
            <div class="d-flex align-items-end flex-wrap gap-3">
            
                <!-- Search (flex-grow) -->
                <div class="search-wrapper flex-grow-1">
                  <div class="form-group mb-0">
                    <label class="form-label">Search</label>
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
            
                <!-- Date Range -->
                <div class="date-wrapper">
                  <div class="form-group mb-0">
                    <label class="form-label">Choose Date</label>
                    <div class="input-group">
                      <span class="input-group-text bg-white">
                        <i class="ti ti-calendar"></i>
                      </span>
                      <input
                        type="text"
                        class="form-control bookingrange"
                        placeholder="dd/mm/yyyy - dd/mm/yyyy"
                      >
                    </div>
                  </div>
                </div>
            
                <!-- Supplier -->
                <div class="supplier-wrapper">
                  <div class="form-group mb-0">
                    <label class="form-label">Customer</label>
                    <select class="form-control select2" id="contact_id">
                      <option value="">All</option>
                      @foreach($contacts as $contact)
                        <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                      @endforeach
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
    
    $(document).on('bookingRangeChanged', function (e, data) {
        
        
        getData(1);
    
    });
    
    $('#search').keyup(function(){
        getData();
    });

    $('#search_btn').click(function(){
        getData();
    });

    $('#contact_id').change(function(){
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

        let contact_id=$('#contact_id').val();
    
        $('#member_data').html('');
        $.ajax({
            url: '{{ route("sell_returns.index")}}?page='+page,
            type: 'GET',
            data:{q,date,contact_id},
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

    
    
    var product_url = "{{ route('getPurchaseProduct') }}";

    $('#common_modal').on('shown.bs.modal', function () {
    	const products=[];
    	$('.datetimepicker').datepicker({
	      dateFormat: 'yy-mm-dd',  // format you want the selected date to appear in
	      timeFormat: 'HH:mm:ss',
	      changeMonth: true,
	      changeYear: true,
	      showButtonPanel: true
	    });

        
        $(document).on('change', '.vendor_id',function () {
            var vendor_id = $(this).val();
        
            $('.contact_id').empty().append('<option value="">Loading...</option>');
        
            $.ajax({
                url: '{{ route("purchase.getSupplier")}}',
                type: 'GET',
                data:{vendor_id},
                success: function (data) {
                    $('.contact_id').empty().append('<option value="">Select Supplier</option>');
                    $.each(data, function (key, value) {
                        $('.contact_id').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
                }
            });
        });
        

	    $(document).find("#purchases_product").autocomplete({
	        selectFirst: true, //here
	        minLength: 2,
	        appendTo: '#common_modal',
	        source: function( request, response ) {
	          $.ajax({
	            url: product_url,
	            type: 'GET',
	            dataType: "json",
	            data: {search: request.term},
	            success: function( data ) {
	                
	                if (data.length ==0) {
	                    toastr.error('Product Not Found Or Manage Stock Disable');
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

        $.ajax({
            url: '{{ route("purchaseProductEntry")}}',
            type: 'GET',
            dataType: "json",
            data: {id:item.id},
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


