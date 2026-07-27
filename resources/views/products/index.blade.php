@extends('layouts.app')
@section('content')
@push('css')
    <style>
        .gallery-card {
            transition: all 0.3s ease-in-out;
        }

        .gallery-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .gallery-card:hover img {
            transform: scale(1.05);
        }

        .delete-btn:hover {
            opacity: 1 !important;
            transform: scale(1.1);
        }
    </style>
@endpush
<div class="content">
	<div class="page-header">
		<div class="add-item d-flex">
			<div class="page-title">
				<h4 class="fw-bold">Product</h4>
				<h6>Manage your products</h6>
			</div>
		</div>
		<ul class="table-top-head">
			<!--<li>-->
			<!--	<a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img src="assets/img/icons/pdf.svg" alt="img"></a>-->
			<!--</li>-->
			<!--<li>-->
			<!--	<a data-bs-toggle="tooltip" data-bs-placement="top" title="Excel"><img src="assets/img/icons/excel.svg" alt="img"></a>-->
			<!--</li>-->
			<!--<li>-->
			<!--	<a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>-->
			<!--</li>-->
			<li>
				<a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
			</li>
		</ul>
		<div class="page-btn">
			<a href="{{ route('products.create')}}" class="btn btn-primary btn_modal"><i class="ti ti-circle-plus me-1"></i>Add Product</a>
		</div>
	</div>
	<!-- /product list -->
	<div class="card">
    
        <div class="card-header row align-items-center">
    
            <!-- Search (70%) -->
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    
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
    
                <!-- Vendor Select -->
                <div class="col-3 from-group mb-2">
                    <select class="form-control" id="user_id">
                        <option value=""> All Vendor</option>
                        @foreach($users as $user)
                        <option value="{{$user->id}}"> {{$user->name}} </option>
                        @endforeach
                    </select>
                </div>

                <!-- Category Select -->
                <div class="col-3 from-group mb-2">
                    <select class="form-control" id="category_id">
                        <option value=""> All Category</option>
                        @foreach($cats as $cat)
                        <option value="{{$cat->id}}"> {{$cat->name}} </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-3 from-group mb-2">
                    <select class="form-control" id="brand_id">
                        <option value=""> All Brand</option>
                        @foreach($brands as $brand)
                        <option value="{{$brand->id}}"> {{$brand->name}} </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-3 from-group mb-2">
                    <select class="form-control" id="type">
                        <option value=""> All Type</option>
                        
                        <option value="single"> Single </option>
                        <option value="variable"> Variable </option>
                    </select>
                </div>
                
                <div class="col-3 from-group mb-2">
                    <select class="form-control" id="stock_manage">
                        <option value="">All Stock </option>
                        <option value="1"> Yes </option>
                        <option value="2"> No </option>
                    </select>
                </div>
                
                <div class="col-3 from-group mb-2">
                    <select class="form-control" id="status">
                        <option value=""> All Status </option>
                        <option value="1"> Active </option>
                        <option value="0"> Inactive </option>
                    </select>
                </div>
                
                <div class="col-3 from-group mb-2">
                    <select class="form-control" id="discount_id">
                        <option value=""> All Product Discount </option>
                        @foreach($discounts as $discount)
                        <option value="{{ $discount->id}}">{{ $discount->title}}</option>
                        @endforeach
                    </select>
                </div>
                
                
                <div class="col-3 from-group mb-2">
                    <select class="form-control" id="ecom_status">
                        <option value=""> All Ecommerce Status </option>
                        <option value="is_ecom"> Ecommerce Active </option>
                        <option value="is_feature"> Feature Active </option>
                        <option value="is_reco"> Trending Active </option>
                    </select>
                </div>
            </div>
        </div>
        <!-- Action Buttons -->
        <div class="row m-1">
            <div class="col-12">
                <div class="d-flex flex-wrap gap-2 p-2">
    
                    <a class="btn btn-sm btn-info show_update"
                       href="{{ route('productUpdate')}}?is_ecom=1">
                        Ecom Active
                    </a>
                    <a class="btn btn-sm btn-danger show_update"
                       href="{{ route('productUpdate')}}?is_ecom=0">
                        Ecom De-Active
                    </a>
    
                    <a class="btn btn-sm btn-info show_update"
                       href="{{ route('productUpdate')}}?is_feature=1">
                        Active (Feature)
                    </a>
                    <a class="btn btn-sm btn-danger show_update"
                       href="{{ route('productUpdate')}}?is_feature=0">
                        De-active (Feature)
                    </a>
    
                    <a class="btn btn-sm btn-info show_update"
                       href="{{ route('productUpdate')}}?is_reco=1">
                        Active (Trending)
                    </a>
                    <a class="btn btn-sm btn-danger show_update"
                       href="{{ route('productUpdate')}}?is_reco=0">
                        De-active (Trending)
                    </a>
                     <a href="{{ route('product.created.history')}}" class="btn btn-sm btn-info btn_modal"><i class="ti ti-eye me-1"></i>
                        Product Created History
                    </a>
    
                </div>
            </div>
        </div>
        <div class="card-body p-0" id="data">
            <!-- Dynamic content stays unchanged -->
        </div>
    
    </div>

	<!-- /product list -->
</div>
@endsection

