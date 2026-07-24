@extends('layouts.app')

@push('css')

<style>
    /* 1. Reset for Printing */
    @media print {
        body { margin: 0; padding: 0; }
        .print-area { width: 100%; }
        @page { margin: 0; }
    }

    /* 2. The Sticker Container */
    .barcode-sticker {
        width: 40mm;        /* Set your exact label width */
        height: 30mm;       /* Set your exact label height */
        display: inline-block;
        padding: 1mm;       /* Small gap so border doesn't touch paper edge */
        box-sizing: border-box;
        vertical-align: top;
        page-break-inside: avoid;
    }

    /* 3. The Border and Internal Alignment */
    .border-wrapper {
        border: 1px solid #000;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between; /* Spreads content evenly */
        align-items: center;
        padding: 2px;
        box-sizing: border-box;
        text-align: center;
    }

    /* 4. Text Styling */
    .biz-name { font-weight: bold; font-size: 10px; line-height: 1; }
    .prod-name { font-size: 10px; margin: 1px 0; line-height: 1; }
    .price-tag { font-weight: 800; font-size: 12px; margin-top: 2px; }
    
    .barcode-wrapper {
        width: 100%;
        margin-top: auto;   /* Pushes barcode to bottom */
    }
    .barcode-wrapper img {
        max-width: 100%;
        height: 25px;       /* Fixed height for barcode consistency */
        display: block;
        margin: 0 auto;
    }
    .sku-text {
        font-size: 9px;
        letter-spacing: 1px;
    }
</style>

@endpush
@section('content')
<div class="content">
	<div class="page-header no-print">
		<div class="add-item d-flex">
			<div class="page-title">
				<h4 class="fw-bold">Print Barcode</h4>
				<h6>Manage your barcodes</h6>
			</div>
		</div>
		<div class="d-flex align-items-center">
			<ul class="table-top-head">
				<li>
					<a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
				</li>
				<li>
					<a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
				</li>
			</ul>
		</div>
	</div>
    <div class="barcode-content-list no-print">
        <form>
            
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3 search-form seacrh-barcode-item">
                        <div class="search-form">
                            <label class="form-label">Product<span class="text-danger ms-1">*</span></label>
							<div class="position-relative">
								<input type="text" class="form-control" id="barcode_product" placeholder="Search Product by Code">
								<i data-feather="search" class="feather-search"></i>
							</div>
                        </div>
                    </div>                                                             
                                                 
                </div>
            </div>
        </form>  
        
        <form action="{{ route('barcodes.store')}}" method="post" id="barcode_form">
            @csrf
        
        <div class="col-lg-12">
            <div class="p-3 bg-light rounded border mb-3">
                <div class="table-responsive rounded border">
                    <table class="table" id="barcode_table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Qty</th>
                                <th class="text-center no-sort bg-secondary-transparent"></th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>

        <div class="paper-search-size">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    
                        <label class="form-label">Paper Size<span class="text-danger ms-1">*</span></label>
                        <select class="select">
                            <option>Select</option>
                            <option>A3</option>
                            <option>A4</option>
                            <option>A5</option>
                            <option>A6</option>
                        </select>
                </div>
                <div class="col-lg-6 pt-3">
                    <div class="row">
						<div class="col-sm-4">
							<div class="search-toggle-list">
								<p>Show Store Name</p>
								<div class="m-0">
									<div class="status-toggle modal-status d-flex justify-content-between align-items-center">
										<input type="checkbox" id="user7" class="check" checked value="1" name="show_business">
										<label for="user7" class="checktoggle mb-0"></label>
									</div>
								</div>
							</div> 
						</div>    
                            
						<div class="col-sm-4">
							<div class="search-toggle-list">
								<p>Show Product Name</p>
								<div class="m-0">
									<div class="status-toggle modal-status d-flex justify-content-between align-items-center">
										<input type="checkbox" id="user8" class="check" checked value="1" name="show_name">
										<label for="user8" class="checktoggle mb-0"></label>
									</div>
								</div>
							</div> 
						</div>


						<div class="col-sm-4">
							<div class="search-toggle-list">
								<p>Show Price</p>
								<div class="m-0">
									<div class="status-toggle modal-status d-flex justify-content-between align-items-center">
										<input type="checkbox" id="user9" class="check" checked value="1" name="show_price">
										<label for="user9" class="checktoggle mb-0">	</label>
									</div>
								</div>
							</div> 
						</div> 
                    </div>                                                               
                </div>
            </div>
        </div> 

        <div class="search-barcode-button">                            
			<button type="submit" class="btn btn-primary me-2 mt-0">
                <span><i class="fas fa-eye me-1"></i></span>Generate Barcode
			</button>
            <a href="{{ route('barcodes.index')}}" class="btn btn-cancel btn-secondary fs-13 me-2">
                <span><i class="fas fa-power-off me-1"></i></span>Reset Barcode
			</a>
			
			<a href="javascript:void(0);" class="btn btn-cancel btn-danger close-btn" id="print-btn">
                <span><i class="fas fa-print me-1"></i></span>Print Barcode
			</a>
			
        </div>
        
        </form>
        
    </div>  
    
    <div class="col-12 barcode-product">
        
    </div>
</div>
@endsection

@push('js')


<script type="text/javascript">
    $('#print-btn').on('click', function() {
        // 1. Get the HTML content of the barcode div
        var printContents = $('.barcode-product').html();
        
        // 2. Create a temporary window
        var printWindow = window.open('', '_blank', 'width=600,height=400');
        
        // 3. Write the content and styles to the new window
        printWindow.document.write('<html><head><title>Print Barcode</title>');
        // Include your CSS here so the barcode stays formatted
        printWindow.document.write('<style>body{text-align:center; margin:20px;}</style>'); 
        printWindow.document.write('</head><body>');
        printWindow.document.write(printContents);
        printWindow.document.write('</body></html>');
        
        // 4. Trigger print and close
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    });

    $('#barcode_form').on('submit', function(e) {
        e.preventDefault(); // stop page reload
    
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(), // send all form fields
            success: function(res) {
                $('div.barcode-product').html(res);
            },
            error: function(err) {
                alert('Something went wrong');
            }
        });
    });

    var product_url = "{{ route('barcodes.create') }}";

    $(document).ready(function () {
    	const products=[];
    	

	    $(document).find("#barcode_product").autocomplete({
	        selectFirst: true, //here
	        minLength: 2,
	        source: function( request, response ) {
	          $.ajax({
	            url: product_url,
	            type: 'GET',
	            dataType: "json",
	            data: {search: request.term},
	            success: function( data ) {
	                
	                if (data.length ==0) {
	                    toastr.error('Product Not Found');
	                }
	                else if (data.length ==1) {

	                    if(products.indexOf(data[0].id) ==-1){
	                        productEntry(data[0]);
	                        products.push(data[0].id);
	                    }
	                    
	                    $('#barcode_product').val('');


	                    
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

	           $('#barcode_product').val('');
	           return false;
	        }
	    });

	});
    function productEntry(item){

        $.ajax({
            url: '{{ route("barcodeProductEntry")}}',
            type: 'GET',
            dataType: "json",
            data: {id:item.id},
            success: function( res ) {
                    
                if (res.html) {
                    $('#barcode_table tbody').append(res.html);
                }
                
            }
        });
    }


    $(document).on('click',".remove",function(e) {
        var whichtr = $(this).closest("tr");
        whichtr.remove();     
    });


  
</script>

@endpush