@push('js')
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>

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
            toastr.error('Please Select A Product First !');
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

    $('#search_btn').click(function(){
        getData();
    });

    $('#category_id, #user_id, #stock_manage, #type, #brand_id, #status, #ecom_status, #discount_id').change(function(){
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
        let stock_manage=$('#stock_manage').val();
        let brand_id=$('#brand_id').val();
        let type=$('#type').val();

        let user_id=$('#user_id').val();
        let category_id=$('#category_id').val();
        let status=$('#status').val();
        let discount_id=$('#discount_id').val();
        let ecom_status=$('#ecom_status').val();
    
        $('#member_data').html('');
        $.ajax({
            url: '{{ route("products.index")}}?page='+page,
            type: 'GET',
            data:{q,type,stock_manage,type,brand_id,category_id,user_id,status, discount_id, ecom_status},
            dataType: 'html',
            success: function(data) {
                $('#data').html(data);
            }
        });
    }
  });

  $('#common_modal').on('shown.bs.modal', function () {
    	
    	productType();

    	$('.product_type').change(function(){
	        productType();
	    });

    	function productType(){
    		let type=$('.product_type').val();
    		if (type=='single') {
    			$('.variable_section').hide();
    		}else if (type=='variable') {
    			$('.variable_section').show();
    		}

    	}


    	$('#generate').on('click', function () {

    		let variants=[];
    		let new_variants=[];
    		$('.variants').each(function(index) {
			    var text = $(this).text().trim();
			    let values=$('.'+text+':checked').map(function () { return this.value; }).get();
			    if (values.length) {
			    	variants[text]=values;

			    	new_variants.push({
		                [text]: values,
		            });
			    }
			    
			});
    		
    		let jsonData = JSON.stringify(new_variants);

    		$('.variant_values').val(jsonData);

    		
            let variantValues = Object.values(variants);

            // Cartesian product function
            function cartesian(arr) {
                return arr.reduce((a, b) => a.flatMap(d => b.map(e => [].concat(d, e))));
            }

            let combinations = cartesian(variantValues);
        
   
            let old_ids=[];
            let new_ids=[];

            $('#variationTable tbody tr').each(function() {
			    var rowText = $(this).attr('class');
			    old_ids.push(rowText);
			});
			
	        combinations.forEach(function (combo) {
              // Normalize: if not an array, wrap it so .join always exists
              let arr = Array.isArray(combo) ? combo : [combo];
              // guard against null/undefined
              arr = arr.map(x => x == null ? '' : String(x));
            
              let variationName = arr.join('-');
              new_ids.push(variationName);
            });

            let notInNew = old_ids.filter(item => !new_ids.includes(item));

            $.each(notInNew, function(index, value) {
			    $(`tr.${value}`).remove();
			});

            combinations.forEach(function (combo, index) {
                let arr = Array.isArray(combo) ? combo : [combo];
                arr = arr.map(v => String(v ?? '').trim());
            
                // ✅ Create variation name & SKU safely
                let variationName = arr.join('-');
                let skuPart = arr.map(val => val.substring(0, 10).toLowerCase()).join('-');
                let main_sku = $('.main_sku').val() || '';
            
                let sku = `${main_sku}-${skuPart}-${index + 1}`;
            
                // ✅ Check for existing row
                let pprice = 0;
                let sprice = 0;
                let existtr = $(`tr.${variationName}`);
            
                if (existtr.length > 0) {
                    pprice = existtr.find('td.pprice input').val() || 0;
                    sprice = existtr.find('td.sprice input').val() || 0;
                    existtr.remove();
                }
    
               

                let row = `
                    <tr class="${variationName}">
                        <td>
                            ${variationName}
                            <input type="hidden" name="variations[${index}][name]" value="${variationName}">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="variations[${index}][sub_sku]" value="${sku}">
                        </td>
                        <td class="pprice">
                            <input type="number" class="form-control" name="variations[${index}][purchase_price]" value="${pprice}" step="any">
                        </td>
                        <td class="sprice">
                            <input type="number" class="form-control" name="variations[${index}][sell_price]" value="${sprice}" step="any">
                        </td>
                    </tr>
                `;
                $('#variationTable tbody').append(row);
            });
        });
    	
	});
	
	$('#common_modal').on('shown.bs.modal', function () {

        ClassicEditor
            .create( document.querySelector( '#editor' ),{
                ckfinder: {
                    uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
    
                }
    
            })
    
            .catch( error => {
            });
            
        ClassicEditor
            .create( document.querySelector( '#editor2' ),{
                ckfinder: {
                    uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
    
                }
    
            })
    
            .catch( error => {
            });
            
    });

    
    
    $('#common_modal').on('shown.bs.modal', function () {
        
            
        $(document).on('change', '.category_id',function () {
            var category_id = $(this).val();
        
            $('.sub_category_id').empty().append('<option value="">Loading...</option>');
        
            $.ajax({
                url: '{{ route("getSubCategory")}}',
                type: 'GET',
                data:{category_id},
                success: function (data) {
                    $('.sub_category_id').empty().append('<option value="">Select One</option>');
                    $.each(data, function (key, value) {
                        $('.sub_category_id').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
                }
            });
        });
        
        
    });
  	

    //multiple image delete section- single image delete
    $(document).on('click', '.ajax-delete-btn', function (e) {
        e.preventDefault();

        if (!confirm('Are you sure you want to delete this image?')) {
            return false;
        }

        let button = $(this);
        let deleteUrl = button.data('url');
        let cardWrapper = button.closest('.image-card-wrapper');

        $.ajax({
            url: deleteUrl,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.status === true) {
                    cardWrapper.fadeOut(300, function () {
                        $(this).remove();
                        let currentCount = parseInt($('.image-count-badge').text());
                        if (!isNaN(currentCount) && currentCount > 0) {
                            $('.image-count-badge').text((currentCount - 1) + ' Images');
                        }
                    });
                } else {
                    alert(response.msg || 'Failed to delete image!');
                }
            },
            error: function (xhr) {
                alert('Something went wrong while deleting!');
            }
        });
    });

</script>
@endpush


